<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'site_title' => 'Entu Creative — Graphic Designer & Art Director',
            'tagline' => 'Bold visual identities and thoughtful packaging design.',
            'hero_heading' => 'Visual Identity & Graphic Design That Commands Attention',
            'hero_subheading' => 'Hi, I’m Entu — a multidisciplinary graphic designer specializing in brand identity, packaging, and digital art direction.',
            'about_heading' => 'Designing with Purpose, Crafting with Heart',
            'about_body' => 'With over 7 years of design experience, I help ambitious brands discover their visual voice. From early-stage startup identities to nationwide packaging rollouts, I merge strategic thinking with expressive aesthetics to craft unforgettable brand experiences.',
            'email' => 'hello@entucreative.com',
            'phone' => '+251 911 234 567',
            'location' => 'Addis Ababa, Ethiopia',
            'instagram' => 'https://instagram.com/entucreative',
            'behance' => 'https://behance.net/entucreative',
            'dribbble' => 'https://dribbble.com/entucreative',
            'linkedin' => 'https://linkedin.com/in/entucreative',
            'facebook' => 'https://facebook.com/entucreative',
            'tiktok' => 'https://tiktok.com/@entucreative',
            'footer_text' => '© 2026 Entu Creative. All rights reserved. Crafted with passion.',
        ];
    }
}
