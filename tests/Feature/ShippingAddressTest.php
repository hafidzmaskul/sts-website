<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_shipping_addresses()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $shippingAddress = ShippingAddress::create([
            'customer_id' => $customer->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'country' => 'USA',
            'city' => 'NY',
            'address' => '123 St',
            'postal_code' => '10001',
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/shipping-addresses');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_can_create_shipping_address()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $token = $user->createToken('test')->plainTextToken;

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'country' => 'USA',
            'city' => 'NY',
            'address' => '123 St',
            'postal_code' => '10001',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/shipping-addresses', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('shipping_addresses', $data);
    }

    public function test_can_show_shipping_address()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $shippingAddress = ShippingAddress::create([
            'customer_id' => $customer->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'country' => 'USA',
            'city' => 'NY',
            'address' => '123 St',
            'postal_code' => '10001',
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson("/api/shipping-addresses/{$shippingAddress->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['first_name' => 'John']);
    }

    public function test_can_update_shipping_address()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $shippingAddress = ShippingAddress::create([
            'customer_id' => $customer->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'country' => 'USA',
            'city' => 'NY',
            'address' => '123 St',
            'postal_code' => '10001',
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $data = [
            'first_name' => 'Jane',
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson("/api/shipping-addresses/{$shippingAddress->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['first_name' => 'Jane']);

        $this->assertDatabaseHas('shipping_addresses', ['id' => $shippingAddress->id, 'first_name' => 'Jane']);
    }

    public function test_can_delete_shipping_address()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $shippingAddress = ShippingAddress::create([
            'customer_id' => $customer->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'country' => 'USA',
            'city' => 'NY',
            'address' => '123 St',
            'postal_code' => '10001',
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson("/api/shipping-addresses/{$shippingAddress->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('shipping_addresses', ['id' => $shippingAddress->id]);
    }

    public function test_cannot_access_other_users_address()
    {
        $user1 = User::factory()->create();
        $customer1 = Customer::factory()->create(['user_id' => $user1->id]);
        $address1 = ShippingAddress::create([
            'customer_id' => $customer1->id,
            'first_name' => 'User1',
            'last_name' => 'Doe',
            'country' => 'USA',
            'city' => 'NY',
            'address' => '123 St',
            'postal_code' => '10001',
        ]);

        $user2 = User::factory()->create();
        $customer2 = Customer::factory()->create(['user_id' => $user2->id]);

        $token = $user2->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson("/api/shipping-addresses/{$address1->id}");

        $response->assertStatus(404); // Should not find it or 403
    }
}
