<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectGalleryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            AdminUserSeeder::class,
            CategorySeeder::class,
            ProjectSeeder::class,
            SettingSeeder::class,
        ]);
    }

    public function test_portfolio_index_page_loads_with_published_projects_and_filter_pills(): void
    {
        $response = $this->get('/work');

        $response->assertSuccessful();
        $response->assertSee('Selected Work');
        $response->assertSee('All Projects');

        // Check categories in filter pills
        $categories = Category::all();
        foreach ($categories as $cat) {
            $response->assertSee($cat->name);
            $response->assertSee(route('work.category', $cat->slug));
        }

        // Check published projects appear
        $firstProject = Project::published()->first();
        $this->assertNotNull($firstProject);
        $response->assertSee($firstProject->title);
    }

    public function test_portfolio_category_filter_page_filters_projects(): void
    {
        $brandingCategory = Category::where('name', 'Branding')->firstOrFail();
        $response = $this->get('/work/' . $brandingCategory->slug);

        $response->assertSuccessful();
        $response->assertSee($brandingCategory->name);

        // Projects belonging to Branding should be present
        $brandingProjects = Project::published()->where('category_id', $brandingCategory->id)->get();
        foreach ($brandingProjects as $bp) {
            $response->assertSee($bp->title);
        }

        // Non-existent category returns 404
        $notFoundResponse = $this->get('/work/non-existent-category-slug');
        $notFoundResponse->assertNotFound();
    }

    public function test_ajax_request_returns_json_for_smooth_filtering_and_load_more(): void
    {
        $response = $this->getJson('/work', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'html',
            'hasMorePages',
            'nextPageUrl',
            'currentPage',
            'total',
            'categoryName',
            'categorySlug',
            'pageTitle',
        ]);
    }

    public function test_project_detail_page_loads_with_all_elements_meta_and_lightbox(): void
    {
        $project = Project::published()->whereNotNull('category_id')->firstOrFail();
        $category = $project->category;

        $url = route('work.show', ['category' => $category->slug, 'project' => $project->slug]);
        $response = $this->get($url);

        $response->assertSuccessful();

        // Title, client, year, category, tools
        $response->assertSee($project->title);
        if ($project->client) {
            $response->assertSee($project->client);
        }
        if ($project->year) {
            $response->assertSee($project->year);
        }
        $response->assertSee($category->name);

        // Tools chips
        foreach ($project->tools_used ?? [] as $tool) {
            $response->assertSee($tool);
        }

        // Rich text description
        if ($project->description) {
            $response->assertSee(strip_tags($project->description), false);
        }

        // SEO meta tags
        $expectedTitle = $project->seo_title ?: $project->title;
        $response->assertSee('<title>' . e($expectedTitle) . ' — ' . config('app.name', 'Entu Creative') . '</title>', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:description"', false);
        $response->assertSee('property="og:image"', false);
        $response->assertSee('content="article"', false);

        // Lightbox accessibility and markup
        $response->assertSee('role="dialog"', false);
        $response->assertSee('aria-modal="true"', false);
        $response->assertSee('@keydown.escape.window', false);
        $response->assertSee('@keydown.right.window', false);
        $response->assertSee('@keydown.left.window', false);
        $response->assertSee('@touchstart="handleTouchStart($event)"', false);
        $response->assertSee('@touchend="handleTouchEnd($event)"', false);

        // Adjacent project navigation
        $response->assertSee('Previous Project');
        $response->assertSee('Next Project');

        // Related projects section
        $response->assertSee('Related Projects');
    }

    public function test_direct_project_url_redirects_to_canonical_work_url(): void
    {
        $project = Project::published()->whereNotNull('category_id')->firstOrFail();

        $directUrl = route('project.show', ['project' => $project->slug]);
        $response = $this->get($directUrl);

        $expectedUrl = route('work.show', ['category' => $project->category->slug, 'project' => $project->slug]);
        $response->assertRedirect($expectedUrl);
    }

    public function test_mismatched_category_in_url_redirects_to_canonical_url(): void
    {
        $project = Project::published()->whereNotNull('category_id')->firstOrFail();

        $wrongCategoryUrl = '/work/wrong-category-slug/' . $project->slug;
        $response = $this->get($wrongCategoryUrl);

        $expectedUrl = route('work.show', ['category' => $project->category->slug, 'project' => $project->slug]);
        $response->assertRedirect($expectedUrl);
    }

    public function test_unpublished_project_returns_404_for_guests(): void
    {
        $project = Project::published()->firstOrFail();
        $project->update(['is_published' => false]);

        $url = route('work.show', ['category' => $project->category->slug, 'project' => $project->slug]);
        $response = $this->get($url);

        $response->assertNotFound();

        $directUrl = route('project.show', ['project' => $project->slug]);
        $directResponse = $this->get($directUrl);
        $directResponse->assertNotFound();
    }

    public function test_unpublished_project_can_be_previewed_by_authenticated_admin(): void
    {
        $admin = User::firstOrFail();
        $project = Project::published()->firstOrFail();
        $project->update(['is_published' => false]);

        $url = route('work.show', ['category' => $project->category->slug, 'project' => $project->slug]);
        $response = $this->actingAs($admin)->get($url);

        $response->assertSuccessful();
        $response->assertSee('Preview Mode Active');
        $response->assertSee($project->title);
    }

    public function test_images_have_lazy_loading_and_media_srcset(): void
    {
        $project = Project::published()->whereNotNull('category_id')->firstOrFail();
        $url = route('work.show', ['category' => $project->category->slug, 'project' => $project->slug]);

        $response = $this->get($url);
        $response->assertSuccessful();

        // Responsive cover and gallery attributes
        $response->assertSee('srcset=', false);
        $response->assertSee('600w', false);
        $response->assertSee('1600w', false);
        $response->assertSee('width="1600"', false);
        $response->assertSee('loading="lazy"', false);
    }
}
