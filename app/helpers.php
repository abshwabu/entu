<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    /**
     * Get a setting value by key or the full Setting model, cached backed by cache and busted on save.
     */
    function setting(?string $key = null, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever('app_settings', function () {
            return Setting::first() ?? new Setting();
        });

        if ($key === null) {
            return $settings;
        }

        return data_get($settings, $key, $default);
    }
}
