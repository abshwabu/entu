<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapService
{
    /**
     * Generate the sitemap XML string and write it to public/sitemap.xml
     */
    public function generate(): string
    {
        $sitemap = Sitemap::create();

        // 1. Home Page
        $sitemap->add(
            Url::create(url('/'))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        // 2. Portfolio Index
        $sitemap->add(
            Url::create(route('work.index'))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );

        // 3. Category Filter Pages
        $categories = Category::all();
        foreach ($categories as $category) {
            $sitemap->add(
                Url::create(route('work.category', $category->slug))
                    ->setLastModificationDate($category->updated_at ?? Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        }

        // 4. Published Projects
        $projects = Project::published()->with('category')->get();
        foreach ($projects as $project) {
            $sitemap->add(
                Url::create($project->url)
                    ->setLastModificationDate($project->updated_at ?? Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        }

        // 5. About, Services & Contact
        $sitemap->add(
            Url::create(route('about'))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );

        $sitemap->add(
            Url::create(route('services'))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );

        $sitemap->add(
            Url::create(route('contact'))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );

        $xml = $sitemap->render();

        try {
            File::put(public_path('sitemap.xml'), $xml);
        } catch (\Throwable $e) {
            // Safe fallback if filesystem permission blocks writing
        }

        return $xml;
    }

    /**
     * Retrieve the cached sitemap XML, generating fresh if missing or invalidated.
     */
    public function getCachedXml(): string
    {
        return Cache::remember('sitemap_xml_content', 86400, function () {
            return $this->generate();
        });
    }
}
