<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_profile()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $newData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone_number' => '1234567890',
            'address' => '123 Main St',
            'postal_code' => '12345',
            'city' => 'Anytown',
            'country' => 'USA',
        ];

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/me', $newData);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('user.name', 'John Doe')
            ->assertJsonPath('user.email', 'john.doe@example.com')
            ->assertJsonPath('user.customer.first_name', 'John')
            ->assertJsonPath('user.customer.last_name', 'Doe')
            ->assertJsonPath('user.customer.phone', '1234567890');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'postal_code' => '12345',
            'city' => 'Anytown',
            'country' => 'USA',
        ]);
    }

    public function test_update_profile_validation()
    {
        $user = User::factory()->create();

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/me', [
                    'email' => 'invalid-email',
                ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'email']);
    }

    public function test_email_must_be_unique()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $token = $user1->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/me', [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'user2@example.com',
                ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
