<?php

use App\Livewire\Admin\RmaRequests\Index;
use App\Models\RmaRequest;
use App\Models\User;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it renders rma requests page', function () {
    $role = \Spatie\Permission\Models\Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    // Actually the route middleware checks for 'role:admin'.
    // Let's just test the component directly first.

    RmaRequest::create([
        'order_number' => 'ORD-001',
        'product_name' => 'Prod A',
        'return_reason' => 'Reason A',
        'return_type' => 'refund',
        'status' => 'pending',
    ]);

    Livewire::test(Index::class)
        ->assertSee('ORD-001')
        ->assertSee('Prod A');
});

test('it can filter rma requests', function () {
    RmaRequest::create([
        'order_number' => 'ORD-001',
        'product_name' => 'Prod A',
        'return_reason' => 'Reason A',
        'return_type' => 'refund',
        'status' => 'pending',
    ]);

    RmaRequest::create([
        'order_number' => 'ORD-002',
        'product_name' => 'Prod B',
        'return_reason' => 'Reason B',
        'return_type' => 'exchange',
        'status' => 'approved',
    ]);

    Livewire::test(Index::class)
        ->set('search', 'ORD-001')
        ->assertSee('ORD-001')
        ->assertDontSee('ORD-002')
        ->set('search', '')
        ->set('status', 'approved')
        ->assertSee('ORD-002')
        ->assertDontSee('ORD-001')
        ->set('status', '')
        ->set('returnType', 'refund')
        ->assertSee('ORD-001')
        ->assertDontSee('ORD-002');
});
