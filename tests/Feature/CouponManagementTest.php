<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CouponManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup admin user
        $this->admin = User::factory()->create(['name' => 'Admin User']);
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $this->admin->assignRole($role);
    }

    public function test_can_view_coupons_index_page()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.coupons.index'));

        $response->assertStatus(200);
        $response->assertSee('Coupons');
    }

    public function test_can_create_coupon_with_fixed_discount_and_no_restriction()
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\Admin\Coupons\Create::class)
            ->set('name', 'Test Coupon')
            ->set('code', 'TESTCODE')
            ->set('type', 'redeem')
            ->set('status', 'published')
            ->set('discount_type', 'fixed')
            ->set('discount_value', 10.00)
            ->set('restriction_type', 'none')
            ->call('save')
            ->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'name' => 'Test Coupon',
            'code' => 'TESTCODE',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10.00,
            'restriction_type' => null,
        ]);
    }

    public function test_can_create_coupon_with_role_restriction()
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\Admin\Coupons\Create::class)
            ->set('name', 'Trade Coupon')
            ->set('code', 'TRADE10')
            ->set('type', 'redeem')
            ->set('status', 'published')
            ->set('discount_type', 'percentage')
            ->set('discount_value', 10)
            ->set('restriction_type', 'role')
            ->set('role_level', 'trade account')
            ->call('save');

        $this->assertDatabaseHas('coupons', [
            'name' => 'Trade Coupon',
            'role_level' => 'trade account',
            'restriction_type' => 'role',
        ]);
    }

    public function test_can_create_coupon_with_specific_user_restriction()
    {
        $this->actingAs($this->admin);
        $user = User::factory()->create();

        Livewire::test(\App\Livewire\Admin\Coupons\Create::class)
            ->set('name', 'User Coupon')
            ->set('code', 'USER10')
            ->set('type', 'redeem')
            ->set('status', 'published')
            ->set('discount_type', 'percentage')
            ->set('discount_value', 10)
            ->set('restriction_type', 'specific_user')
            ->set('specific_users', [$user->id])
            ->call('save');

        $coupon = Coupon::where('code', 'USER10')->first();
        $this->assertNotNull($coupon);
        $this->assertEquals('specific_user', $coupon->restriction_type);
        $this->assertTrue($coupon->users->contains($user));
    }

    public function test_cannot_create_duplicate_coupon_code()
    {
        $this->actingAs($this->admin);
        Coupon::create([
            'name' => 'Existing',
            'code' => 'EXISTING',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 5,
        ]);

        Livewire::test(\App\Livewire\Admin\Coupons\Create::class)
            ->set('name', 'New Coupon')
            ->set('code', 'EXISTING')
            ->set('type', 'redeem')
            ->set('status', 'published')
            ->set('discount_type', 'fixed')
            ->set('discount_value', 10)
            ->call('save')
            ->assertHasErrors(['code']);
    }

    public function test_can_delete_coupon()
    {
        $this->actingAs($this->admin);
        $coupon = Coupon::create([
            'name' => 'To Delete',
            'code' => 'DELETE',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 5,
        ]);

        Livewire::test(\App\Livewire\Admin\Coupons\Index::class)
            ->call('delete', $coupon->id);

        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }

    public function test_can_mount_edit_coupon_page_with_specific_user_restriction()
    {
        $this->actingAs($this->admin);
        $user = User::factory()->create();
        $coupon = Coupon::create([
            'name' => 'Specific User Coupon',
            'code' => 'SPECUSER',
            'type' => 'redeem',
            'status' => 'published',
            'discount_type' => 'fixed',
            'discount_value' => 10,
            'restriction_type' => 'specific_user',
        ]);
        $coupon->users()->attach($user->id);

        // This should trigger the ambiguous ID error if plain pluck('id') is used
        Livewire::test(\App\Livewire\Admin\Coupons\Edit::class, ['coupon' => $coupon])
            ->assertSet('restriction_type', 'specific_user')
            ->assertSet('specific_users', [$user->id]);
    }

    public function test_auto_generates_code_for_claim_type_if_empty()
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\Admin\Coupons\Create::class)
            ->set('name', 'Claim Coupon')
            ->set('code', '') // Empty code
            ->set('type', 'claim') // Type claim
            ->set('status', 'published')
            ->set('discount_type', 'fixed')
            ->set('discount_value', 10)
            ->set('restriction_type', 'none')
            ->call('save')
            ->assertHasNoErrors(['code']); // Should not have validation error

        $this->assertDatabaseHas('coupons', [
            'name' => 'Claim Coupon',
            'type' => 'claim',
        ]);
    }

    public function test_can_create_coupon_with_quota()
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\Admin\Coupons\Create::class)
            ->set('name', 'Limited Coupon')
            ->set('code', 'LIMIT5')
            ->set('type', 'redeem')
            ->set('status', 'published')
            ->set('quota', 5)
            ->set('discount_type', 'fixed')
            ->set('discount_value', 10)
            ->set('restriction_type', 'none')
            ->call('save')
            ->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'name' => 'Limited Coupon',
            'code' => 'LIMIT5',
            'quota' => 5,
            'used_count' => 0,
        ]);
    }
}
