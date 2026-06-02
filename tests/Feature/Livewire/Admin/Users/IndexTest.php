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
