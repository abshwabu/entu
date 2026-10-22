<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $projectsData = [
            [
                'category' => 'Branding',
                'title' => 'Kaffa Single-Origin Roasters',
                'client' => 'Kaffa Coffee Co.',
                'year' => '2024',
                'summary' => 'Comprehensive visual identity celebrating ancestral Ethiopian coffee heritage through modern typography.',
                'description' => '<p>Kaffa Single-Origin Roasters sought a brand evolution that honored their birthplace in southwestern Ethiopia while appealing to contemporary specialty coffee drinkers across Europe and North America.</p><p>We created a bespoke typographic mark inspired by ancient Ge’ez script strokes, paired with warm ochre and deep forest palettes. The identity system spans packaging labels, retail signage, branded merchandise, and an extensive visual guidelines book.</p>',
                'tools_used' => ['Adobe Illustrator', 'Adobe Photoshop', 'Figma', 'InDesign'],
                'project_url' => 'https://kaffaroasters.example.com',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(120),
                'sort_order' => 1,
                'seo_title' => 'Kaffa Single-Origin Roasters — Brand Identity by Entu Creative',
                'seo_description' => 'A modern brand identity crafted for Kaffa Single-Origin Roasters, celebrating Ethiopian coffee heritage.',
                'color' => '#8B5A2B',
            ],
            [
                'category' => 'Branding',
                'title' => 'Zoma Eco Architecture Institute',
                'client' => 'Zoma Museum',
                'year' => '2025',
                'summary' => 'Sustainable institution identity combining organic curves with structural architectural precision.',
                'description' => '<p>Zoma Eco Architecture Institute is dedicated to sustainable, vernacular building techniques. The visual identity reflects rammed-earth textures, natural pigments, and minimalist geometric balance.</p><p>The system was rolled out across educational publications, exhibition posters, environmental wayfinding, and digital touchpoints.</p>',
                'tools_used' => ['Adobe Illustrator', 'InDesign', 'Figma'],
                'project_url' => 'https://zomamuseum.example.com',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(90),
                'sort_order' => 2,
                'seo_title' => 'Zoma Architecture Institute Identity — Entu Creative',
                'seo_description' => 'Sustainable brand identity system for Zoma Eco Architecture Institute.',
                'color' => '#2D5A27',
            ],
            [
                'category' => 'Branding',
                'title' => 'Liyu Botanical Beauty',
                'client' => 'Liyu Organics',
                'year' => '2024',
                'summary' => 'Holistic botanical skincare brand identity highlighting cold-pressed highland oils.',
                'description' => '<p>Liyu Botanical Beauty develops clean skincare from indigenous highland flora. We crafted a serene, ethereal aesthetic using gold foil accents on muted sage backgrounds.</p>',
                'tools_used' => ['Adobe Illustrator', 'Photoshop', 'Blender'],
                'project_url' => 'https://liyuorganics.example.com',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(75),
                'sort_order' => 3,
                'seo_title' => 'Liyu Botanical Beauty Identity — Entu Creative',
                'seo_description' => 'Clean skincare identity and visual branding by Entu Creative.',
                'color' => '#5C6B50',
            ],
            [
                'category' => 'Packaging',
                'title' => 'Habesha Spice Heritage Collection',
                'client' => 'Habesha Spice Co.',
                'year' => '2025',
                'summary' => 'Textured tin canisters with gold debossing for artisan berbere and mitmita blends.',
                'description' => '<p>A premium packaging collection celebrating legendary spice trade corridors. Custom tin geometries were paired with intricate folkloric border illustrations and color-coded spice badges.</p>',
                'tools_used' => ['Adobe Illustrator', 'Photoshop', 'InDesign'],
                'project_url' => null,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(60),
                'sort_order' => 4,
                'seo_title' => 'Habesha Spice Heritage Tins — Packaging Design by Entu',
                'seo_description' => 'Luxury tin packaging design for Habesha Spice Co. heritage collection.',
                'color' => '#B83A14',
            ],
            [
                'category' => 'Packaging',
                'title' => 'Sol Tea Atelier Blends',
                'client' => 'Sol Tea Atelier',
                'year' => '2024',
                'summary' => 'Minimalist recyclable tea canisters with custom botanical linocut illustrations.',
                'description' => '<p>Crafted for specialty tea purveyors, this packaging suite utilizes FSC-certified unbleached kraft tubes embossed with tactile linocuts depicting harvest flowers.</p>',
                'tools_used' => ['Adobe Illustrator', 'InDesign', 'Photoshop'],
                'project_url' => 'https://soltea.example.com',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(50),
                'sort_order' => 5,
                'seo_title' => 'Sol Tea Atelier Packaging Design — Entu Creative',
                'seo_description' => 'Eco-friendly tea canister packaging with botanical linocut illustrations.',
                'color' => '#C49A45',
            ],
            [
                'category' => 'Packaging',
                'title' => 'Bole Brewing Seasonal Series',
                'client' => 'Bole Craft Brewery',
                'year' => '2026',
                'summary' => 'Vibrant aluminum can wraps with dynamic retro-futuristic illustrations.',
                'description' => '<p>A playful, bold set of microbrewery cans that stand out in crowded craft coolers. Each variant features an exaggerated, colorful character inspired by urban nightlife.</p>',
                'tools_used' => ['Adobe Illustrator', 'Photoshop', 'Procreate'],
                'project_url' => null,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(30),
                'sort_order' => 6,
                'seo_title' => 'Bole Brewing Can Series — Entu Creative',
                'seo_description' => 'Craft beer label illustration and can packaging design.',
                'color' => '#E75A24',
            ],
            [
                'category' => 'Poster & Print',
                'title' => 'Addis Jazz & Heritage Fest 2025',
                'client' => 'Addis Cultural Council',
                'year' => '2025',
                'summary' => 'Official festival poster series expressing syncopated rhythms through bold typography.',
                'description' => '<p>A dynamic poster trilogy blending Ethio-jazz musical notations with layered mid-century typography. Screen printed on heavy archival cotton paper.</p>',
                'tools_used' => ['Adobe Illustrator', 'InDesign'],
                'project_url' => 'https://addisjazzfest.example.com',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(45),
                'sort_order' => 7,
                'seo_title' => 'Addis Jazz & Heritage Festival Posters — Entu Creative',
                'seo_description' => 'Limited edition screen-printed posters for Addis Jazz & Heritage Festival.',
                'color' => '#1F3A52',
            ],
            [
                'category' => 'Poster & Print',
                'title' => 'Echoes of Modernism Exhibition Catalog',
                'client' => 'National Modern Art Gallery',
                'year' => '2024',
                'summary' => 'Hardbound 180-page monograph with exposed Swiss binding and blind deboss.',
                'description' => '<p>A museum retrospective monograph showcasing mid-century modernist painters. Designed with generous white space, custom grid systems, and French fold covers.</p>',
                'tools_used' => ['Adobe InDesign', 'Photoshop'],
                'project_url' => null,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(40),
                'sort_order' => 8,
                'seo_title' => 'Echoes of Modernism Catalog Design — Entu Creative',
                'seo_description' => 'Fine art exhibition catalog with custom typography and Swiss binding.',
                'color' => '#3D3B4E',
            ],
            [
                'category' => 'Social Media',
                'title' => 'Sora Fashion Week Campaign',
                'client' => 'Sora Studios',
                'year' => '2025',
                'summary' => 'Multi-channel digital campaign kit featuring animated typography and grid layouts.',
                'description' => '<p>Comprehensive social creative suite designed for Instagram, TikTok, and digital billboards, generating over 2.4M organic impressions during runway week.</p>',
                'tools_used' => ['Figma', 'After Effects', 'Photoshop'],
                'project_url' => 'https://sorastudios.example.com',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(25),
                'sort_order' => 9,
                'seo_title' => 'Sora Fashion Week Campaign — Entu Creative',
                'seo_description' => 'Social media campaign visuals and motion templates for Sora Fashion Week.',
                'color' => '#8E2856',
            ],
            [
                'category' => 'Social Media',
                'title' => 'Aura Mindful Living Campaign',
                'client' => 'Aura Health',
                'year' => '2025',
                'summary' => 'Calming editorial carousel system and motion assets for mindful wellness app.',
                'description' => '<p>A modular 30-day social content system focused on micro-reflections and mindfulness routines, utilizing soft gradients and thoughtful spacing.</p>',
                'tools_used' => ['Figma', 'Adobe Illustrator'],
                'project_url' => null,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(20),
                'sort_order' => 10,
                'seo_title' => 'Aura Mindful Living Visuals — Entu Creative',
                'seo_description' => 'Social media kit and visual assets for Aura health app launch.',
                'color' => '#42707B',
            ],
            [
                'category' => 'Illustration',
                'title' => 'Folklore & Flora Illustrated Book',
                'client' => 'Nilotic Press',
                'year' => '2024',
                'summary' => '32 full-bleed digital paintings exploring indigenous botanical myths.',
                'description' => '<p>A celebrated illustrated collection celebrating endemic flora and traditional lore. Characterized by lush botanical textures and high-contrast atmospheric lighting.</p>',
                'tools_used' => ['Procreate', 'Adobe Illustrator', 'Photoshop'],
                'project_url' => 'https://niloticpress.example.com',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'sort_order' => 11,
                'seo_title' => 'Folklore & Flora Illustrated Book — Entu Creative',
                'seo_description' => 'Editorial illustrations for Folklore & Flora by graphic designer Entu.',
                'color' => '#1B4D3E',
            ],
            [
                'category' => 'Illustration',
                'title' => 'City in Motion Iconography',
                'client' => 'Metro Transit Agency',
                'year' => '2025',
                'summary' => 'Wayfinding iconography and transit map illustration suite for urban mobility.',
                'description' => '<p>A 120-symbol vector iconographic language designed for clarity at both 16px digital scales and 2-meter physical station wayfinding plaques.</p>',
                'tools_used' => ['Adobe Illustrator', 'Figma'],
                'project_url' => null,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'sort_order' => 12,
                'seo_title' => 'City in Motion Transit Iconography — Entu Creative',
                'seo_description' => 'Urban wayfinding and transit iconography system by Entu Creative.',
                'color' => '#1E3A8A',
            ],
        ];

        $tempDir = storage_path('app/temp_seed_images_' . uniqid('', true));
        File::ensureDirectoryExists($tempDir);

        foreach ($projectsData as $data) {
            $categoryName = $data['category'];
            $category = $categories[$categoryName] ?? null;

            $project = Project::firstOrCreate(
                ['title' => $data['title']],
                [
                    'category_id' => $category?->id,
                    'client' => $data['client'],
                    'year' => $data['year'],
                    'summary' => $data['summary'],
                    'description' => $data['description'],
                    'tools_used' => $data['tools_used'],
                    'project_url' => $data['project_url'],
                    'is_featured' => $data['is_featured'],
                    'is_published' => $data['is_published'],
                    'published_at' => $data['published_at'],
                    'sort_order' => $data['sort_order'],
                    'seo_title' => $data['seo_title'],
                    'seo_description' => $data['seo_description'],
                ]
            );

            // Add cover image if not already present
            if ($project->getMedia('cover')->isEmpty()) {
                $coverFile = $this->createPlaceholderImage(
                    $tempDir,
                    'cover_' . $project->id . '.png',
                    1200,
                    800,
                    $data['color'],
                    $project->title,
                    $categoryName
                );
                $project->addMedia($coverFile)->preservingOriginal()->toMediaCollection('cover');
            }

            // Add 2 gallery images if not already present
            if ($project->getMedia('gallery')->isEmpty()) {
                $gallery1 = $this->createPlaceholderImage(
                    $tempDir,
                    'gallery1_' . $project->id . '.png',
                    1400,
                    900,
                    $data['color'],
                    $project->title . ' — Detail 1',
                    'Gallery View'
                );
                $project->addMedia($gallery1)->preservingOriginal()->toMediaCollection('gallery');

                $gallery2 = $this->createPlaceholderImage(
                    $tempDir,
                    'gallery2_' . $project->id . '.png',
                    1400,
                    900,
                    $data['color'],
                    $project->title . ' — Detail 2',
                    'Process & Typography'
                );
                $project->addMedia($gallery2)->preservingOriginal()->toMediaCollection('gallery');
            }
        }

        File::deleteDirectory($tempDir);
    }

    private function createPlaceholderImage(
        string $dir,
        string $filename,
        int $width,
        int $height,
        string $hexColor,
        string $title,
        string $subtitle
    ): string {
        $path = $dir . '/' . $filename;
        $image = imagecreatetruecolor($width, $height);

        // Convert hex to rgb
        $hex = ltrim($hexColor, '#');
        if (strlen($hex) === 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        } else {
            $r = 45; $g = 55; $b = 72;
        }

        $bgColor = imagecolorallocate($image, $r, $g, $b);
        $accentColor = imagecolorallocate($image, min(255, (int) ($r * 1.25)), min(255, (int) ($g * 1.25)), min(255, (int) ($b * 1.25)));
        $whiteColor = imagecolorallocate($image, 255, 255, 255);
        $lightColor = imagecolorallocate($image, 220, 225, 235);

        imagefill($image, 0, 0, $bgColor);

        // Draw decorative subtle grid lines and framing
        imagefilledrectangle($image, 40, 40, $width - 40, $height - 40, $accentColor);
        imagefilledrectangle($image, 44, 44, $width - 44, $height - 44, $bgColor);

        // Draw an accent header banner
        imagefilledrectangle($image, 80, 80, $width - 80, 160, $accentColor);

        // Add built-in text
        imagestring($image, 5, 100, 105, strtoupper($subtitle), $whiteColor);
        imagestring($image, 5, 100, 190, $title, $whiteColor);
        imagestring($image, 4, 100, 230, 'Entu Creative Portfolio — Project Showcase', $lightColor);
        imagestring($image, 3, 100, $height - 100, 'Dimensions: ' . $width . 'x' . $height . ' px | WebP Optimized', $lightColor);

        imagepng($image, $path, 6);

        return $path;
    }
}
