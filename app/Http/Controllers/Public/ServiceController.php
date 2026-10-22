<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::active()->ordered()->get();

        $processSteps = [
            [
                'step' => '01',
                'title' => 'Discovery & Strategic Foundation',
                'description' => 'We begin with an in-depth creative briefing, contextual research, competitor audit, and brand positioning definition to align on clear creative objectives.',
            ],
            [
                'step' => '02',
                'title' => 'Concept Exploration & Art Direction',
                'description' => 'Translating insights into distinct creative trajectories, exploring typography, color palettes, bespoke marks, and tactile material explorations.',
            ],
            [
                'step' => '03',
                'title' => 'Refinement & System Architecture',
                'description' => 'Developing the chosen direction into an exhaustive visual design language, testing real-world applications across physical packaging, digital media, and environmental prints.',
            ],
            [
                'step' => '04',
                'title' => 'Production, Guidelines & Handover',
                'description' => 'Delivery of press-ready vector master files, digital assets, print finish specifications, and an intuitive Brand Guidelines Book for seamless future execution.',
            ],
        ];

        return view('pages.services', compact('services', 'processSteps'));
    }
}
