<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->brand = Brand::create(['name' => 'Brand X', 'slug' => 'brand-x', 'created_by' => $this->user->id]);

    // Parent Product
    $this->parentProduct = Product::create([
        'title' => 'Parent Product Title',
        'slug' => 'smart-cctv-camera-pro', // Matches the legacy test slug
        'sku' => 'PARENT-SKU',
        'brand_id' => $this->brand->id,
        'base_price' => 100.00,
        'created_by' => $this->user->id,
        'key_feature' => 'Parent Key Feature',
        'product_overview' => 'Parent Product Overview',
        'main_feature' => 'Parent Main Feature',
        'information' => 'Parent Information',
        'specification' => 'Parent Specification',
    ]);

    // Variant Product
    $this->variantProduct = Product::create([
        'parent_id' => $this->parentProduct->id,
        'title' => 'Variant Product Title',
        'slug' => 'variant-cctv-camera',
        'sku' => 'VARIANT-SKU',
        'brand_id' => $this->brand->id,
        'base_price' => 120.00,
        'created_by' => $this->user->id,
        // Description/features left blank/null to test frontend fallback logic
    ]);
});

it('renders the product detail page for parent product', function () {
    $response = $this->get(route('products.detail', $this->parentProduct->slug));

    $response->assertSuccessful();

    $response->assertInertia(fn ($page) => $page
        ->component('ProductDetail')
        ->where('product.id', $this->parentProduct->id)
        ->has('variants')
    );
});

it('renders the product detail page for variant product', function () {
    $response = $this->get(route('products.detail', $this->variantProduct->slug));

    $response->assertSuccessful();

    $response->assertInertia(fn ($page) => $page
        ->component('ProductDetail')
        ->where('product.id', $this->variantProduct->id)
        ->has('variants')
    );
});
