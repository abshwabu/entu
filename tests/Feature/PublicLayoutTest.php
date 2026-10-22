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
use Tests\TestCase;

class PublicLayoutTest extends TestCase
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

    public function test_homepage_renders_successfully_with_public_layout(): void
    {
        $response = $this->get('/');

        $response->assertSuccessful();

        // SEO and Meta Tags
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:description"', false);
        $response->assertSee('name="twitter:card"', false);

        // Google Fonts
        $response->assertSee('family=Syne', false);
        $response->assertSee('family=Plus+Jakarta+Sans', false);

        // Anti-FOUT dark mode script
        $response->assertSee('localStorage.getItem(\'theme\')', false);

        // Custom cursor elements
        $response->assertSee('id="custom-cursor"', false);
        $response->assertSee('id="custom-cursor-dot"', false);
    }

    public function test_navbar_and_footer_pull_live_data_from_settings(): void
    {
        $setting = Setting::first();
        $setting->update([
            'site_title' => 'Entu Master Designer',
            'tagline' => 'Bespoke Brand & Packaging Systems',
            'email' => 'collaborate@entucreative.com',
            'phone' => '+251 900 111 222',
            'location' => 'Bole Subcity, Addis Ababa',
            'footer_text' => '© 2026 Entu Custom Studio',
        ]);

        $response = $this->get('/');

        $response->assertSuccessful();
        $response->assertSee('collaborate@entucreative.com');
        $response->assertSee('Bespoke Brand & Packaging Systems');
        $response->assertSee('+251 900 111 222');
        $response->assertSee('Bole Subcity, Addis Ababa');
        $response->assertSee('© 2026 Entu Custom Studio');
    }

    public function test_homepage_displays_projects_services_and_testimonials(): void
    {
        $response = $this->get('/');

        $response->assertSuccessful();

        // Projects
        $response->assertSee('Kaffa Single-Origin Roasters');
        $response->assertSee('Zoma Eco Architecture Institute');

        // Services
        $response->assertSee('Brand Identity & Visual Systems');
        $response->assertSee('Packaging & Sensory Print Design');

        // Testimonials
        $response->assertSee('Sarah Jenkins');
        $response->assertSee('Dawit Wolde');
    }

    public function test_components_are_responsive_and_accessible(): void
    {
        $response = $this->get('/');

        $response->assertSuccessful();

        // Responsive grid and container classes
        $response->assertSee('max-w-7xl');
        $response->assertSee('grid-cols-1 md:grid-cols-3');

        // Semantic and accessible tags
        $response->assertSee('<header', false);
        $response->assertSee('<nav', false);
        $response->assertSee('<main id="main-content"', false);
        $response->assertSee('<footer', false);
        $response->assertSee('aria-label="Toggle dark mode theme"', false);
        $response->assertSee('aria-label="Back to top of page"', false);
    }
}
