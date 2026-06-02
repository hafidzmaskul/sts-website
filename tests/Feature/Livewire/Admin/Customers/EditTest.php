<?php

use App\Livewire\Admin\Customers\Edit;
use App\Models\PricingFormula;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    // Create required permissions
    Permission::firstOrCreate(['name' => 'customers.edit']);
});

test('customer edit component renders successfully and displays pricing formulas', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo('customers.edit');

    $customerToEdit = User::factory()->create();

    PricingFormula::create([
        'user_id' => $admin->id,
        'label' => 'Test Customer Margin Formula',
        'margin' => 12.00,
        'markup' => null,
        'discount' => null,
    ]);

    Livewire::actingAs($admin)
        ->test(Edit::class, ['user' => $customerToEdit])
        ->assertStatus(200)
        ->assertSee('Edit Customer')
        ->assertSee('Test Customer Margin Formula')
        ->assertSee('Margin: 12.00%');
});
