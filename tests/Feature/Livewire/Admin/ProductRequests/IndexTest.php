<?php

namespace Tests\Feature\Livewire\Admin\ProductRequests;

use App\Livewire\Admin\ProductRequests\Index;
use App\Models\Product;
use App\Models\ProductRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_index_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertStatus(200);
    }

    public function test_can_search_product_requests()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $brand = \App\Models\Brand::create(['name' => 'B', 'slug' => 'b', 'created_by' => $user->id]);
        $product = Product::create(['title' => 'P1', 'slug' => 'p1', 'sku' => 's1', 'brand_id' => $brand->id, 'created_by' => $user->id]);

        ProductRequest::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123',
            'product_id' => $product->id,
        ]);

        ProductRequest::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '456',
            'product_id' => $product->id,
        ]);

        Livewire::test(Index::class)
            ->set('search', 'John')
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith');
    }

    public function test_can_filter_by_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $brand = \App\Models\Brand::create(['name' => 'B', 'slug' => 'b', 'created_by' => $user->id]);
        $product1 = Product::create(['title' => 'P1', 'slug' => 'p1', 'sku' => 's1', 'brand_id' => $brand->id, 'created_by' => $user->id]);
        $product2 = Product::create(['title' => 'P2', 'slug' => 'p2', 'sku' => 's2', 'brand_id' => $brand->id, 'created_by' => $user->id]);

        ProductRequest::create(['name' => 'R1', 'email' => 'e1@e.com', 'phone' => '1', 'product_id' => $product1->id]);
        ProductRequest::create(['name' => 'R2', 'email' => 'e2@e.com', 'phone' => '2', 'product_id' => $product2->id]);

        Livewire::test(Index::class)
            ->set('productId', $product1->id)
            ->assertSee('R1')
            ->assertDontSee('R2');
    }

    public function test_can_filter_by_date()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $brand = \App\Models\Brand::create(['name' => 'B', 'slug' => 'b', 'created_by' => $user->id]);
        $product = Product::create(['title' => 'P1', 'slug' => 'p1', 'sku' => 's1', 'brand_id' => $brand->id, 'created_by' => $user->id]);

        $old = ProductRequest::create(['name' => 'Old', 'email' => 'o@e.com', 'phone' => '1', 'product_id' => $product->id]);
        $old->created_at = now()->subDays(5);
        $old->save();

        $new = ProductRequest::create(['name' => 'New', 'email' => 'n@e.com', 'phone' => '2', 'product_id' => $product->id]);
        // Default created_at is now

        Livewire::test(Index::class)
            ->set('dateStart', now()->subDay()->format('Y-m-d'))
            ->assertSee('New')
            ->assertDontSee('Old');
    }
}
