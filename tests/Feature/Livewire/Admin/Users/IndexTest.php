<?php

use App\Livewire\Admin\Users\Index;
use App\Models\PricingFormula;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    // Create required permissions
    Permission::firstOrCreate(['name' => 'users.view']);
    Permission::firstOrCreate(['name' => 'users.create']);
    Permission::firstOrCreate(['name' => 'users.edit']);
});

test('user index component renders successfully', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo('users.view');

    PricingFormula::create([
        'user_id' => $admin->id,
        'label' => 'Test Margin Formula',
        'margin' => 15.00,
        'markup' => null,
        'discount' => null,
    ]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->assertStatus(200)
        ->assertSee('Users');
});

test('filters users by pricing formula', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo('users.view');

    $formula = PricingFormula::create([
        'user_id' => $admin->id,
        'label' => 'Gold Tier',
        'margin' => 20.00,
        'markup' => null,
        'discount' => null,
    ]);

    $withFormula = User::factory()->create(['name' => 'Alice With', 'pricing_formula_id' => $formula->id]);
    $withoutFormula = User::factory()->create(['name' => 'Bob Without', 'pricing_formula_id' => null]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->set('pricing_formula_filter', $formula->id)
        ->assertSee('Alice With')
        ->assertDontSee('Bob Without');
});

test('filters users by verified status', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo('users.view');

    $verified = User::factory()->create(['name' => 'Vera Verified', 'email_verified_at' => now()]);
    $unverified = User::factory()->create(['name' => 'Uri Unverified', 'email_verified_at' => null]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->set('verified_filter', 'verified')
        ->assertSee('Vera Verified')
        ->assertDontSee('Uri Unverified')
        ->set('verified_filter', 'unverified')
        ->assertSee('Uri Unverified')
        ->assertDontSee('Vera Verified');
});

test('filters users by registration date range', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo('users.view');

    $old = User::factory()->create(['name' => 'Olivia Old', 'created_at' => '2020-01-01']);
    $recent = User::factory()->create(['name' => 'Ralph Recent', 'created_at' => '2026-06-01']);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->set('date_from', '2026-01-01')
        ->assertSee('Ralph Recent')
        ->assertDontSee('Olivia Old')
        ->set('date_from', null)
        ->set('date_to', '2021-01-01')
        ->assertSee('Olivia Old')
        ->assertDontSee('Ralph Recent');
});

test('reset filters clears all filter state', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo('users.view');

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->set('search', 'foo')
        ->set('verified_filter', 'verified')
        ->set('date_from', '2026-01-01')
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('verified_filter', null)
        ->assertSet('date_from', null);
});

test('clicking edit sets up form and renders pricing formulas correctly', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(['users.view', 'users.edit']);

    $userToEdit = User::factory()->create();

    $formula = PricingFormula::create([
        'user_id' => $admin->id,
        'label' => 'Test Discount Formula',
        'margin' => null,
        'markup' => null,
        'discount' => 10.00,
    ]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('edit', $userToEdit->id)
        ->assertSet('editingId', $userToEdit->id)
        ->assertSet('showForm', true)
        ->assertSee('Edit User')
        ->assertSee('Test Discount Formula')
        ->assertSee('Discount: 10.00%');
});
