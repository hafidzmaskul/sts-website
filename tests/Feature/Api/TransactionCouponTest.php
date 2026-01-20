<?php

namespace Tests\Feature\Api;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class TransactionCouponTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'trade account', 'guard_name' => 'web']);
        Role::create(['name' => 'guest', 'guard_name' => 'web']);

        $this->user = User::factory()->create();
        $this->user->customer()->create(); // Ensure customer exists

        $this->product = Product::create([
            'title' => 'Test Product',
            'price' => 100, // old field, probably not used if base_price exists
            'base_price' => 100.00,
            'slug' => 'test-product',
            'created_by' => $this->user->id,
        ]);

        // Mock Settings
        Setting::create(['key' => 'transaction_tax', 'value' => '10']); // 10% Tax
    }

    protected function sanctumAuth($user)
    {
        \Laravel\Sanctum\Sanctum::actingAs($user);
        return ['Authorization' => 'Bearer test-token'];
    }

    public function test_can_create_transaction_with_fixed_discount_coupon()
    {
        $coupon = Coupon::create([
            'name' => 'Fixed Discount',
            'code' => 'FIXED10',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
        ]);

        // Cart setup
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2, // Subtotal 200
            'price' => 100, // Snapshot price
        ]);

        // Calculation:
        // Subtotal: 200
        // Discount: 10
        // Taxable: 190
        // Tax (10%): 19
        // Total: 209

        $payload = [
            'total_amount' => 209.00,
            'contact_email' => 'test@example.com',
            'coupon_id' => $coupon->id,
        ];

        $response = $this->postJson('/api/transactions', $payload, $this->sanctumAuth($this->user));

        $response->assertStatus(201);
        $this->assertDatabaseHas('transactions', [
            'customer_id' => $this->user->customer->id,
            'coupon_id' => $coupon->id,
            'discount_amount' => 10.00,
            'total_amount' => 209.00,
        ]);

        $this->assertEquals(1, $coupon->fresh()->used_count);
    }

    public function test_can_create_transaction_with_percentage_discount_coupon()
    {
        $coupon = Coupon::create([
            'name' => 'Percent Discount',
            'code' => 'PERCENT50',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'percentage',
            'discount_value' => 50, // 50%
        ]);

        // Direct Purchase setup
        // Product 100, Qty 1

        // Calculation:
        // Subtotal: 100
        // Discount: 50
        // Taxable: 50
        // Tax: 5
        // Total: 55

        $payload = [
            'product_id' => $this->product->id,
            'quantity' => 1,
            'total_amount' => 55.00,
            'contact_email' => 'test@example.com',
            'coupon_id' => $coupon->id,
        ];

        $response = $this->postJson('/api/transactions', $payload, $this->sanctumAuth($this->user));

        $response->assertStatus(201);
        $this->assertDatabaseHas('transactions', [
            'coupon_id' => $coupon->id,
            'discount_amount' => 50.00,
            'total_amount' => 55.00,
        ]);
    }

    public function test_fails_if_total_amount_mismatches_with_coupon()
    {
        $coupon = Coupon::create([
            'name' => 'Fixed Discount',
            'code' => 'FIXED10',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
        ]);

        // Direct Purchase
        // Subtotal: 100
        // Discount: 10
        // Tax: 9
        // Total Should Be: 99

        $payload = [
            'product_id' => $this->product->id,
            'quantity' => 1,
            'total_amount' => 100.00, // Wrong, ignoring discount
            'contact_email' => 'test@example.com',
            'coupon_id' => $coupon->id,
        ];

        $response = $this->postJson('/api/transactions', $payload, $this->sanctumAuth($this->user));

        $response->assertStatus(400)
            ->assertJsonFragment(['success' => false])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'items',
                    'shipping',
                    'coupon',
                    'tax',
                    'totals'
                ]
            ]);
    }

    public function test_fails_if_coupon_is_invalid()
    {
        $coupon = Coupon::create([
            'name' => 'Unpublished',
            'code' => 'HIDDEN',
            'type' => 'redeem',
            'status' => 'unpublished',
            'discount_type' => 'fixed',
            'discount_value' => 10,
        ]);

        $payload = [
            'product_id' => $this->product->id,
            'quantity' => 1,
            'total_amount' => 100.00,
            'contact_email' => 'test@example.com',
            'coupon_id' => $coupon->id,
        ];

        $response = $this->postJson('/api/transactions', $payload, $this->sanctumAuth($this->user));

        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Coupon is not valid or you are not eligible.']);
    }
}
