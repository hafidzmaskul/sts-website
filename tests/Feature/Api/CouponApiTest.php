<?php

namespace Tests\Feature\Api;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class CouponApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create user and roles
        $this->user = User::factory()->create();
        Role::create(['name' => 'trade account', 'guard_name' => 'web']);
        Role::create(['name' => 'guest', 'guard_name' => 'web']);
    }

    protected function sanctumAuth($user)
    {
        \Laravel\Sanctum\Sanctum::actingAs($user);
        return ['Authorization' => 'Bearer test-token'];
    }

    public function test_can_retrieve_valid_public_coupon()
    {
        $coupon = Coupon::create([
            'name' => 'Public Coupon',
            'code' => 'PUBLIC10',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
            'restriction_type' => null,
        ]);

        $this->getJson('/api/coupons/PUBLIC10', $this->sanctumAuth($this->user))
            ->assertStatus(200)
            ->assertJsonPath('data.code', 'PUBLIC10');
    }

    public function test_cannot_retrieve_unpublished_coupon()
    {
        $coupon = Coupon::create([
            'name' => 'Unpublished',
            'code' => 'HIDDEN',
            'type' => 'redeem',
            'status' => 'unpublished',
            'discount_type' => 'fixed',
            'discount_value' => 10,
        ]);

        $this->getJson('/api/coupons/HIDDEN', $this->sanctumAuth($this->user))
            ->assertStatus(400)
            ->assertJsonFragment(['message' => 'This coupon is not available.']);
    }

    public function test_cannot_retrieve_expired_coupon()
    {
        $coupon = Coupon::create([
            'name' => 'Expired',
            'code' => 'EXPIRED',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
            'end_date' => now()->subDay(),
        ]);

        $this->getJson('/api/coupons/EXPIRED', $this->sanctumAuth($this->user))
            ->assertStatus(400)
            ->assertJsonFragment(['message' => 'This coupon has expired.']);
    }

    public function test_cannot_retrieve_future_coupon()
    {
        $coupon = Coupon::create([
            'name' => 'Future',
            'code' => 'FUTURE',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
            'start_date' => now()->addDay(),
        ]);

        $this->getJson('/api/coupons/FUTURE', $this->sanctumAuth($this->user))
            ->assertStatus(400)
            ->assertJsonFragment(['message' => 'This coupon is not yet valid.']);
    }

    public function test_cannot_retrieve_quota_exceeded_coupon()
    {
        $coupon = Coupon::create([
            'name' => 'Quota Full',
            'code' => 'FULL',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
            'quota' => 5,
            'used_count' => 5,
        ]);

        $this->getJson('/api/coupons/FULL', $this->sanctumAuth($this->user))
            ->assertStatus(400)
            ->assertJsonFragment(['message' => 'This coupon has reached its usage limit.']);
    }

    public function test_user_can_retrieve_role_restricted_coupon_if_has_role()
    {
        $coupon = Coupon::create([
            'name' => 'Trade Only',
            'code' => 'TRADE',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
            'restriction_type' => 'role',
            'role_level' => 'trade account',
        ]);

        // User doesn't have role yet
        $this->getJson('/api/coupons/TRADE', $this->sanctumAuth($this->user))
            ->assertStatus(403);

        // Assign role
        $this->user->assignRole('trade account');

        $this->getJson('/api/coupons/TRADE', $this->sanctumAuth($this->user))
            ->assertStatus(200)
            ->assertJsonPath('data.code', 'TRADE');
    }

    public function test_user_can_retrieve_specific_restricted_coupon_if_allowed()
    {
        $otherUser = User::factory()->create();
        $coupon = Coupon::create([
            'name' => 'Specific',
            'code' => 'SPECIFIC',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
            'restriction_type' => 'specific_user',
        ]);
        $coupon->users()->attach($this->user->id);

        $this->getJson('/api/coupons/SPECIFIC', $this->sanctumAuth($this->user))
            ->assertStatus(200);

        $this->getJson('/api/coupons/SPECIFIC', $this->sanctumAuth($otherUser))
            ->assertStatus(403);
    }

    public function test_can_list_available_claim_coupons()
    {
        // 1. Valid Claim Coupon
        $validClaim = Coupon::create([
            'name' => 'Valid Claim',
            'code' => 'CLAIM_VALID',
            'type' => 'claim',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
        ]);

        // 2. Valid Redeem Coupon (Should NOT be in list)
        Coupon::create([
            'name' => 'Valid Redeem',
            'code' => 'REDEEM_VALID',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
        ]);

        // 3. Unpublished Claim Coupon (Should NOT be in list)
        Coupon::create([
            'name' => 'Hidden Claim',
            'code' => 'CLAIM_HIDDEN',
            'type' => 'claim',
            'status' => 'unpublished',
            'discount_type' => 'fixed',
            'discount_value' => 10,
        ]);

        // 4. Restricted Claim Coupon (User doesn't have role) (Should NOT be in list)
        Coupon::create([
            'name' => 'Restricted Claim',
            'code' => 'CLAIM_ROLE',
            'type' => 'claim',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
            'restriction_type' => 'role',
            'role_level' => 'admin', // user is not admin
        ]);

        $response = $this->getJson('/api/coupons', $this->sanctumAuth($this->user))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');

        $this->assertEquals('CLAIM_VALID', $response->json('data.0.code'));
    }
}
