<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Customers\Show;
use App\Mail\CustomerApprovedWelcomeMail;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomerApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_approving_customer_creates_monthly_credit_limit_record()
    {
        Mail::fake();

        // 1. Setup Admin User with permissions
        $admin = User::factory()->create();
        $role = Role::create(['name' => 'admin']);
        $customerRole = Role::create(['name' => 'customer']);
        $permissionEdit = Permission::create(['name' => 'customers.edit']);
        $permissionView = Permission::create(['name' => 'customers.view']);
        $role->givePermissionTo($permissionEdit);
        $role->givePermissionTo($permissionView);
        $admin->assignRole($role);

        $this->actingAs($admin);

        // 2. Setup Customer and Company with requested credit limit
        $company = Company::create([
            'name' => 'Test Company',
            'requested_credit_limit' => 5000.00,
        ]);

        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'company_id' => $company->id,
            'status_review' => 'pending',
            'role_applied' => 'customer',
        ]);

        // 3. Simulate Approval via Livewire
        Livewire::test(Show::class, ['customer' => $customer])
            ->call('approve');

        // 4. Assert Mail was sent
        Mail::assertSent(CustomerApprovedWelcomeMail::class, function ($mail) use ($customer) {
            return $mail->hasTo($customer->email) && $mail->user->email === $customer->email;
        });

        // 5. Assert MonthlyCreditLimit is created
        $customer->refresh();
        $this->assertNotNull($customer->user_id, 'Customer should have a user_id assigned');

        $this->assertDatabaseHas('monthly_credit_limits', [
            'company_id' => $company->id,
            'user_id' => $customer->user_id,
            'amount' => 5000.00,
            'description' => 'Initial approved credit limit',
        ]);

        $this->assertDatabaseHas('credit_limits', [
            'customer_id' => $customer->id,
            'company_id' => $company->id,
            'balance' => 5000.00,
        ]);
    }
}
