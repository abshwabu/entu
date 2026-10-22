<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'client',
        'year',
        'summary',
        'description',
        'tools_used',
        'project_url',
        'is_featured',
        'is_published',
        'published_at',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'tools_used' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('home_page_data');
            \Illuminate\Support\Facades\Cache::forget('sitemap_xml_content');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('home_page_data');
            \Illuminate\Support\Facades\Cache::forget('sitemap_xml_content');
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile();

        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(600)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(1600)
            ->format('webp')
            ->nonQueued();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at');
    }

    public function getUrlAttribute(): string
    {
        if ($this->category) {
            return route('work.show', [
                'category' => $this->category->slug,
                'project' => $this->slug,
            ]);
        }

        return route('project.show', [
            'project' => $this->slug,
        ]);
    }

    public function getLightboxItemsAttribute(): array
    {
        $items = [];
        $cover = $this->getFirstMedia('cover');
        if ($cover) {
            $items[] = [
                'src' => $cover->hasGeneratedConversion('large') ? $cover->getUrl('large') : $cover->getUrl(),
                'thumb' => $cover->hasGeneratedConversion('thumb') ? $cover->getUrl('thumb') : $cover->getUrl(),
                'orig' => $cover->getUrl(),
                'alt' => $this->title . ' — Cover Image',
                'caption' => $this->title . ' (Cover Showcase)',
            ];
        }

        foreach ($this->getMedia('gallery') as $index => $media) {
            $items[] = [
                'src' => $media->hasGeneratedConversion('large') ? $media->getUrl('large') : $media->getUrl(),
                'thumb' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl(),
                'orig' => $media->getUrl(),
                'alt' => $this->title . ' — ' . ($media->name ?: 'Detail ' . ($index + 1)),
                'caption' => $media->getCustomProperty('caption') ?: ($media->name ?: $this->title . ' — Story View ' . ($index + 1)),
            ];
        }

        return $items;
    }
}
