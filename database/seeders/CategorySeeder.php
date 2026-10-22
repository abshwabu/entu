<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Branding',
                'description' => 'Comprehensive brand identity systems, typography guidelines, color palettes, and logo suites.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Packaging',
                'description' => 'Sensory packaging design, structural box mockups, label finishes, and retail shelf presence.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Poster & Print',
                'description' => 'Expressive editorial spreads, limited edition screen prints, cultural posters, and event collateral.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Social Media',
                'description' => 'Cohesive visual campaigns, digital storytelling templates, motion-ready assets, and social content.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Illustration',
                'description' => 'Bespoke vector illustrations, narrative brand characters, custom iconography, and decorative murals.',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
