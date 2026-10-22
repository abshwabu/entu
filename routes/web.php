<?php

use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredProjects = Project::with('category')
        ->published()
        ->featured()
        ->ordered()
        ->take(6)
        ->get();

    if ($featuredProjects->isEmpty()) {
        $featuredProjects = Project::with('category')
            ->published()
            ->ordered()
            ->take(6)
            ->get();
    }

    $services = Service::active()->ordered()->get();
    $testimonials = Testimonial::published()->ordered()->take(3)->get();
    $setting = Setting::current();

    return view('welcome', compact('featuredProjects', 'services', 'testimonials', 'setting'));
});
