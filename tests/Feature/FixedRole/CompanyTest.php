<?php

namespace Tests\Feature\FixedRole;

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        if (! Role::where('name', 'credit facilities account')->exists()) {
            Role::create(['name' => 'credit facilities account']);
        }
        if (! Role::where('name', 'trade account')->exists()) {
            Role::create(['name' => 'trade account']);
        }
    }

    public function test_trade_account_user_can_access_company_page()
    {
        $company = Company::create(['name' => 'Test Company']);
        $user = User::factory()->create();
        $user->assignRole('trade account');

        $customer = Customer::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $user->email,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard.company.show'))
            ->assertStatus(200)
            ->assertSee('Company Details')
            ->assertSee('Test Company');
    }

    public function test_credit_facilities_user_can_access_company_page()
    {
        $company = Company::create(['name' => 'Credit Company']);
        $user = User::factory()->create();
        $user->assignRole('credit facilities account');

        $customer = Customer::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $user->email,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard.company.show'))
            ->assertStatus(200)
            ->assertSee('Company Details')
            ->assertSee('Credit Company');
    }

    public function test_unauthorized_user_cannot_access_company_page()
    {
        $user = User::factory()->create();
        // No role assigned

        $this->actingAs($user)
            ->get(route('dashboard.company.show'))
            ->assertStatus(403);
    }
}
