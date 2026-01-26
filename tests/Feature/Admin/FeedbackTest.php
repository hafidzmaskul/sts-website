<?php

namespace Tests\Feature\Admin;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_feedback_list()
    {
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();
        Feedback::create([
            'user_id' => $user->id,
            'message' => 'Test feedback message',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.feedback.index'));

        $response->assertStatus(200);
        $response->assertSee('Test feedback message');
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_admin_can_view_feedback_detail()
    {
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();
        $feedback = Feedback::create([
            'user_id' => $user->id,
            'message' => 'Detail feedback message content',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.feedback.show', $feedback));

        $response->assertStatus(200);
        $response->assertSee('Detail feedback message content');
        $response->assertSee($user->name);
    }
}
