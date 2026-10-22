<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $setting = Setting::with('media')->first() ?? new Setting();
        $bioMedia = $setting->getFirstMedia('bio_photo');
        $cvMedia = $setting->getFirstMedia('cv');
        $services = Service::active()->ordered()->get();

        $skills = [
            'Brand Strategy & Positioning',
            'Visual Identity & Logo Systems',
            'Packaging & Structural Dielines',
            'Print Production & Prepress',
            'Editorial & Publication Layout',
            'Art Direction & Storyboarding',
            'Typography & Custom Lettering',
            'Vector Illustration & Iconography',
            'Digital Campaign Creative',
            '3D Brand Mockups & Visuals',
        ];

        $tools = [
            'Adobe Illustrator',
            'Adobe Photoshop',
            'Adobe InDesign',
            'Figma',
            'Procreate',
            'Blender 3D',
            'Adobe After Effects',
        ];

        return view('pages.about', compact(
            'setting',
            'bioMedia',
            'cvMedia',
            'services',
            'skills',
            'tools'
        ));
    }
}
