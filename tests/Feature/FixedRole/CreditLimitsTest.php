<?php

namespace Tests\Feature\FixedRole;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Customer;
use App\Models\Company;
use App\Models\CreditLimit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreditLimitsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        if (!Role::where('name', 'credit facilities account')->exists()) {
            Role::create(['name' => 'credit facilities account']);
        }
        if (!Role::where('name', 'trade account')->exists()) {
            Role::create(['name' => 'trade account']);
        }
    }

    public function test_credit_facilities_user_can_access_credit_limit_page()
    {
        $company = Company::create(['name' => 'Test Company']);
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
            ->get(route('dashboard.credit-limits.index'))
            ->assertStatus(200)
            ->assertSee('Credit Limit History');
    }

    public function test_trade_account_user_cannot_access_credit_limit_page()
    {
        $user = User::factory()->create();
        $user->assignRole('trade account');

        $this->actingAs($user)
            ->get(route('dashboard.credit-limits.index'))
            ->assertStatus(403);
    }

    public function test_credit_limit_data_is_displayed_correctly()
    {
        $company = Company::create(['name' => 'Test Company']);
        $user = User::factory()->create();
        $user->assignRole('credit facilities account');

        $customer = Customer::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $user->email,
        ]);

        // Create Credit Limit History
        CreditLimit::create([
            'company_id' => $company->id,
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'description' => 'Initial Limit',
            'credit' => 1000,
            'debit' => 0,
            'balance' => 1000,
        ]);

        CreditLimit::create([
            'company_id' => $company->id,
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'description' => 'Purchase',
            'credit' => 0,
            'debit' => 200,
            'balance' => 800,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\FixedRole\CreditLimits\Index::class)
            ->assertSee('Current Balance')
            ->assertSee('800.00') // Display formatted balance
            ->assertSee('Initial Limit')
            ->assertSee('Purchase');
    }
}
