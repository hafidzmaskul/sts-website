<?php

use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('product detail page passes variants prop for parent product', function () {
    $user = User::factory()->create();
    $brand = Brand::create(['name' => 'Brand Test', 'slug' => 'brand-test']);

    $parent = Product::create([
        'title' => 'Parent Product',
        'slug' => 'parent-product',
        'sku' => 'PARENT1',
        'base_price' => 100,
        'status' => 'active',
        'brand_id' => $brand->id,
        'created_by' => $user->id,
    ]);

    $variant1 = Product::create([
        'title' => 'Variant One',
        'slug' => 'variant-one',
        'sku' => 'VAR1',
        'base_price' => 110,
        'status' => 'active',
        'parent_id' => $parent->id,
        'brand_id' => $brand->id,
        'created_by' => $user->id,
    ]);

    $variant2 = Product::create([
        'title' => 'Variant Two',
        'slug' => 'variant-two',
        'sku' => 'VAR2',
        'base_price' => 120,
        'status' => 'active',
        'parent_id' => $parent->id,
        'brand_id' => $brand->id,
        'created_by' => $user->id,
    ]);

    $response = $this->get(route('products.detail', $parent->slug));

    $response->assertSuccessful();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('ProductDetail')
        ->has('variants', 3)
        ->has('variants.0.images')
        ->where('variants.0.id', $parent->id)
        ->where('variants.1.id', $variant1->id)
        ->where('variants.2.id', $variant2->id)
    );
});

test('product detail page passes variants prop for child variant', function () {
    $user = User::factory()->create();
    $brand = Brand::create(['name' => 'Brand Test', 'slug' => 'brand-test']);

    $parent = Product::create([
        'title' => 'Parent Product',
        'slug' => 'parent-product',
        'sku' => 'PARENT1',
        'base_price' => 100,
        'status' => 'active',
        'brand_id' => $brand->id,
        'created_by' => $user->id,
    ]);

    $variant1 = Product::create([
        'title' => 'Variant One',
        'slug' => 'variant-one',
        'sku' => 'VAR1',
        'base_price' => 110,
        'status' => 'active',
        'parent_id' => $parent->id,
        'brand_id' => $brand->id,
        'created_by' => $user->id,
    ]);

    $response = $this->get(route('products.detail', $variant1->slug));

    $response->assertSuccessful();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('ProductDetail')
        ->has('variants', 2)
        ->has('variants.0.images')
        ->where('variants.0.id', $parent->id)
        ->where('variants.1.id', $variant1->id)
    );
});
