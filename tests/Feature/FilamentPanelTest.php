<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\MessageResource;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ServiceResource;
use App\Filament\Resources\TestimonialResource;
use App\Models\Category;
use App\Models\Message;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminUserSeeder::class);
        $this->admin = User::where('email', env('ADMIN_EMAIL', 'admin@entucreative.com'))->first();
    }

    public function test_admin_user_can_login_with_env_credentials(): void
    {
        Livewire::test(\Filament\Pages\Auth\Login::class)
            ->fillForm([
                'email' => env('ADMIN_EMAIL', 'admin@entucreative.com'),
                'password' => env('ADMIN_PASSWORD', 'password'),
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect(filament()->getUrl());

        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_dashboard_renders_with_widgets(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin');

        $response->assertSuccessful();
        $response->assertSee('Entu Creative');
    }

    public function test_project_resource_crud_and_reorder(): void
    {
        $this->actingAs($this->admin);

        $category = Category::create(['name' => 'Brand Identity']);

        // Test List Projects
        Livewire::test(ProjectResource\Pages\ListProjects::class)
            ->assertSuccessful();

        Storage::fake('public');
        $cover = UploadedFile::fake()->image('cover.png', 800, 600);
        $gallery1 = UploadedFile::fake()->image('gallery1.png', 800, 600);

        // Test Create Project
        Livewire::test(ProjectResource\Pages\CreateProject::class)
            ->fillForm([
                'title' => 'Brand Identity For Zenith',
                'slug' => 'brand-identity-for-zenith',
                'category_id' => $category->id,
                'client' => 'Zenith Tech',
                'year' => '2026',
                'summary' => 'Tech branding project.',
                'tools_used' => ['Illustrator', 'Figma'],
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->toDateTimeString(),
                'cover' => [$cover],
                'gallery' => [$gallery1],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $project = Project::where('slug', 'brand-identity-for-zenith')->first();
        $this->assertNotNull($project);
        $this->assertTrue($project->is_featured);
        $this->assertTrue($project->hasMedia('cover'));
        $this->assertTrue($project->hasMedia('gallery'));

        // Test Edit Project
        Livewire::test(ProjectResource\Pages\EditProject::class, ['record' => $project->getRouteKey()])
            ->fillForm([
                'title' => 'Zenith Tech Brand Evolution',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertEquals('Zenith Tech Brand Evolution', $project->fresh()->title);

        // Test Reorder
        $project2 = Project::create([
            'title' => 'Another Project',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        Livewire::test(ProjectResource\Pages\ListProjects::class)
            ->call('reorderTable', [$project2->id, $project->id])
            ->assertSuccessful();

        // Test Delete Project
        Livewire::test(ProjectResource\Pages\EditProject::class, ['record' => $project->getRouteKey()])
            ->callAction('delete');

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    public function test_category_resource_crud(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CategoryResource\Pages\ListCategories::class)
            ->assertSuccessful();

        Livewire::test(CategoryResource\Pages\CreateCategory::class)
            ->fillForm([
                'name' => 'Packaging Design',
                'slug' => 'packaging-design',
                'description' => 'Box and label design',
                'sort_order' => 1,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $category = Category::where('slug', 'packaging-design')->first();
        $this->assertNotNull($category);

        Livewire::test(CategoryResource\Pages\EditCategory::class, ['record' => $category->getRouteKey()])
            ->fillForm([
                'description' => 'Updated packaging description',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertEquals('Updated packaging description', $category->fresh()->description);
    }

    public function test_service_resource_crud(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ServiceResource\Pages\ListServices::class)
            ->assertSuccessful();

        Livewire::test(ServiceResource\Pages\CreateService::class)
            ->fillForm([
                'title' => 'Art Direction',
                'description' => 'Visual creative direction',
                'icon' => 'heroicon-o-paint-brush',
                'is_active' => true,
                'sort_order' => 1,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('services', ['title' => 'Art Direction']);
    }

    public function test_testimonial_resource_crud(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(TestimonialResource\Pages\ListTestimonials::class)
            ->assertSuccessful();

        Storage::fake('public');
        $avatar = UploadedFile::fake()->image('client.png', 200, 200);

        Livewire::test(TestimonialResource\Pages\CreateTestimonial::class)
            ->fillForm([
                'author_name' => 'Michael Scott',
                'author_role' => 'Regional Manager, Dunder Mifflin',
                'quote' => 'Best design experience of my life.',
                'is_published' => true,
                'sort_order' => 1,
                'avatar' => [$avatar],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $testimonial = Testimonial::where('author_name', 'Michael Scott')->first();
        $this->assertNotNull($testimonial);
        $this->assertTrue($testimonial->hasMedia('avatar'));
    }

    public function test_message_resource_unread_badge_view_and_mark_as_read(): void
    {
        $this->actingAs($this->admin);

        $message = Message::create([
            'name' => 'Alice Walker',
            'email' => 'alice@example.com',
            'subject' => 'Project Collaboration',
            'body' => 'I would like to discuss a project.',
        ]);

        $this->assertEquals(1, MessageResource::getNavigationBadge());

        // Viewing the message marks it as read
        $this->get('/admin/messages/' . $message->id)
            ->assertSuccessful();

        $this->assertNotNull($message->fresh()->read_at);
        $this->assertNull(MessageResource::getNavigationBadge());
    }

    public function test_site_settings_page_saves_and_updates_helper(): void
    {
        $this->actingAs($this->admin);

        Setting::firstOrCreate(['id' => 1]);

        Livewire::test(ManageSettings::class)
            ->assertSuccessful()
            ->fillForm([
                'site_title' => 'Entu Creative Portfolio Updated',
                'tagline' => 'Crafting distinct visual identities.',
                'hero_heading' => 'Headline for Hero',
                'email' => 'contact@entucreative.com',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertEquals('Entu Creative Portfolio Updated', setting('site_title'));
        $this->assertEquals('Crafting distinct visual identities.', setting('tagline'));
        $this->assertEquals('contact@entucreative.com', setting('email'));
    }
}
