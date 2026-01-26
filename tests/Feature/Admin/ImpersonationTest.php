<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ImpersonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login_via_signed_masquerade_route_without_auth()
    {
        $user = User::factory()->create();

        // Generate Signed URL
        $url = URL::signedRoute('admin.users.masquerade', ['userId' => $user->id]);

        // Access via unauthenticated session (simulating Incognito)
        $response = $this->get($url);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_cannot_login_via_masquerade_route_with_invalid_signature()
    {
        $user = User::factory()->create();

        // Manually construct URL without signature or invalid one
        $url = route('admin.users.masquerade', ['userId' => $user->id]);

        $response = $this->get($url);

        // Should be 403 Invalid Signature
        $response->assertStatus(403);
        $this->assertGuest();
    }
}
