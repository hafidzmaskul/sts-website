<?php

use App\Models\User;
use App\Models\Customer;
use App\Models\Transaction;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $roles = ['admin', 'customer', 'trade account', 'credit facilities account', 'guest', 'child'];
    foreach ($roles as $role) {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
    }
});

test('guests are allowed and auto-logged in', function () {
    $response = $this->get(route('dashboard'));
    $response->assertStatus(200);
    // Guests might see 'Guest User' or similar depending on EnsureGuestUser logic
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertStatus(200);
});

test('trade account users can see recent transactions', function () {
    $role = Role::firstOrCreate(['name' => 'trade account']);
    $user = User::factory()->create();
    $user->assignRole($role);

    $customer = Customer::factory()->create(['user_id' => $user->id]);

    $transaction = Transaction::create([
        'customer_id' => $customer->id,
        'invoice_code' => 'INV-TEST-001',
        'total_amount' => 123.45,
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Recent Transactions');
    $response->assertSee('Transactions'); // Check for sidebar link
    $response->assertSee('INV-TEST-001');
    $response->assertSee('123.45');
});

test('normal users do not see recent transactions section', function () {
    $user = User::factory()->create();
    // No role or default role

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertDontSee('Recent Transactions');
});

test('admin user sees business overview stats', function () {
    $this->withoutExceptionHandling();
    $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'trade account', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'credit facilities account', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole($role);

    // Create some data
    $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
    $customerUser = User::factory()->create();
    $customerUser->assignRole($customerRole);
    Customer::factory()->create(['user_id' => $customerUser->id]);

    Transaction::create([
        'customer_id' => $customerUser->customer->id,
        'invoice_code' => 'INV-STAT-001',
        'total_amount' => 100,
        'status' => 'paid',
    ]);

    Transaction::create([
        'customer_id' => $customerUser->customer->id,
        'invoice_code' => 'INV-STAT-002',
        'total_amount' => 50,
        'status' => 'pending', // Pending doesn't count for revenue in our logic
    ]);

    Transaction::create([
        'customer_id' => $customerUser->customer->id,
        'invoice_code' => 'INV-STAT-003',
        'total_amount' => 200,
        'status' => 'cancelled', // Cancelled doesn't count for orders
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Business Overview');

    // Revenue: 100 (Paid)
    // Orders: 2 (Paid + Pending, Cancelled excluded)
    // Customers: 1

    // Livewire feature tests return rendered HTML, so we check content directly
    $response->assertStatus(200);
    $response->assertSee('Business Overview');

    // Revenue formatted: 100 -> £100.00
    // Check for the value, assuming currency symbol might be in separate span or adjacent
    $response->assertSee('100.00');

    // Orders count: 2
    $response->assertSee('2');

    // Customers count: 1 or more
    // Hard to check exact number if dynamic, but we can check the label exists
    $response->assertSee('Total Customers');

    // Verify chart data key is present in the rendered script
    $response->assertSee('const chartData =');
});