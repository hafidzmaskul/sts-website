<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::firstOrCreate(
            ['key' => 'vat_percentage'],
            ['value' => '0']
        );

        Setting::firstOrCreate(
            ['key' => 'robots_txt_content'],
            ['value' => "User-agent: *\nDisallow:"]
        );

        foreach (['google_analytics_id', 'google_tag_manager_id', 'custom_script_header', 'custom_script_footer'] as $key) {
            Setting::firstOrCreate(['key' => $key], ['value' => '']);
        }
    }
}
