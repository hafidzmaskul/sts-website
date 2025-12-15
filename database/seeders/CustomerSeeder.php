<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure customer role exists
        $role = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // Create 50 customers
        User::factory(50)->create()->each(function ($user) use ($role) {
            $user->assignRole($role);

            Customer::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        $this->command->info('seeded 50 customers successfully.');
    }
}
