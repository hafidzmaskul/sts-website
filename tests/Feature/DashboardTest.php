<?php

use App\Models\User;
use App\Models\Customer;
use App\Models\Transaction;
use Spatie\Permission\Models\Role;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
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