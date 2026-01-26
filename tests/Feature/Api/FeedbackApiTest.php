<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeedbackApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_feedback()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/feedback', [
                'message' => 'Great app!',
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Feedback submitted successfully']);

        $this->assertDatabaseHas('feedback', [
            'user_id' => $user->id,
            'message' => 'Great app!',
        ]);
    }

    public function test_message_is_required()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/feedback', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    public function test_unauthenticated_user_cannot_submit_feedback()
    {
        $response = $this->postJson('/api/feedback', [
            'message' => 'Great app!',
        ]);

        $response->assertStatus(401);
    }
}
