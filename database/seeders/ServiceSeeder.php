<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Brand Identity & Visual Systems',
                'description' => 'Comprehensive logo design, typography hierarchies, custom color theory palettes, and brand guidelines books built to ensure consistency across digital and print touchpoints.',
                'icon' => 'heroicon-o-swatch',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Packaging & Sensory Print Design',
                'description' => 'Tactile, shelf-commanding structural packaging, label finishes, embossing, and thoughtful unboxing experiences for consumer goods, craft beverages, and boutique collections.',
                'icon' => 'heroicon-o-cube',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Art Direction & Digital Campaigns',
                'description' => 'Holistic creative direction, custom vector illustrations, and multi-channel campaign collateral designed to elevate storytelling and capture audience imagination.',
                'icon' => 'heroicon-o-paint-brush',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($services as $data) {
            Service::firstOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
    }
}
