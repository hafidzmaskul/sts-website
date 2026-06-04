<?php

use App\Livewire\Admin\Companies\Show;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Company;
use App\Models\CreditLimit;
use App\Models\Customer;
use App\Models\Feedback;
use App\Models\MonthlyCreditLimit;
use App\Models\Product;
use App\Models\QuoteBuilder;
use App\Models\StatementHistory;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionStatusHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure permissions exist
    Permission::firstOrCreate(['name' => 'customers.view']);
    Permission::firstOrCreate(['name' => 'customers.delete']);
});

test('unauthorized users cannot delete a company', function () {
    $user = User::factory()->create();
    // User only has view, not delete permission
    $role = Role::firstOrCreate(['name' => 'admin']);
    $role->givePermissionTo('customers.view');
    $user->assignRole($role);

    $company = Company::create([
        'name' => 'Test Company',
    ]);

    Livewire::actingAs($user)
        ->test(Show::class, ['company' => $company])
        ->assertStatus(200)
        ->assertDontSee('Delete Company');
});

test('authorized users can delete a company and all related cascading data', function () {
    Storage::fake();

    $admin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $role->givePermissionTo(['customers.view', 'customers.delete']);
    $admin->assignRole($role);

    // Create Company
    $company = Company::create([
        'name' => 'Mega Corp',
    ]);

    // Create Customer and associated User
    $customerUser = User::factory()->create(['name' => 'John Doe', 'email' => 'john@megacorp.com']);
    $customer = Customer::create([
        'user_id' => $customerUser->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@megacorp.com',
        'company_id' => $company->id,
    ]);

    // Create credit limit histories
    CreditLimit::create([
        'company_id' => $company->id,
        'customer_id' => $customer->id,
        'credit' => 1000.00,
        'debit' => 0,
        'balance' => 1000.00,
        'description' => 'Test credit limit',
    ]);

    // Create monthly credit limits
    MonthlyCreditLimit::create([
        'company_id' => $company->id,
        'user_id' => $admin->id,
        'amount' => 5000.00,
        'description' => 'Monthly limit',
    ]);

    // Create statement history with a file
    $filePath = 'statements/'.$company->id.'/test_statement.pdf';
    Storage::put($filePath, 'fake pdf content');
    $statement = StatementHistory::create([
        'company_id' => $company->id,
        'user_id' => $admin->id,
        'period' => 'June 2026',
        'recipients' => 'john@megacorp.com',
        'file_path' => $filePath,
    ]);

    // Create product and brand for transaction/quote builder
    $brand = Brand::create(['name' => 'Brand X', 'slug' => 'brand-x', 'created_by' => $admin->id]);
    $product = Product::create([
        'title' => 'Test Item',
        'slug' => 'test-item',
        'sku' => 'TEST-SKU',
        'brand_id' => $brand->id,
        'base_price' => 10.00,
        'created_by' => $admin->id,
    ]);

    // Create transaction
    $transaction = Transaction::create([
        'customer_id' => $customer->id,
        'invoice_code' => 'INV-1234',
        'subtotal' => 10.00,
        'tax_amount' => 1.00,
        'total_amount' => 11.00,
        'status' => 'pending',
    ]);

    $transactionItem = TransactionItem::create([
        'transaction_id' => $transaction->id,
        'product_id' => $product->id,
        'product_name_snapshot' => $product->title,
        'quantity' => 1,
        'unit_price' => 10.00,
        'total_price' => 10.00,
    ]);

    $transactionHistory = TransactionStatusHistory::create([
        'transaction_id' => $transaction->id,
        'user_id' => $admin->id,
        'status' => 'pending',
    ]);

    // Create quote builder
    $quoteBuilder = QuoteBuilder::create([
        'user_id' => $customerUser->id,
        'name' => 'My Quote',
    ]);
    $quoteBuilder->products()->attach($product->id, ['quantity' => 2]);

    // Create cart item
    $cartItem = Cart::create([
        'user_id' => $customerUser->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => 10.00,
    ]);

    // Create feedback
    $feedback = Feedback::create([
        'user_id' => $customerUser->id,
        'message' => 'Great!',
    ]);

    // Verify all records exist in DB
    expect(Company::where('id', $company->id)->exists())->toBeTrue();
    expect(User::where('id', $customerUser->id)->exists())->toBeTrue();
    expect(Customer::where('id', $customer->id)->exists())->toBeTrue();
    expect(CreditLimit::where('company_id', $company->id)->exists())->toBeTrue();
    expect(MonthlyCreditLimit::where('company_id', $company->id)->exists())->toBeTrue();
    expect(StatementHistory::where('company_id', $company->id)->exists())->toBeTrue();
    expect(Transaction::where('id', $transaction->id)->exists())->toBeTrue();
    expect(TransactionItem::where('transaction_id', $transaction->id)->exists())->toBeTrue();
    expect(TransactionStatusHistory::where('transaction_id', $transaction->id)->exists())->toBeTrue();
    expect(QuoteBuilder::where('id', $quoteBuilder->id)->exists())->toBeTrue();
    expect(Cart::where('id', $cartItem->id)->exists())->toBeTrue();
    expect(Feedback::where('id', $feedback->id)->exists())->toBeTrue();
    expect(Storage::exists($filePath))->toBeTrue();

    // Call livewire test to delete
    Livewire::actingAs($admin)
        ->test(Show::class, ['company' => $company])
        ->assertSee('Delete Company')
        ->call('deleteCompany')
        ->assertRedirect(route('admin.companies.index'));

    // Verify all records are deleted in cascade
    expect(Company::where('id', $company->id)->exists())->toBeFalse();
    expect(User::where('id', $customerUser->id)->exists())->toBeFalse();
    expect(Customer::where('id', $customer->id)->exists())->toBeFalse();
    expect(CreditLimit::where('company_id', $company->id)->exists())->toBeFalse();
    expect(MonthlyCreditLimit::where('company_id', $company->id)->exists())->toBeFalse();
    expect(StatementHistory::where('company_id', $company->id)->exists())->toBeFalse();
    expect(Transaction::where('id', $transaction->id)->exists())->toBeFalse();
    expect(TransactionItem::where('transaction_id', $transaction->id)->exists())->toBeFalse();
    expect(TransactionStatusHistory::where('transaction_id', $transaction->id)->exists())->toBeFalse();
    expect(QuoteBuilder::where('id', $quoteBuilder->id)->exists())->toBeFalse();
    expect(Cart::where('id', $cartItem->id)->exists())->toBeFalse();
    expect(Feedback::where('id', $feedback->id)->exists())->toBeFalse();

    // Verify file is deleted
    expect(Storage::exists($filePath))->toBeFalse();
});
