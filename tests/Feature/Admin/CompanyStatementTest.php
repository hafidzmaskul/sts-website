<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Companies\Show;
use App\Mail\CompanyStatementMail;
use App\Models\Company;
use App\Models\CreditLimit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CompanyStatementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_send_statement_invoice_filtered_by_date_and_debit()
    {
        Mail::fake();
        \Illuminate\Support\Facades\Storage::fake();

        // 1. Setup Admin
        $admin = User::factory()->create(['name' => 'Admin User']);
        $role = Role::create(['name' => 'admin']);
        $permissionView = Permission::create(['name' => 'customers.view']);
        $role->givePermissionTo($permissionView);
        $admin->assignRole($role);
        $this->actingAs($admin);

        // 2. Setup Company with Contacts
        $company = Company::create([
            'name' => 'Test Company',
            'purchasing_contact_email' => 'purchasing@example.com',
            'accounts_contact_email' => 'accounts@example.com',
        ]);

        $customer = \App\Models\Customer::create([
            'company_id' => $company->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'user_id' => $admin->id,
        ]);

        // 3. Create Transactions (Credit Limits)

        // Should be ignored (Credit/Payment)
        CreditLimit::create([
            'company_id' => $company->id,
            'customer_id' => $customer->id,
            'description' => 'Payment',
            'credit' => 100.00,
            'debit' => 0,
            'balance' => 0,
            'created_at' => now(),
        ]);

        // Should be ignored (Wrong Date - Last Month)
        CreditLimit::create([
            'company_id' => $company->id,
            'customer_id' => $customer->id,
            'description' => 'Old Invoice',
            'credit' => 0,
            'debit' => 50.00,
            'balance' => 50.00,
            'created_at' => now()->subMonth(),
        ]);

        // Should be INCLUDED (Correct Date + Debit)
        CreditLimit::create([
            'company_id' => $company->id,
            'customer_id' => $customer->id,
            'description' => 'Current Invoice',
            'credit' => 0,
            'debit' => 75.00,
            'balance' => 125.00,
            'created_at' => now(),
        ]);

        // 3. Call sendInvoice action
        Livewire::test(Show::class, ['company' => $company])
            ->set('statementMonth', now()->month)
            ->set('statementYear', now()->year)
            ->call('sendInvoice')
            ->assertDispatched('notify', function ($event, $data) {
                return $data['type'] === 'success';
            });

        // 4. Assert Mail Sent
        Mail::assertQueued(CompanyStatementMail::class, function ($mail) use ($company) {
            // Check that PDF generation logic is present in attachments
            $attachments = $mail->attachments();
            $hasPdf = !empty($attachments) &&
                $attachments[0] instanceof \Illuminate\Mail\Mailables\Attachment &&
                str_contains($attachments[0]->as, 'Statement_');

            // Should contain only the 1 relevant debit transaction
            $hasCorrectCount = $mail->creditLimits->count() === 1;
            $hasCorrectTotal = $mail->totalBalance === 75.00;

            return $mail->company->id === $company->id &&
                $hasCorrectCount &&
                $hasCorrectTotal &&
                $hasPdf &&
                ($mail->hasTo('purchasing@example.com') || $mail->hasTo('accounts@example.com'));
        });

        // 5. Assert History Logged & File Stored
        $this->assertDatabaseHas('statement_histories', [
            'company_id' => $company->id,
            'user_id' => $admin->id,
            'period' => now()->format('F Y'),
        ]);

        \Illuminate\Support\Facades\Storage::assertExists('statements/' . $company->id . '/' . time() . '_Statement_' . str_replace(' ', '_', now()->format('F Y')) . '.pdf');
    }

    public function test_shows_error_if_no_debit_transactions_found()
    {
        Mail::fake();
        $admin = User::factory()->create(['name' => 'Admin User']);
        $role = Role::create(['name' => 'admin']);
        $permissionView = Permission::create(['name' => 'customers.view']);
        $role->givePermissionTo($permissionView);
        $admin->assignRole($role);
        $this->actingAs($admin);

        $company = Company::create(['name' => 'Empty Company']);

        Livewire::test(Show::class, ['company' => $company])
            ->set('statementMonth', now()->month)
            ->set('statementYear', now()->year)
            ->call('sendInvoice')
            ->assertDispatched('notify', function ($event, $data) {
                return $data['type'] === 'error' && str_contains($data['message'], 'No debit transactions found');
            });

        Mail::assertNothingQueued();
    }
}
