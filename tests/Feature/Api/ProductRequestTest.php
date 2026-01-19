<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_product_request_publicly()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $adminEmail = 'admin@example.com';
        \App\Models\Setting::create(['key' => 'email_notification_admin', 'value' => $adminEmail]);

        $user = User::factory()->create();
        $brand = \App\Models\Brand::create(['name' => 'Brand A', 'slug' => 'brand-a', 'created_by' => $user->id]);
        $product = Product::create([
            'title' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'SKU-TEST',
            'brand_id' => $brand->id,
            'status' => 'active',
            'base_price' => 1000,
            'created_by' => $user->id,
        ]);

        $data = [
            'name' => 'Public User',
            'email' => 'public@example.com',
            'phone' => '08123456789',
            'message' => 'I am interested in this product',
            'product_id' => $product->id,
        ];

        // No auth headers needed
        $response = $this->postJson('/api/product-requests', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Product request submitted successfully.',
                'data' => [
                    'name' => 'Public User',
                    'email' => 'public@example.com',
                    'product_id' => $product->id,
                ],
            ]);

        $this->assertDatabaseHas('product_requests', $data);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\NewProductRequestNotification::class, function ($mail) use ($adminEmail) {
            return $mail->hasTo($adminEmail);
        });
    }

    public function test_validation_errors()
    {
        $response = $this->postJson('/api/product-requests', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'phone', 'product_id']);
    }

    public function test_validation_error_for_non_existent_product_id()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'product_id' => 999999, // Non-existent ID
        ];

        $response = $this->postJson('/api/product-requests', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id']);
    }

}
