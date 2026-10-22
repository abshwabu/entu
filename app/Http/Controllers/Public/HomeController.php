<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the dynamic portfolio homepage.
     * Caches page data for 10 minutes (600s), busted on model mutations.
     */
    public function index(): View
    {
        $data = Cache::remember('home_page_data', 600, function () {
            // Eager load category and media to eliminate N+1 queries
            $featuredProjects = Project::with(['category', 'media'])
                ->published()
                ->featured()
                ->ordered()
                ->take(6)
                ->get();

            // Supplement with additional published projects if fewer than 6 are featured
            if ($featuredProjects->count() < 6) {
                $additional = Project::with(['category', 'media'])
                    ->published()
                    ->whereNotIn('id', $featuredProjects->pluck('id'))
                    ->ordered()
                    ->take(6 - $featuredProjects->count())
                    ->get();

                $featuredProjects = $featuredProjects->concat($additional);
            }

            $services = Service::active()
                ->ordered()
                ->get();

            $testimonials = Testimonial::with('media')
                ->published()
                ->ordered()
                ->get();

            $heroProjects = $featuredProjects->take(4);

            $setting = Setting::with('media')->first() ?? new Setting();

            return [
                'featuredProjects' => $featuredProjects,
                'services' => $services,
                'testimonials' => $testimonials,
                'heroProjects' => $heroProjects,
                'setting' => $setting,
            ];
        });

        return view('pages.home', $data);
    }
}
