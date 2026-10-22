<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\Setting;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoPerformancePolishTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            AdminUserSeeder::class,
            CategorySeeder::class,
            ProjectSeeder::class,
            ServiceSeeder::class,
            SettingSeeder::class,
        ]);
    }

    public function test_security_headers_are_present_on_all_responses(): void
    {
        $response = $this->get('/');

        $response->assertSuccessful();
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        $this->assertTrue($response->headers->has('Content-Security-Policy'));
        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString('https://fonts.googleapis.com', $csp);
        $this->assertStringContainsString('https://fonts.gstatic.com', $csp);
    }

    public function test_homepage_contains_person_json_ld_schema(): void
    {
        $response = $this->get('/');

        $response->assertSuccessful();
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type": "Person"', false);
        $response->assertSee('"jobTitle": "Graphic Designer & Art Director"', false);
        $response->assertSee(setting('email'), false);
    }

    public function test_project_page_contains_visual_artwork_and_breadcrumb_json_ld_schema(): void
    {
        $project = Project::published()->whereNotNull('category_id')->firstOrFail();
        $url = route('work.show', ['category' => $project->category->slug, 'project' => $project->slug]);

        $response = $this->get($url);

        $response->assertSuccessful();
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type": "VisualArtwork"', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
        $response->assertSee(json_encode($project->title), false);
        $response->assertSee('Entu Creative', false);
    }

    public function test_dynamic_sitemap_xml_lists_all_published_projects_and_categories(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertSuccessful();
        $response->assertHeader('Content-Type', 'application/xml');

        // Check homepage & work
        $response->assertSee('<loc>' . url('/') . '</loc>', false);
        $response->assertSee('<loc>' . route('work.index') . '</loc>', false);

        // Check all categories
        $categories = Category::all();
        foreach ($categories as $cat) {
            $response->assertSee('<loc>' . route('work.category', $cat->slug) . '</loc>', false);
        }

        // Check published projects
        $projects = Project::published()->get();
        foreach ($projects as $proj) {
            $response->assertSee('<loc>' . $proj->url . '</loc>', false);
        }

        // Check about, services, contact
        $response->assertSee('<loc>' . route('about') . '</loc>', false);
        $response->assertSee('<loc>' . route('services') . '</loc>', false);
        $response->assertSee('<loc>' . route('contact') . '</loc>', false);
    }

    public function test_robots_txt_disallows_admin_and_points_to_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertSuccessful();
        $response->assertSee('User-agent: *');
        $response->assertSee('Disallow: /admin');
        $response->assertSee('Sitemap: ' . url('/sitemap.xml'));
    }

    public function test_custom_404_page_renders_with_brand_layout(): void
    {
        $response = $this->get('/non-existent-page-testing-404-error');

        $response->assertNotFound();
        $response->assertSee('Page Not Found');
        $response->assertSee('Browse Selected Work');
        $response->assertSee('Back to Home');
    }

    public function test_performance_font_preconnect_preload_and_back_to_top_button(): void
    {
        $response = $this->get('/');

        $response->assertSuccessful();

        // Font preconnect & preloads
        $response->assertSee('rel="preconnect" href="https://fonts.googleapis.com"', false);
        $response->assertSee('rel="preconnect" href="https://fonts.gstatic.com"', false);
        $response->assertSee('display=swap', false);

        // Back to top floating button
        $response->assertSee('aria-label="Back to top of page"', false);

        // No-script accessibility fallback
        $response->assertSee('<noscript>', false);
    }

    public function test_analytics_integration_loads_google_or_plausible_only_when_configured(): void
    {
        // 1. Without analytics_id configured, no analytics scripts appear
        $setting = Setting::first();
        $setting->update(['analytics_id' => null]);

        $responseWithout = $this->get('/');
        $responseWithout->assertDontSee('googletagmanager.com');
        $responseWithout->assertDontSee('plausible.io');

        // 2. With Google Analytics GA4 ID
        $setting->update(['analytics_id' => 'G-ABC1234XYZ']);
        $responseGA = $this->get('/');
        $responseGA->assertSee('https://www.googletagmanager.com/gtag/js?id=G-ABC1234XYZ', false);
        $responseGA->assertSee('G-ABC1234XYZ');

        // 3. With Plausible Analytics Domain
        $setting->update(['analytics_id' => 'entucreative.com']);
        $responsePlausible = $this->get('/');
        $responsePlausible->assertSee('https://plausible.io/js/script.js', false);
        $responsePlausible->assertSee('data-domain="entucreative.com"', false);
    }
}
