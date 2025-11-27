<?php

namespace App\Services;

use App\Models\Setting;

class LandingPageService
{
    /**
     * Get all landing page settings.
     *
     * @return array
     */
    public static function getSettings(): array
    {
        $keys = [
            'footer_description',
            'address_label_1',
            'address_label_2',
            'contact_1',
            'contact_2',
            'time_operational_label_1',
            'time_operational_label_2',
            'landing_page_email',
        ];

        $settings = [];

        foreach ($keys as $key) {
            $settings[$key] = Setting::where('key', $key)->first()?->value;
        }

        return $settings;
    }
}
