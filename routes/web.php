<?php

use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProjectController;
use App\Http\Controllers\Public\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Portfolio Work & Detail routes
Route::get('/work', [ProjectController::class, 'index'])->name('work.index');
Route::get('/work/{category:slug}', [ProjectController::class, 'index'])->name('work.category');
Route::get('/work/{category}/{project:slug}', [ProjectController::class, 'show'])->name('work.show');
Route::get('/project/{project:slug}', [ProjectController::class, 'showDirect'])->name('project.show');

// Public Pages: About, Services, Contact
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

// SEO: Dynamic Sitemap and Robots.txt
Route::get('/sitemap.xml', [\App\Http\Controllers\Public\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    $sitemapUrl = url('/sitemap.xml');
    $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /admin/\nDisallow: /livewire/\n\nSitemap: {$sitemapUrl}\n";
    return response($content, 200, ['Content-Type' => 'text/plain']);
});



