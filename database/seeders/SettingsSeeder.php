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
    }
}
