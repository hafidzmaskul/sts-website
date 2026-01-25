<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create roles
        Role::firstOrCreate(['name' => 'trade account']);
        Role::firstOrCreate(['name' => 'credit facilities account']);
        Role::firstOrCreate(['name' => 'staff']);
    }

    private function createHeadUser($role = 'trade account')
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        $company = Company::create(['name' => 'Test Company', 'email' => 'test@co.com', 'phone' => '123']);

        Customer::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'first_name' => 'Head',
            'last_name' => 'User',
            'email' => $user->email,
            'account_level' => 'head',
            'status_review' => 'approved',
        ]);

        return $user;
    }

    public function test_head_account_can_list_staff_users()
    {
        $head = $this->createHeadUser();

        // Create staff users
        User::factory()->count(3)->create(['parent_id' => $head->id]);

        Sanctum::actingAs($head);

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data.data');
    }

    public function test_head_account_cannot_see_other_staff_users()
    {
        $head1 = $this->createHeadUser();
        $head2 = $this->createHeadUser();

        User::factory()->create(['parent_id' => $head1->id]);
        User::factory()->create(['parent_id' => $head2->id]);

        Sanctum::actingAs($head1);

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.data'); // Only their own child
    }

    public function test_head_account_can_create_staff_user()
    {
        $head = $this->createHeadUser();
        Sanctum::actingAs($head);

        $data = [
            'name' => 'New Staff Member',
            'email' => 'newstaff@example.com',
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->postJson('/api/users', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'newstaff@example.com',
            'parent_id' => $head->id,
        ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'newstaff@example.com',
            'account_level' => 'staff',
        ]);
    }

    public function test_head_account_can_update_staff_user()
    {
        $head = $this->createHeadUser();
        $staff = User::factory()->create(['parent_id' => $head->id]);

        Sanctum::actingAs($head);

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->putJson("/api/users/{$staff->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $staff->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_head_account_can_delete_staff_user()
    {
        $head = $this->createHeadUser();
        $staff = User::factory()->create(['parent_id' => $head->id]);

        Sanctum::actingAs($head);

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->deleteJson("/api/users/{$staff->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $staff->id]);
    }

    public function test_staff_user_cannot_access_user_management()
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff'); // Not a head account

        Sanctum::actingAs($staff);

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->getJson('/api/users');
        $response->assertStatus(403);

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->postJson('/api/users', ['name' => 'Test', 'email' => 'test@test.com']);
        $response->assertStatus(403);
    }
}
