<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TestimonialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            CategorySeeder::class,
            ProjectSeeder::class,
            ServiceSeeder::class,
            TestimonialSeeder::class,
            SettingSeeder::class,
        ]);
    }

    public function test_home_page_loads_with_all_six_sections(): void
    {
        Cache::forget('home_page_data');

        $response = $this->get('/');

        $response->assertSuccessful();
        $response->assertViewIs('pages.home');

        // 1. Hero
        $response->assertSee('Available for Select Projects');
        $response->assertSee('View my work');
        $response->assertSee('Get in touch');

        // 2. Selected Work
        $response->assertSee('Selected Work');
        $response->assertSee('View all work');

        // 3. Services
        $response->assertSee('Capabilities');
        $response->assertSee('Brand Identity & Visual Systems');

        // 4. About Teaser
        $response->assertSee('About Entu');

        // 5. Testimonials Slider
        $response->assertSee('Client Testimonials Slider', false);
        $response->assertSee('Sarah Jenkins');

        // 6. CTA Band
        $response->assertSee('Let’s create something together.');
    }

    public function test_no_n_plus_one_queries_on_home_page(): void
    {
        Cache::forget('home_page_data');

        $queryCount = 0;
        DB::listen(function ($query) use (&$queryCount) {
            $queryCount++;
        });

        $response = $this->get('/');
        $response->assertSuccessful();

        // 8 total queries on cache miss: projects, categories, project media, services, testimonials, testimonial media, settings, setting media
        $this->assertLessThanOrEqual(10, $queryCount);

        // Next request should hit cache and run 0 database queries
        $cachedQueryCount = 0;
        DB::listen(function ($query) use (&$cachedQueryCount) {
            $cachedQueryCount++;
        });

        $cachedResponse = $this->get('/');
        $cachedResponse->assertSuccessful();

        $this->assertEquals(0, $cachedQueryCount);
    }

    public function test_content_changes_from_models_bust_cache_immediately(): void
    {
        // Populate cache
        $this->get('/');
        $this->assertTrue(Cache::has('home_page_data'));

        // 1. Project update busts cache
        $project = Project::first();
        $project->update(['title' => 'Brand Vanguard Alpha']);
        $this->assertFalse(Cache::has('home_page_data'));

        $response = $this->get('/');
        $response->assertSee('Brand Vanguard Alpha');

        // 2. Service update busts cache
        $service = Service::first();
        $service->update(['title' => 'Kinetic Motion Design']);
        $this->assertFalse(Cache::has('home_page_data'));

        $response = $this->get('/');
        $response->assertSee('Kinetic Motion Design');

        // 3. Testimonial update busts cache
        $testimonial = Testimonial::first();
        $testimonial->update(['author_name' => 'Alexander Wright']);
        $this->assertFalse(Cache::has('home_page_data'));

        $response = $this->get('/');
        $response->assertSee('Alexander Wright');

        // 4. Setting update busts cache
        $setting = Setting::first();
        $setting->update(['hero_heading' => 'Revolutionary Aesthetics For Modern Brands']);
        $this->assertFalse(Cache::has('home_page_data'));

        $response = $this->get('/');
        $response->assertSee('Revolutionary Aesthetics For Modern Brands');
    }
}
