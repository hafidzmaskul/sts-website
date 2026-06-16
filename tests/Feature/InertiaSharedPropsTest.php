<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\CreditLimit;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    if (! Role::where('name', 'credit facilities account')->exists()) {
        Role::create(['name' => 'credit facilities account']);
    }
});

it('shares null credit limit balance for guest or unauthorized users', function () {
    $response = $this->get(route('home'));

    $response->assertSuccessful()->assertInertia(
        fn (Assert $page) => $page
            ->where('credit_limit_balance', null)
    );
});

it('shares correct credit limit balance for credit facilities account users', function () {
    $company = Company::create(['name' => 'Test Company']);
    $user = User::factory()->create();
    $user->assignRole('credit facilities account');

    $customer = Customer::create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => $user->email,
    ]);

    CreditLimit::create([
        'company_id' => $company->id,
        'customer_id' => $customer->id,
        'user_id' => $user->id,
        'description' => 'Initial Limit',
        'credit' => 1250,
        'debit' => 0,
        'balance' => 1250,
    ]);

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSuccessful()->assertInertia(
        fn (Assert $page) => $page
            ->where('credit_limit_balance', 1250)
    );
});
