<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        // Optionally create an admin user:
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            ['name' => 'admin', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');
    }
}
