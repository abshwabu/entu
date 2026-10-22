<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Message;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DatabaseAndModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_auto_generate_unique_slugs(): void
    {
        $cat1 = Category::create([
            'name' => 'Brand Identity',
            'description' => 'Branding work',
            'sort_order' => 1,
        ]);

        $this->assertEquals('brand-identity', $cat1->slug);

        $cat2 = Category::create([
            'name' => 'Brand Identity',
            'description' => 'Another branding category',
            'sort_order' => 2,
        ]);

        $this->assertEquals('brand-identity-1', $cat2->slug);
    }

    public function test_projects_auto_generate_slugs_and_cast_attributes(): void
    {
        $category = Category::create(['name' => 'Packaging']);

        $project = Project::create([
            'category_id' => $category->id,
            'title' => 'Artisan Coffee Bag',
            'tools_used' => ['Illustrator', 'Photoshop'],
            'is_featured' => true,
            'is_published' => true,
            'published_at' => now(),
            'sort_order' => 1,
        ]);

        $this->assertEquals('artisan-coffee-bag', $project->slug);
        $this->assertIsArray($project->tools_used);
        $this->assertContains('Illustrator', $project->tools_used);
        $this->assertTrue($project->is_featured);
        $this->assertTrue($project->is_published);
        $this->assertInstanceOf(Category::class, $project->category);
    }

    public function test_deleting_category_sets_project_category_id_to_null(): void
    {
        $category = Category::create(['name' => 'Print']);
        $project = Project::create([
            'category_id' => $category->id,
            'title' => 'Exhibition Poster',
            'is_published' => true,
        ]);

        $this->assertEquals($category->id, $project->category_id);

        $category->delete();

        $project->refresh();
        $this->assertNull($project->category_id);
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function test_project_scopes_work_correctly(): void
    {
        $publishedFeatured = Project::create([
            'title' => 'Featured Project',
            'is_published' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $publishedOnly = Project::create([
            'title' => 'Regular Published',
            'is_published' => true,
            'is_featured' => false,
            'sort_order' => 2,
        ]);

        $draft = Project::create([
            'title' => 'Draft Project',
            'is_published' => false,
            'is_featured' => true,
            'sort_order' => 3,
        ]);

        $featured = Project::published()->featured()->get();

        $this->assertTrue($featured->contains('id', $publishedFeatured->id));
        $this->assertFalse($featured->contains('id', $publishedOnly->id));
        $this->assertFalse($featured->contains('id', $draft->id));
    }

    public function test_project_soft_deletes(): void
    {
        $project = Project::create([
            'title' => 'Soft Delete Project',
            'is_published' => true,
        ]);

        $project->delete();

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
        $this->assertCount(0, Project::all());
        $this->assertCount(1, Project::withTrashed()->get());
    }

    public function test_services_model_and_active_scope(): void
    {
        $active = Service::create([
            'title' => 'Brand Design',
            'description' => 'Logos and identities',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $inactive = Service::create([
            'title' => 'Old Service',
            'description' => 'Deprecated',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $services = Service::active()->ordered()->get();
        $this->assertTrue($services->contains('id', $active->id));
        $this->assertFalse($services->contains('id', $inactive->id));
    }

    public function test_testimonials_model_and_scopes(): void
    {
        $published = Testimonial::create([
            'author_name' => 'Jane Doe',
            'author_role' => 'Founder',
            'quote' => 'Exceptional work.',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $unpublished = Testimonial::create([
            'author_name' => 'John Smith',
            'author_role' => 'Manager',
            'quote' => 'Great experience.',
            'is_published' => false,
            'sort_order' => 2,
        ]);

        $testimonials = Testimonial::published()->ordered()->get();
        $this->assertTrue($testimonials->contains('id', $published->id));
        $this->assertFalse($testimonials->contains('id', $unpublished->id));
    }

    public function test_messages_model_and_read_state(): void
    {
        $message = Message::create([
            'name' => 'Abebe Bikila',
            'email' => 'abebe@example.com',
            'subject' => 'New Project Inquiry',
            'body' => 'I would like to hire you for a branding project.',
        ]);

        $this->assertNull($message->read_at);
        $this->assertTrue(Message::unread()->exists());

        $message->markAsRead();
        $message->refresh();

        $this->assertNotNull($message->read_at);
        $this->assertFalse(Message::unread()->exists());
        $this->assertTrue(Message::read()->exists());
    }

    public function test_setting_helper_cached_and_busted_on_save(): void
    {
        Cache::forget('app_settings');

        $this->assertEquals('Default Site', setting('site_title', 'Default Site'));

        $setting = Setting::create([
            'site_title' => 'Entu Creative',
            'tagline' => 'Graphic Design',
            'email' => 'hello@entucreative.com',
        ]);

        $this->assertEquals('Entu Creative', setting('site_title'));
        $this->assertEquals('Graphic Design', setting('tagline'));
        $this->assertEquals('hello@entucreative.com', setting('email'));

        // Update busts cache
        $setting->update(['site_title' => 'Entu Studio']);
        $this->assertEquals('Entu Studio', setting('site_title'));

        // Calling setting() without args returns Setting instance
        $this->assertInstanceOf(Setting::class, setting());
    }
}
