<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $setting = Setting::firstOrCreate(
            ['id' => 1],
            [
                'site_title' => 'Entu Creative — Graphic Designer & Art Director',
                'tagline' => 'Bold visual identities and thoughtful packaging design.',
                'hero_heading' => 'Visual Identity & Graphic Design That Commands Attention',
                'hero_subheading' => 'Hi, I’m Entu — a multidisciplinary graphic designer specializing in brand identity, packaging, and digital art direction.',
                'about_heading' => 'Designing with Purpose, Crafting with Heart',
                'about_body' => '<p>With over 7 years of professional experience, I partner with forward-thinking brands, cultural institutions, and ambitious startups worldwide.</p><p>My approach blends deep contextual research with bold, expressive typography and intentional palettes to produce work that resonates across print and digital mediums.</p>',
                'email' => 'hello@entucreative.com',
                'phone' => '+251 911 234 567',
                'location' => 'Addis Ababa, Ethiopia',
                'instagram' => 'https://instagram.com/entucreative',
                'behance' => 'https://behance.net/entucreative',
                'dribbble' => 'https://dribbble.com/entucreative',
                'linkedin' => 'https://linkedin.com/in/entucreative',
                'facebook' => 'https://facebook.com/entucreative',
                'tiktok' => 'https://tiktok.com/@entucreative',
                'footer_text' => '© 2026 Entu Creative. All rights reserved. Crafted with intentionality.',
            ]
        );

        $tempDir = storage_path('app/temp_seed_settings');
        File::ensureDirectoryExists($tempDir);

        // Bio photo placeholder
        if ($setting->getMedia('bio_photo')->isEmpty()) {
            $bioPath = $tempDir . '/entu_bio_portrait.png';
            $image = imagecreatetruecolor(600, 800);
            $bgColor = imagecolorallocate($image, 30, 41, 59);
            $accentColor = imagecolorallocate($image, 217, 119, 6);
            $whiteColor = imagecolorallocate($image, 255, 255, 255);

            imagefill($image, 0, 0, $bgColor);
            imagefilledrectangle($image, 30, 30, 570, 770, $accentColor);
            imagefilledrectangle($image, 34, 34, 566, 766, $bgColor);
            imagestring($image, 5, 180, 380, 'ENTU CREATIVE', $whiteColor);
            imagestring($image, 4, 175, 410, 'Graphic Designer & Art Director', $whiteColor);
            imagepng($image, $bioPath, 6);

            $setting->addMedia($bioPath)->preservingOriginal()->toMediaCollection('bio_photo');
        }

        // CV placeholder file
        if ($setting->getMedia('cv')->isEmpty()) {
            $cvPath = $tempDir . '/Entu_Creative_Resume.pdf';
            // A valid minimal PDF
            $pdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f\n0000000009 00000 n\n0000000052 00000 n\n0000000101 00000 n\ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n178\n%%EOF";
            File::put($cvPath, $pdfContent);

            $setting->addMedia($cvPath)->preservingOriginal()->toMediaCollection('cv');
        }

        File::deleteDirectory($tempDir);
    }
}
