<?php

namespace Tests\Feature\Livewire\Admin\Quotes;

use App\Livewire\Admin\Quotes\Show;
use App\Models\Cart;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteBuilder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the admin and guest roles
        $this->adminRole = Role::findOrCreate('admin');
        Role::findOrCreate('guest');

        // Create an admin user and assign the role
        $this->admin = User::factory()->create();
        $this->admin->assignRole($this->adminRole);
    }

    public function test_guest_is_forbidden_due_to_auto_guest_login()
    {
        $quote = Quote::factory()->create();

        $response = $this->get(route('admin.quotes.show', $quote));

        $response->assertForbidden();
    }

    public function test_non_admin_cannot_view_quote_details()
    {
        $nonAdmin = User::factory()->create();
        $quote = Quote::factory()->create();

        $response = $this->actingAs($nonAdmin)
            ->get(route('admin.quotes.show', $quote));

        $response->assertForbidden();
    }

    public function test_admin_can_view_quote_details_without_registered_user()
    {
        $quote = Quote::factory()->create([
            'email' => 'unregistered@example.com',
            'first_name' => 'Alice',
            'last_name' => 'Smith',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.quotes.show', $quote));

        $response->assertOk();
        $response->assertSee('Alice Smith');
        $response->assertSee('Not Registered');
    }

    public function test_admin_can_view_quote_details_with_registered_user_and_products()
    {
        // 1. Create a registered customer user
        $customerUser = User::factory()->create([
            'email' => 'customer@example.com',
            'name' => 'Bob Builder',
        ]);

        $company = Company::create([
            'name' => 'Construction Inc',
            'registration_number' => '12345678',
            'vat_number' => 'GB987654321',
            'requested_credit_limit' => 5000.00,
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $customerUser->id,
            'company_id' => $company->id,
            'account_number' => 'ACC123',
            'account_level' => 'head',
            'role_applied' => 'customer',
            'status_review' => 'approved',
            'job_title' => 'Project Manager',
        ]);

        // 2. Create products
        $product1 = new Product;
        $product1->title = 'Hammer';
        $product1->slug = 'slug-hammer';
        $product1->base_price = 15.50;
        $product1->created_by = $this->admin->id;
        $product1->status = 'active';
        $product1->save();

        $product2 = new Product;
        $product2->title = 'Nails';
        $product2->slug = 'slug-nails';
        $product2->base_price = 5.00;
        $product2->created_by = $this->admin->id;
        $product2->status = 'active';
        $product2->save();

        // 3. Create Quote Builder with items
        $builder = QuoteBuilder::create([
            'user_id' => $customerUser->id,
            'name' => 'Bob\'s Construction Quote',
        ]);
        $builder->products()->attach($product1->id, ['quantity' => 2]);

        // 4. Create Cart Item
        Cart::create([
            'user_id' => $customerUser->id,
            'product_id' => $product2->id,
            'quantity' => 10,
        ]);

        // 5. Create the Quote submission with same email
        $quote = Quote::factory()->create([
            'email' => 'customer@example.com',
            'first_name' => 'Bob',
            'last_name' => 'Builder',
            'company_name' => 'Construction Inc',
        ]);

        // 6. Access and assert Livewire view sees user info and products
        Livewire::actingAs($this->admin)
            ->test(Show::class, ['quote' => $quote])
            ->assertOk()
            ->assertSee('Bob Builder')
            ->assertSee('ACC123')
            ->assertSee('Construction Inc')
            ->assertSee('Bob\'s Construction Quote')
            ->assertSee('Hammer')
            ->assertSee('Nails');
    }

    public function test_admin_can_view_quote_source_page()
    {
        $quote = Quote::factory()->create([
            'source_page' => 'System Design',
        ]);

        Livewire::actingAs($this->admin)
            ->test(Show::class, ['quote' => $quote])
            ->assertOk()
            ->assertSee('System Design');
    }
}
