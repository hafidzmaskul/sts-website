<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use App\Models\Customer;
use App\Models\Brand;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Laravel\Sanctum\Sanctum;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Seed roles if necessary or just create the one we need
        if (!Role::where('name', 'customer')->exists()) {
            Role::create(['name' => 'customer']);
        }

        // Setup Settings
        Setting::create(['key' => 'shipping_method_1_name', 'value' => 'JNE']);
        Setting::create(['key' => 'shipping_method_1_price', 'value' => '10000']);
        Setting::create(['key' => 'payment_method_1_name', 'value' => 'Bank Transfer']);
        Setting::create(['key' => 'transaction_tax', 'value' => '10']); // 10% tax
    }

    public function test_can_create_transaction_directly_from_product_id()
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $customer = Customer::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '08123456789',
        ]);

        $brand = Brand::create(['name' => 'Brand A', 'slug' => 'brand-a', 'created_by' => $user->id]);
        $product = Product::create([
            'title' => 'Direct Buy Product',
            'slug' => 'direct-buy-product',
            'sku' => 'SKU-DIRECT',
            'brand_id' => $brand->id,
            'status' => 'active',
            'base_price' => 50000,
            'created_by' => $user->id,
        ]);

        $quantity = 2;
        $shippingPrice = 10000;
        $subtotal = 50000 * $quantity; // 100,000
        $taxAmount = ($subtotal + $shippingPrice) * 0.10; // (100,000 + 10,000) * 10% = 11,000
        $totalAmount = $subtotal + $shippingPrice + $taxAmount; // 100,000 + 10,000 + 11,000 = 121,000

        $data = [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'contact_email' => 'buyer@example.com',
            'shipping_first_name' => 'Buyer',
            'shipping_last_name' => 'One',
            'shipping_address' => 'Street 123',
            'shipping_city' => 'Jakarta',
            'shipping_postal_code' => '12345',
            'shipping_country' => 'Indonesia',
            'shipping_phone_number' => '08123456789',
            'shipping_method' => 'JNE',
            'shipping_payment_method' => 'Bank Transfer',
            'total_amount' => $totalAmount,
        ];

        // Create token for user (User model must use HasApiTokens)
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/transactions', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('transactions', [
            'customer_id' => $customer->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        $transactionId = $response->json('data.id');
        $this->assertDatabaseHas('transaction_items', [
            'transaction_id' => $transactionId,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => 50000,
            'total_price' => 100000,
        ]);
    }
}
