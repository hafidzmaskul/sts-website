<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Companies\Show;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use App\Models\MonthlyCreditLimit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CompanyMonthlyCreditLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_page_displays_monthly_credit_limit_history()
    {
        // 1. Setup Admin User
        $admin = User::factory()->create(['name' => 'Admin User']);
        $role = Role::create(['name' => 'admin']);
        $permissionView = Permission::create(['name' => 'customers.view']);
        $role->givePermissionTo($permissionView);
        $admin->assignRole($role);

        $this->actingAs($admin);

        // 2. Setup Company, Customer, and Monthly Credit Limit
        $company = Company::create([
            'name' => 'Test Company',
            'requested_credit_limit' => 5000.00,
        ]);

        $user = User::factory()->create(['name' => 'Customer User']);

        $customer = Customer::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'company_id' => $company->id,
            'status_review' => 'approved',
        ]);

        MonthlyCreditLimit::create([
            'company_id' => $company->id,
            'user_id' => $user->id, // Created by Customer (Initial)
            'amount' => 5000.00,
            'description' => 'Initial approved credit limit',
        ]);

        // 3. Visit Company Page
        Livewire::test(Show::class, ['company' => $company])
            ->assertSee('Monthly Credit Limit History')
            ->assertSee('5,000.00')
            ->assertSee('Initial approved credit limit')
            ->assertSee('Customer User');
    }

    public function test_can_manually_add_monthly_credit_limit()
    {
        // 1. Setup Admin, Company, User
        $admin = User::factory()->create(['name' => 'Admin User']);
        $role = Role::create(['name' => 'admin']);
        $permissionView = Permission::create(['name' => 'customers.view']);
        $role->givePermissionTo($permissionView);
        $admin->assignRole($role);
        $this->actingAs($admin);

        $company = Company::create(['name' => 'Test Company']);
        $user = User::factory()->create(['name' => 'Test User']);
        $customer = Customer::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'company_id' => $company->id,
        ]);

        // 2. Add via Livewire
        Livewire::test(Show::class, ['company' => $company])
            ->set('newLimitAmount', 1000)
            ->set('newLimitDescription', 'Manual Add')
            ->call('saveLimit')
            ->assertDispatched('notify');

        // 3. Verify Database
        $this->assertDatabaseHas('monthly_credit_limits', [
            'company_id' => $company->id,
            'user_id' => $admin->id,
            'amount' => 1000,
            'description' => 'Manual Add',
        ]);

        $this->travel(1)->second();

        // 4. Verify Order (Add another one)
        Livewire::test(Show::class, ['company' => $company])
            ->set('newLimitAmount', 2000)
            ->set('newLimitDescription', 'Manual Add 2')
            ->call('saveLimit')
            ->assertSee('£2,000.00')
            ->assertSee('Active');

        $limits = MonthlyCreditLimit::where('company_id', $company->id)->latest()->get();
        $this->assertEquals(2000, $limits->first()->amount);
        $this->assertEquals(1000, $limits->last()->amount);
    }
}

