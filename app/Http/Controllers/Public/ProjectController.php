<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display the portfolio gallery index or category filtered view.
     */
    public function index(Request $request, ?Category $category = null): View|JsonResponse
    {
        $categories = Category::ordered()
            ->withCount(['projects' => function ($query) {
                $query->where('is_published', true);
            }])
            ->get();

        $allCount = Project::published()->count();

        $query = Project::published()
            ->with(['category', 'media'])
            ->ordered();

        if ($category && $category->exists) {
            $query->where('category_id', $category->id);
        }

        $projects = $query->paginate(12)->withQueryString();

        if ($request->ajax() || $request->wantsJson() || $request->has('ajax') || $request->header('X-Alpine-Request')) {
            $html = view('pages.projects._grid_items', compact('projects'))->render();

            return response()->json([
                'html' => $html,
                'hasMorePages' => $projects->hasMorePages(),
                'nextPageUrl' => $projects->nextPageUrl(),
                'currentPage' => $projects->currentPage(),
                'total' => $projects->total(),
                'categoryName' => $category?->name ?? 'All Work',
                'categorySlug' => $category?->slug ?? '',
                'pageTitle' => ($category ? "{$category->name} Portfolio" : 'Selected Work & Portfolio') . ' — ' . config('app.name', 'Entu Creative'),
            ]);
        }

        return view('pages.projects.index', compact('projects', 'categories', 'category', 'allCount'));
    }

    /**
     * Display the project case study detail page.
     */
    public function show(Request $request, string $category, string $project): View|RedirectResponse
    {
        $canPreview = auth()->check();

        $query = Project::where('slug', $project)->with(['category', 'media']);

        if (!$canPreview) {
            $query->published();
        }

        $projectModel = $query->firstOrFail();

        // If category is provided in URL but doesn't match the project's actual category, redirect to canonical
        if ($projectModel->category && $projectModel->category->slug !== $category) {
            return redirect()->route('work.show', [
                'category' => $projectModel->category->slug,
                'project' => $projectModel->slug,
            ], 301);
        }

        // Adjacent project navigation (wrap-around enabled)
        $prevProject = Project::published()
            ->where(function ($q) use ($projectModel) {
                $q->where('sort_order', '<', $projectModel->sort_order)
                  ->orWhere(function ($sub) use ($projectModel) {
                      $sub->where('sort_order', $projectModel->sort_order)
                          ->where('published_at', '>', $projectModel->published_at);
                  });
            })
            ->where('id', '!=', $projectModel->id)
            ->orderByDesc('sort_order')
            ->orderBy('published_at')
            ->first();

        if (!$prevProject) {
            $prevProject = Project::published()
                ->where('id', '!=', $projectModel->id)
                ->orderByDesc('sort_order')
                ->orderBy('published_at')
                ->first();
        }

        $nextProject = Project::published()
            ->where(function ($q) use ($projectModel) {
                $q->where('sort_order', '>', $projectModel->sort_order)
                  ->orWhere(function ($sub) use ($projectModel) {
                      $sub->where('sort_order', $projectModel->sort_order)
                          ->where('published_at', '<', $projectModel->published_at);
                  });
            })
            ->where('id', '!=', $projectModel->id)
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->first();

        if (!$nextProject) {
            $nextProject = Project::published()
                ->where('id', '!=', $projectModel->id)
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->first();
        }

        // Related projects in the same category
        $relatedProjects = collect();
        if ($projectModel->category_id) {
            $relatedProjects = Project::published()
                ->where('category_id', $projectModel->category_id)
                ->where('id', '!=', $projectModel->id)
                ->with(['category', 'media'])
                ->take(3)
                ->get();
        }

        if ($relatedProjects->count() < 3) {
            $excludeIds = $relatedProjects->pluck('id')->push($projectModel->id);
            $additional = Project::published()
                ->whereNotIn('id', $excludeIds)
                ->with(['category', 'media'])
                ->take(3 - $relatedProjects->count())
                ->get();

            $relatedProjects = $relatedProjects->merge($additional);
        }

        $lightboxItems = $projectModel->lightbox_items;
        $project = $projectModel;

        return view('pages.projects.show', compact(
            'project',
            'prevProject',
            'nextProject',
            'relatedProjects',
            'lightboxItems',
            'canPreview'
        ));
    }

    /**
     * Direct project slug access (/project/{slug})
     */
    public function showDirect(Request $request, string $project): View|RedirectResponse
    {
        $canPreview = auth()->check();

        $query = Project::where('slug', $project);
        if (!$canPreview) {
            $query->published();
        }

        $projectModel = $query->firstOrFail();

        if ($projectModel->category) {
            return redirect()->route('work.show', [
                'category' => $projectModel->category->slug,
                'project' => $projectModel->slug,
            ], 301);
        }

        return $this->show($request, '', $projectModel->slug);
    }
}
