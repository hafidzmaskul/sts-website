<?php

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductAttachment;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('detail product api includes attachments with is_public flag', function () {
    $user = User::factory()->create();
    $brand = Brand::create(['name' => 'Brand Test', 'slug' => 'brand-test']);

    $product = Product::create([
        'title' => 'Product With Attachment',
        'slug' => 'product-with-attachment',
        'sku' => 'ATT1',
        'base_price' => 100,
        'status' => 'active',
        'brand_id' => $brand->id,
        'created_by' => $user->id,
    ]);

    $attachment = ProductAttachment::create([
        'product_id' => $product->id,
        'name' => 'Datasheet',
        'file_path' => 'attachments/datasheet.pdf',
        'is_public' => true,
    ]);

    $response = $this->getJson('/api/products/'.$product->slug);

    $response->assertSuccessful();

    $data = $response->json('data');
    expect($data['attachments'])->toBeArray();
    expect($data['attachments'])->toHaveCount(1);
    expect($data['attachments'][0]['id'])->toBe($attachment->id);
    expect($data['attachments'][0]['name'])->toBe('Datasheet');
    expect($data['attachments'][0]['file_path'])->toBe('attachments/datasheet.pdf');
    expect($data['attachments'][0]['is_public'])->toBeTrue();
});

test('list product api includes variants and their images', function () {
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

    $variant = Product::create([
        'title' => 'Variant One',
        'slug' => 'variant-one',
        'sku' => 'VAR1',
        'base_price' => 110,
        'status' => 'active',
        'parent_id' => $parent->id,
        'brand_id' => $brand->id,
        'created_by' => $user->id,
    ]);

    ProductImage::create([
        'product_id' => $variant->id,
        'image_path' => 'products/variant-one.jpg',
        'sequence' => 1,
    ]);

    $response = $this->getJson('/api/products');

    $response->assertSuccessful();

    // The index API paginates, so data is in data.data
    $response->assertJsonFragment([
        'id' => $parent->id,
        'title' => 'Parent Product',
    ]);

    // Check that variants are present in the response data
    $products = $response->json('data.data');
    $parentInResponse = collect($products)->firstWhere('id', $parent->id);

    expect($parentInResponse)->not->toBeNull();
    expect($parentInResponse['variants'])->toBeArray();
    expect($parentInResponse['variants'])->toHaveCount(1);
    expect($parentInResponse['variants'][0]['id'])->toBe($variant->id);
    expect($parentInResponse['variants'][0]['images'])->toBeArray();
    expect($parentInResponse['variants'][0]['images'][0]['image_path'])->toBe('products/variant-one.jpg');
});

test('detail product api includes unified variants and images for parent product', function () {
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

    $variant = Product::create([
        'title' => 'Variant One',
        'slug' => 'variant-one',
        'sku' => 'VAR1',
        'base_price' => 110,
        'status' => 'active',
        'parent_id' => $parent->id,
        'brand_id' => $brand->id,
        'created_by' => $user->id,
    ]);

    ProductImage::create([
        'product_id' => $variant->id,
        'image_path' => 'products/variant-one.jpg',
        'sequence' => 1,
    ]);

    $response = $this->getJson('/api/products/'.$parent->slug);

    $response->assertSuccessful();

    $data = $response->json('data');
    expect($data['id'])->toBe($parent->id);
    expect($data['variants'])->toBeArray();
    expect($data['variants'])->toHaveCount(2); // Parent + Variant One
    expect($data['variants'][0]['id'])->toBe($parent->id);
    expect($data['variants'][1]['id'])->toBe($variant->id);
    expect($data['variants'][1]['images'][0]['image_path'])->toBe('products/variant-one.jpg');
});

test('detail product api includes unified variants and images for child variant', function () {
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

    $variant = Product::create([
        'title' => 'Variant One',
        'slug' => 'variant-one',
        'sku' => 'VAR1',
        'base_price' => 110,
        'status' => 'active',
        'parent_id' => $parent->id,
        'brand_id' => $brand->id,
        'created_by' => $user->id,
    ]);

    ProductImage::create([
        'product_id' => $variant->id,
        'image_path' => 'products/variant-one.jpg',
        'sequence' => 1,
    ]);

    $response = $this->getJson('/api/products/'.$variant->slug);

    $response->assertSuccessful();

    $data = $response->json('data');
    expect($data['id'])->toBe($variant->id);
    expect($data['variants'])->toBeArray();
    expect($data['variants'])->toHaveCount(2); // Parent + Variant One
    expect($data['variants'][0]['id'])->toBe($parent->id);
    expect($data['variants'][1]['id'])->toBe($variant->id);
    expect($data['variants'][1]['images'][0]['image_path'])->toBe('products/variant-one.jpg');
});
