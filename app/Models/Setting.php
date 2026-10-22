<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'site_title',
        'tagline',
        'hero_heading',
        'hero_subheading',
        'about_heading',
        'about_body',
        'email',
        'phone',
        'location',
        'instagram',
        'behance',
        'dribbble',
        'linkedin',
        'facebook',
        'tiktok',
        'footer_text',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('app_settings');
            Cache::forget('home_page_data');
        });

        static::deleted(function () {
            Cache::forget('app_settings');
            Cache::forget('home_page_data');
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('bio_photo')
            ->singleFile();

        $this->addMediaCollection('cv')
            ->singleFile();
    }

    public static function current(): static
    {
        return Cache::rememberForever('app_settings', function () {
            return static::first() ?? new static();
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return setting($key, $default);
    }
}
