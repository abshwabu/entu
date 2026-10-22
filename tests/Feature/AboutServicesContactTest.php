<?php

namespace Tests\Feature;

use App\Jobs\SendTelegramNotification;
use App\Mail\NewContactMessageMail;
use App\Models\Message;
use App\Models\Service;
use App\Models\Setting;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AboutServicesContactTest extends TestCase
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

    public function test_about_page_renders_with_bio_photo_story_skills_and_cv_download(): void
    {
        $response = $this->get('/about');

        $response->assertSuccessful();

        $setting = Setting::first();
        $this->assertNotNull($setting);

        // Story from settings
        $response->assertSee($setting->about_heading);
        $response->assertSee('With over 7 years of professional experience');
        $response->assertSee('deep contextual research with bold');

        // Studio location & email
        $response->assertSee($setting->location);

        // Skills and software tools
        $response->assertSee('Brand Strategy');
        $response->assertSee('Adobe Illustrator');
        $response->assertSee('Figma');

        // Download CV button (if media attached)
        if ($setting->getFirstMedia('cv')) {
            $response->assertSee('Download CV / Resume (PDF)');
        }

        // Social links
        $response->assertSee('Behance');
        $response->assertSee('Instagram');
    }

    public function test_services_page_renders_detailed_service_cards_and_process(): void
    {
        $response = $this->get('/services');

        $response->assertSuccessful();

        $services = Service::active()->get();
        foreach ($services as $service) {
            $response->assertSee($service->title);
            $response->assertSee($service->description);
        }

        // Process steps
        $response->assertSee('Discovery & Strategic Foundation');
        $response->assertSee('Concept Exploration & Art Direction');

        // Contact CTA
        $response->assertSee(route('contact'));
    }

    public function test_contact_page_renders_form_and_contact_details(): void
    {
        $response = $this->get('/contact');

        $response->assertSuccessful();

        // Contact details
        $setting = Setting::first();
        $response->assertSee($setting->email);
        $response->assertSee($setting->phone);
        $response->assertSee($setting->location);

        // Form elements
        $response->assertSee('action="' . route('contact.store') . '"', false);
        $response->assertSee('name="name"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="subject"', false);
        $response->assertSee('name="message"', false);
        $response->assertSee('name="website"', false); // Honeypot
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->from('/contact')->post('/contact', []);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_contact_form_validates_email_format(): void
    {
        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Valid Name',
            'email' => 'not-an-email',
            'message' => 'This is a message with sufficient character length.',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors(['email']);
    }

    public function test_contact_form_validates_message_minimum_length(): void
    {
        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Valid Name',
            'email' => 'valid@example.com',
            'message' => 'Too short',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors(['message']);
    }

    public function test_contact_form_submission_stores_message_and_queues_email(): void
    {
        Mail::fake();
        Bus::fake([SendTelegramNotification::class]);

        $payload = [
            'name' => 'Sara Jenkins',
            'email' => 'sara@brandco.com',
            'subject' => 'Packaging System for Artisan Roastery',
            'message' => 'We are preparing to launch our new product line and need an exceptional visual identity.',
        ];

        $response = $this->from('/contact')->post('/contact', $payload);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('success');

        // Message stored in database
        $this->assertDatabaseHas('messages', [
            'name' => 'Sara Jenkins',
            'email' => 'sara@brandco.com',
            'subject' => 'Packaging System for Artisan Roastery',
            'body' => 'We are preparing to launch our new product line and need an exceptional visual identity.',
        ]);

        // Email queued to recipient
        $expectedRecipient = setting('email', config('mail.from.address'));
        Mail::assertQueued(NewContactMessageMail::class, function ($mail) use ($expectedRecipient) {
            return $mail->hasTo($expectedRecipient)
                && $mail->contactMessage->name === 'Sara Jenkins';
        });

        // Telegram job dispatched
        Bus::assertDispatched(SendTelegramNotification::class);
    }

    public function test_honeypot_silently_discards_spam_without_storage_or_email(): void
    {
        Mail::fake();

        $initialCount = Message::count();

        $payload = [
            'name' => 'Spam Bot',
            'email' => 'bot@spammer.xyz',
            'subject' => 'Buy cheap watches online',
            'message' => 'Click here for the best discounts and special deals today!',
            'website' => 'http://spam-site-trap.com', // Filled honeypot
        ];

        $response = $this->from('/contact')->post('/contact', $payload);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('success'); // Returns success so bot thinks it worked

        // Message should NOT be stored
        $this->assertSame($initialCount, Message::count());
        $this->assertDatabaseMissing('messages', [
            'email' => 'bot@spammer.xyz',
        ]);

        // Email should NOT be sent
        Mail::assertNothingQueued();
        Mail::assertNothingSent();
    }

    public function test_contact_form_rate_limiting_throttles_after_five_requests(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Rate Tester',
            'email' => 'tester@example.com',
            'message' => 'Valid message content exceeding minimum requirement.',
        ];

        // 5 requests succeed
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/contact', $payload);
            $response->assertRedirect('/contact');
        }

        // 6th request within the same minute is throttled (429)
        $throttledResponse = $this->post('/contact', $payload);
        $throttledResponse->assertStatus(429);
    }
}
