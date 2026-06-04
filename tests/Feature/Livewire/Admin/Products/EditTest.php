<?php

namespace Tests\Feature\Livewire\Admin\Products;

use App\Livewire\Admin\Products\Edit;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    public function test_loads_existing_customer_prices()
    {
        // Setup Roles
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'customer', 'guard_name' => 'web']);
        Role::create(['name' => 'trade account', 'guard_name' => 'web']);
        Role::create(['name' => 'credit facilities account', 'guard_name' => 'web']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $brand = Brand::create(['name' => 'Test Brand', 'slug' => 'test-brand', 'is_active' => true]);

        $product = Product::create([
            'title' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'TP-001',
            'brand_id' => $brand->id,
            'status' => 'active',
            'base_price' => 100,
            'created_by' => $admin->id,
        ]);

        // Attach Customer Price
        $product->customerPrices()->attach($customer->id, ['price' => 80]);

        // Test
        Livewire::actingAs($admin)
            ->test(Edit::class, ['product' => $product])
            ->assertSet('showAdvancePricing', true)
            ->assertCount('customerPrices', 1)
            ->assertSet('customerPrices.0.user_id', $customer->id)
            ->assertSet('customerPrices.0.price', 80.00);
    }
}
