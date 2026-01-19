<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GuestRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'guest', 'guard_name' => 'web']);
    }

    /**
     * Test guest registration endpoint.
     */
    public function test_guest_registration_creates_user_and_customer()
    {
        $response = $this->postJson('/api/guest-register');

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'access_token',
                'token_type',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'customer' => [
                        'id',
                        'user_id',
                        'role_applied',
                        'status',
                    ]
                ]
            ]);

        $this->assertTrue($response['success']);
        $this->assertNotNull($response['access_token']);

        $user = User::where('email', $response['user']['email'])->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('guest'));

        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'role_applied' => 'guest',
            'status' => 'active',
        ]);
    }
}
