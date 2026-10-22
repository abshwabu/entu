<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Render the dynamic XML sitemap
     */
    public function index(SitemapService $sitemapService): Response
    {
        $xml = $sitemapService->getCachedXml();

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
