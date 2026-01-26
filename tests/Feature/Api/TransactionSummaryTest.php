<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TransactionSummaryTest extends TestCase
{
    use RefreshDatabase;

    private function createTransaction($customerId, $status)
    {
        return Transaction::forceCreate([
            'invoice_code' => 'INV-' . uniqid(),
            'customer_id' => $customerId,
            'subtotal' => 100,
            'tax_amount' => 10,
            'total_amount' => 110,
            'status' => $status,
            'payment_method' => 'credit_card',
            'contact_email' => 'test@example.com',
        ]);
    }

    private function createCompany()
    {
        return Company::create([
            'name' => 'Test Company ' . uniqid(),
            'email' => 'company' . uniqid() . '@example.com',
            'phone' => '1234567890',
            'website' => 'https://example.com',
            'logo' => 'logo.png',
            'status' => 'active',
        ]);
    }

    public function test_staff_can_view_own_transactions_only()
    {
        $company = $this->createCompany();

        // Staff User
        $user = User::factory()->create();
        $customer = Customer::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'account_level' => 'staff',
        ]);

        // Other User in same company
        $otherUser = User::factory()->create();
        $otherCustomer = Customer::factory()->create([
            'user_id' => $otherUser->id,
            'company_id' => $company->id,
            'account_level' => 'staff',
        ]);

        // Transactions
        $this->createTransaction($customer->id, 'pending');
        $this->createTransaction($customer->id, 'completed');
        $this->createTransaction($otherCustomer->id, 'pending');

        Sanctum::actingAs($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])
            ->getJson('/api/transactions/summary');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data.transactions'); // Only own transactions
        $response->assertJsonFragment(['pending' => 1]);
        $response->assertJsonFragment(['completed' => 1]);
    }

    public function test_head_can_view_company_transactions()
    {
        $company = $this->createCompany();

        // Head User
        $headUser = User::factory()->create();
        $headCustomer = Customer::factory()->create([
            'user_id' => $headUser->id,
            'company_id' => $company->id,
            'account_level' => 'head',
        ]);

        // Staff User in same company
        $staffUser = User::factory()->create();
        $staffCustomer = Customer::factory()->create([
            'user_id' => $staffUser->id,
            'company_id' => $company->id,
            'account_level' => 'staff',
        ]);

        // Other Company User
        $otherCompany = $this->createCompany();
        $otherCompanyUser = User::factory()->create();
        $otherCompanyCustomer = Customer::factory()->create([
            'user_id' => $otherCompanyUser->id,
            'company_id' => $otherCompany->id,
            'account_level' => 'head',
        ]);

        // Transactions
        $this->createTransaction($headCustomer->id, 'pending');
        $this->createTransaction($staffCustomer->id, 'completed');
        $this->createTransaction($otherCompanyCustomer->id, 'pending'); // Should not see

        Sanctum::actingAs($headUser);

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])
            ->getJson('/api/transactions/summary');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data.transactions'); // Head + Staff
        $response->assertJsonFragment(['pending' => 1]);
        $response->assertJsonFragment(['completed' => 1]);
    }

    public function test_transaction_summary_counts()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create([
            'user_id' => $user->id,
            'account_level' => 'staff',
        ]);

        for ($i = 0; $i < 3; $i++)
            $this->createTransaction($customer->id, 'pending');
        for ($i = 0; $i < 2; $i++)
            $this->createTransaction($customer->id, 'processing');
        $this->createTransaction($customer->id, 'completed');

        Sanctum::actingAs($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])
            ->getJson('/api/transactions/summary');

        $response->assertStatus(200);
        $response->assertJsonPath('data.summary.pending', 3);
        $response->assertJsonPath('data.summary.processing', 2);
        $response->assertJsonPath('data.summary.completed', 1);
    }

    public function test_can_access_via_basic_auth()
    {
        $company = $this->createCompany();
        $user = User::factory()->create(['password' => bcrypt('password')]);
        Customer::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'account_level' => 'staff',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':password'),
        ])->getJson('/api/transactions/summary');

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data' => ['summary', 'transactions']]);
    }
}
