<?php

use App\Livewire\Admin\QuoteBuilders\Index;
use App\Livewire\Admin\QuoteBuilders\Show;
use App\Models\Brand;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use App\Models\QuoteBuilder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Set up default admin role
    $this->adminRole = Role::firstOrCreate(['name' => 'admin']);
    $this->adminUser = User::factory()->create();
    $this->adminUser->assignRole($this->adminRole);

    // Create guest role so EnsureGuestUser middleware can assign it
    Role::firstOrCreate(['name' => 'guest']);

    // Create a regular user / customer
    $this->customerRole = Role::firstOrCreate(['name' => 'customer']);
    $this->customerUser = User::factory()->create(['name' => 'John Doe']);
    $this->customerUser->assignRole($this->customerRole);

    // Link customer details and company
    $this->company = Company::create(['name' => 'Test Company']);
    $this->customer = Customer::create([
        'user_id' => $this->customerUser->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $this->customerUser->email,
        'company_id' => $this->company->id,
    ]);

    // Create a product
    $this->brand = Brand::create(['name' => 'Brand X', 'slug' => 'brand-x', 'created_by' => $this->adminUser->id]);
    $this->product = Product::create([
        'title' => 'Test Product',
        'slug' => 'test-product',
        'sku' => 'TEST-SKU',
        'brand_id' => $this->brand->id,
        'base_price' => 100.00,
        'created_by' => $this->adminUser->id,
    ]);

    // Create a quote builder draft
    $this->quoteBuilder = QuoteBuilder::create([
        'user_id' => $this->customerUser->id,
        'name' => 'My Draft Builder',
    ]);
    $this->quoteBuilder->products()->attach($this->product->id, ['quantity' => 3]);
});

test('unauthorized guest or regular user cannot access quote builders dashboard', function () {
    $this->get('/admin/quote-builders')->assertStatus(403);

    $this->actingAs($this->customerUser)
        ->get('/admin/quote-builders')
        ->assertStatus(403);
});

test('authorized admin can access quote builders dashboard and see the drafts list', function () {
    $this->actingAs($this->adminUser)
        ->get('/admin/quote-builders')
        ->assertStatus(200);

    Livewire::actingAs($this->adminUser)
        ->test(Index::class)
        ->assertSee('My Draft Builder')
        ->assertSee('John Doe');
});

test('admin can search quote builders', function () {
    $anotherUser = User::factory()->create(['name' => 'Alice Smith']);
    $anotherBuilder = QuoteBuilder::create([
        'user_id' => $anotherUser->id,
        'name' => 'Alice Draft',
    ]);

    Livewire::actingAs($this->adminUser)
        ->test(Index::class)
        ->set('search', 'Alice')
        ->assertSee('Alice Draft')
        ->assertDontSee('My Draft Builder')
        ->set('search', 'Draft Builder')
        ->assertSee('My Draft Builder')
        ->assertDontSee('Alice Draft');
});

test('admin can delete a quote builder draft', function () {
    expect(QuoteBuilder::where('id', $this->quoteBuilder->id)->exists())->toBeTrue();

    Livewire::actingAs($this->adminUser)
        ->test(Index::class)
        ->call('delete', $this->quoteBuilder->id);

    expect(QuoteBuilder::where('id', $this->quoteBuilder->id)->exists())->toBeFalse();
});

test('authorized admin can view quote builder details and masquerade link', function () {
    $this->actingAs($this->adminUser)
        ->get(route('admin.quote-builders.show', $this->quoteBuilder))
        ->assertStatus(200);

    Livewire::actingAs($this->adminUser)
        ->test(Show::class, ['quoteBuilder' => $this->quoteBuilder])
        ->assertSee('My Draft Builder')
        ->assertSee('John Doe')
        ->assertSee('Test Company')
        ->assertSee('Test Product')
        ->assertSee('300.00') // Subtotal of 100 * 3
        ->assertSee('Login as User')
        ->set('showLoginModal', true)
        ->assertSee('Important: Use Incognito Mode')
        ->assertSee('admin/masquerade/'.$this->customerUser->id);
});
