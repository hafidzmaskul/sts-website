<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductLikeTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(User $creator, $overrides = [])
    {
        return Product::create(array_merge([
            'title' => 'Test Product ' . uniqid(),
            'slug' => 'test-product-' . uniqid(),
            'created_by' => $creator->id,
            'status' => 'active',
        ], $overrides));
    }

    public function test_user_can_like_and_unlike_product()
    {
        $user = User::factory()->create();
        $product = $this->createProduct($user);

        Sanctum::actingAs($user);

        // Like
        $response = $this->withHeaders(['Authorization' => 'Bearer fake-token'])
            ->postJson("/api/products/like", [
                'product_id' => $product->id
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product liked successfully',
                'data' => [
                    'liked' => true
                ]
            ]);

        $this->assertDatabaseHas('product_user_likes', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        // Unlike
        $response = $this->withHeaders(['Authorization' => 'Bearer fake-token'])
            ->postJson("/api/products/unlike", [
                'product_id' => $product->id
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product unliked successfully',
                'data' => [
                    'liked' => false
                ]
            ]);

        $this->assertDatabaseMissing('product_user_likes', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);
    }

    public function test_start_like_with_invalid_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer fake-token'])
            ->postJson("/api/products/like", [
                'product_id' => 99999
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id']);
    }

    public function test_user_can_get_liked_products()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $product1 = $this->createProduct($otherUser, ['title' => 'P1']);
        $product3 = $this->createProduct($otherUser, ['title' => 'P3']);

        $user->likedProducts()->attach($product1->id);
        sleep(1); // Ensure timestamp diff for order check
        $user->likedProducts()->attach($product3->id);

        Sanctum::actingAs($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer fake-token'])
            ->getJson('/api/liked-products');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data.data')
            ->assertJsonPath('data.data.0.id', $product3->id)
            ->assertJsonPath('data.data.1.id', $product1->id);
    }

    public function test_unauthenticated_user_cannot_like_product()
    {
        $user = User::factory()->create();
        $product = $this->createProduct($user);

        $response = $this->postJson("/api/products/like", [
            'product_id' => $product->id
        ]);

        $response->assertStatus(401);
    }

    public function test_basic_auth_access()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $product = $this->createProduct($user);

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test@example.com:password')
        ])->postJson("/api/products/like", [
                    'product_id' => $product->id
                ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('product_user_likes', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);
    }
}
