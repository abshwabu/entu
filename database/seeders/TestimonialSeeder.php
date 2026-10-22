<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'author_name' => 'Sarah Jenkins',
                'author_role' => 'Creative Director, Apex Studio',
                'quote' => 'Entu transformed our brand identity from generic to memorable. Her attention to detail, typography nuances, and intentional color theory delivered a visual voice our audience immediately embraced.',
                'is_published' => true,
                'sort_order' => 1,
                'color' => '#6366F1',
            ],
            [
                'author_name' => 'Dawit Wolde',
                'author_role' => 'Founder & Master Roaster, Kaffa Coffee Co.',
                'quote' => 'The packaging system Entu crafted elevated our single-origin coffees to boutique shelves internationally. She translated our heritage into contemporary retail design with stunning precision.',
                'is_published' => true,
                'sort_order' => 2,
                'color' => '#B45309',
            ],
            [
                'author_name' => 'Elena Rostova',
                'author_role' => 'Marketing VP, Sora Studios',
                'quote' => 'Working with Entu was an absolute breeze. Fast turnaround, impeccable craft, and campaign artwork that exceeded every performance metric we set during our seasonal launch.',
                'is_published' => true,
                'sort_order' => 3,
                'color' => '#BE185D',
            ],
        ];

        $tempDir = storage_path('app/temp_seed_avatars');
        File::ensureDirectoryExists($tempDir);

        foreach ($testimonials as $data) {
            $testimonial = Testimonial::firstOrCreate(
                ['author_name' => $data['author_name']],
                [
                    'author_role' => $data['author_role'],
                    'quote' => $data['quote'],
                    'is_published' => $data['is_published'],
                    'sort_order' => $data['sort_order'],
                ]
            );

            if ($testimonial->getMedia('avatar')->isEmpty()) {
                $avatarPath = $this->createAvatarImage($tempDir, 'avatar_' . $testimonial->id . '.png', 300, 300, $data['color'], $data['author_name']);
                $testimonial->addMedia($avatarPath)->preservingOriginal()->toMediaCollection('avatar');
            }
        }

        File::deleteDirectory($tempDir);
    }

    private function createAvatarImage(string $dir, string $filename, int $w, int $h, string $hexColor, string $name): string
    {
        $path = $dir . '/' . $filename;
        $image = imagecreatetruecolor($w, $h);

        $hex = ltrim($hexColor, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $bgColor = imagecolorallocate($image, $r, $g, $b);
        $whiteColor = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $bgColor);

        // Draw avatar initials
        $parts = explode(' ', $name);
        $initials = '';
        foreach ($parts as $p) {
            $initials .= strtoupper($p[0] ?? '');
        }

        imagestring($image, 5, (int) ($w / 2) - 15, (int) ($h / 2) - 10, $initials, $whiteColor);

        imagepng($image, $path, 6);

        return $path;
    }
}
