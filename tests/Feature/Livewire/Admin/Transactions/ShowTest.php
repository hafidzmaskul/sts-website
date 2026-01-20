<?php

namespace Tests\Feature\Livewire\Admin\Transactions;

use App\Livewire\Admin\Transactions\Show;
use App\Models\Coupon;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'trade account', 'guard_name' => 'web']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_renders_successfully()
    {
        $user = User::factory()->create();
        $user->customer()->create();

        $transaction = Transaction::create([
            'invoice_code' => 'INV-TEST-001',
            'customer_id' => $user->customer->id,
            'subtotal' => 100,
            'tax_amount' => 10,
            'total_amount' => 110,
            'status' => 'pending',
            'contact_email' => 'test@example.com',
            'shipping_first_name' => 'Test',
            'shipping_last_name' => 'User',
            'shipping_address' => '123 St',
            'shipping_city' => 'City',
            'shipping_postal_code' => '12345',
            'shipping_country' => 'Country',
            'shipping_phone_number' => '1234567890',
            'shipping_method' => 'Standard',
            'shipping_price' => 0,
            'payment_method' => 'Card',
        ]);

        Livewire::actingAs($this->admin)
            ->test(Show::class, ['transaction' => $transaction])
            ->assertStatus(200);
    }

    public function test_displays_coupon_info_if_exists()
    {
        $user = User::factory()->create();
        $user->customer()->create();

        $coupon = Coupon::create([
            'name' => 'Test Coupon',
            'code' => 'DISCOUNT10',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
        ]);

        $transaction = Transaction::create([
            'invoice_code' => 'INV-TEST-002',
            'customer_id' => $user->customer->id,
            'subtotal' => 100,
            'discount_amount' => 10,
            'subtotal_after_discount' => 90, // Virtual logic
            'tax_amount' => 9,
            'total_amount' => 99,
            'status' => 'pending',
            'contact_email' => 'test@example.com',
            'shipping_first_name' => 'Test',
            'shipping_last_name' => 'User',
            'shipping_address' => '123 St',
            'shipping_city' => 'City',
            'shipping_postal_code' => '12345',
            'shipping_country' => 'Country',
            'shipping_phone_number' => '1234567890',
            'shipping_method' => 'Standard',
            'shipping_price' => 0,
            'payment_method' => 'Card',
            'coupon_id' => $coupon->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(Show::class, ['transaction' => $transaction])
            ->assertStatus(200)
            ->assertSee('Discount')
            ->assertSee('DISCOUNT10')
            ->assertSee('-£10.00');
    }
}
