<?php

namespace Tests\Feature\Livewire\Admin\Customers;

use App\Livewire\Admin\Customers\Index;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_customers_are_excluded_from_list()
    {
        // Admin User
        $admin = User::factory()->create();
        // Assuming admin permission setup is needed, let's mock authorization for simplicity if possible,
        // or just actAs an admin if permissions are standard.
        // Based on code: $this->authorize('customers.view');
        // Let's rely on standard actingAs and hope for simple auth or specific permission seeder.
        // If it fails on auth, we'll fix it.

        $guestCustomer = Customer::factory()->create([
            'role_applied' => 'guest',
            'first_name' => 'Guest',
            'last_name' => 'User',
            'email' => 'guest@example.com',
        ]);

        $regularCustomer = Customer::factory()->create([
            'role_applied' => 'customer', // or 'head', 'staff'
            'first_name' => 'Regular',
            'last_name' => 'User',
            'email' => 'regular@example.com',
        ]);

        // Mock permission if needed. Since we don't know exact permission implementation details (Spatie),
        // we might run into 403. Let's try acting as a user and see.
        // Actually, let's bypass authorization check if possible or create a user with permission.
        // For now, let's just create a user. If it fails, we will see.

        // Create permission and assign to user
        \Spatie\Permission\Models\Permission::create(['name' => 'customers.view']);
        $admin->givePermissionTo('customers.view');

        Livewire::actingAs($admin)
            ->test(Index::class)
            ->assertViewHas('customers', function ($customers) use ($guestCustomer, $regularCustomer) {
                return ! $customers->contains($guestCustomer) && $customers->contains($regularCustomer);
            });
    }
}
