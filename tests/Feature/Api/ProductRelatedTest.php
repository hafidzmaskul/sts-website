<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRelatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_product_with_related_products()
    {
        // CREATE DATA
        $user = \App\Models\User::factory()->create();

        $brand = Brand::create([
            'name' => 'Brand A',
            'slug' => 'brand-a',
            'created_by' => $user->id
        ]);

        $category = ProductCategory::create([
            'name' => 'Category A',
            'slug' => 'category-a',
            'created_by' => $user->id
        ]);
        $otherCategory = ProductCategory::create([
            'name' => 'Category B',
            'slug' => 'category-b',
            'created_by' => $user->id
        ]);

        // Main product
        $product = Product::create([
            'title' => 'Main Product',
            'slug' => 'main-product',
            'sku' => 'SKU-MAIN',
            'brand_id' => $brand->id,
            'status' => 'active',
            'base_price' => 1000,
            'created_by' => $user->id,
        ]);
        $product->categories()->attach($category->id);

        // Related Product 1 (Same Category)
        $related1 = Product::create([
            'title' => 'Related 1',
            'slug' => 'related-1',
            'sku' => 'SKU-REL-1',
            'brand_id' => $brand->id,
            'status' => 'active',
            'base_price' => 1000,
            'created_by' => $user->id,
        ]);
        $related1->categories()->attach($category->id);

        // Related Product 2 (Same Category)
        $related2 = Product::create([
            'title' => 'Related 2',
            'slug' => 'related-2',
            'sku' => 'SKU-REL-2',
            'brand_id' => $brand->id,
            'status' => 'active',
            'base_price' => 1000,
            'created_by' => $user->id,
        ]);
        $related2->categories()->attach($category->id);

        // Unrelated Product (Different Category)
        $unrelated = Product::create([
            'title' => 'Unrelated',
            'slug' => 'unrelated',
            'sku' => 'SKU-UNREL',
            'brand_id' => $brand->id,
            'status' => 'active',
            'base_price' => 1000,
            'created_by' => $user->id,
        ]);
        $unrelated->categories()->attach($otherCategory->id);

        // Inactive Related Product (Same Category but inactive)
        $inactiveRelated = Product::create([
            'title' => 'Inactive Related',
            'slug' => 'inactive-related',
            'sku' => 'SKU-INACTIVE',
            'brand_id' => $brand->id,
            'status' => 'inactive',
            'base_price' => 1000,
            'created_by' => $user->id,
        ]);
        $inactiveRelated->categories()->attach($category->id);


        // ACT
        $response = $this->getJson('/api/products/' . $product->slug);

        // ASSERT
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'title',
                    'related_products' => [
                        '*' => ['id', 'title', 'slug', 'sku']
                    ]
                ]
            ]);

        $relatedProducts = $response->json('data.related_products');

        // Check count (should be 2: related1 and related2)
        $this->assertCount(2, $relatedProducts);

        // Check contents
        $relatedIds = collect($relatedProducts)->pluck('id')->toArray();
        $this->assertContains($related1->id, $relatedIds);
        $this->assertContains($related2->id, $relatedIds);
        $this->assertNotContains($product->id, $relatedIds); // Should not contain self
        $this->assertNotContains($unrelated->id, $relatedIds);
        $this->assertNotContains($inactiveRelated->id, $relatedIds);
    }
}
