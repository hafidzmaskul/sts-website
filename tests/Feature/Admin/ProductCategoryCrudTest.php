<?php

use App\Livewire\Admin\ProductCategories\Index;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure permissions exist
    Permission::firstOrCreate(['name' => 'product-categories.view']);
    Permission::firstOrCreate(['name' => 'product-categories.create']);
    Permission::firstOrCreate(['name' => 'product-categories.edit']);
    Permission::firstOrCreate(['name' => 'product-categories.delete']);
});

test('unauthorized users cannot view or manage product categories', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get(route('admin.product-categories.index'))
        ->assertForbidden();
});

test('authorized users can view product categories index', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $role->givePermissionTo('product-categories.view');
    $user->assignRole($role);

    $this->actingAs($user);

    $category = ProductCategory::create([
        'name' => 'Existing Category',
        'slug' => 'existing-category',
        'created_by' => $user->id,
        'is_parent' => true,
    ]);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertSee('Existing Category')
        ->assertSee('Yes'); // Is Parent Yes
});

test('creating a category sets is_parent to true and can save it', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $role->givePermissionTo(['product-categories.view', 'product-categories.create']);
    $user->assignRole($role);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('create')
        ->assertSet('is_parent', true)
        ->set('name', 'New Parent Category')
        ->set('slug', 'new-parent-category')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('product_categories', [
        'name' => 'New Parent Category',
        'slug' => 'new-parent-category',
        'is_parent' => true,
    ]);
});

test('editing a category loads and saves properties correctly', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $role->givePermissionTo(['product-categories.view', 'product-categories.edit']);
    $user->assignRole($role);

    $this->actingAs($user);

    $category = ProductCategory::create([
        'name' => 'Edit Category',
        'slug' => 'edit-category',
        'created_by' => $user->id,
        'is_parent' => true,
    ]);

    Livewire::test(Index::class)
        ->call('edit', $category->id)
        ->assertSet('is_parent', true)
        ->set('name', 'Updated Name')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('product_categories', [
        'id' => $category->id,
        'name' => 'Updated Name',
        'is_parent' => true,
    ]);
});

test('deleting a category works correctly', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $role->givePermissionTo(['product-categories.view', 'product-categories.delete']);
    $user->assignRole($role);

    $this->actingAs($user);

    $category = ProductCategory::create([
        'name' => 'Delete Me',
        'slug' => 'delete-me',
        'created_by' => $user->id,
    ]);

    Livewire::test(Index::class)
        ->call('delete', $category->id)
        ->assertDispatched('notify');

    $this->assertDatabaseMissing('product_categories', [
        'id' => $category->id,
    ]);
});

test('clicking + Sub Category button sets parent_id and sets is_parent to false', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $role->givePermissionTo(['product-categories.view', 'product-categories.create']);
    $user->assignRole($role);

    $this->actingAs($user);

    $parentCategory = ProductCategory::create([
        'name' => 'Parent Category',
        'slug' => 'parent-category',
        'created_by' => $user->id,
        'is_parent' => true,
    ]);

    Livewire::test(Index::class)
        ->call('createSubCategory', $parentCategory->id)
        ->assertSet('parent_id', $parentCategory->id)
        ->assertSet('is_parent', false)
        ->set('name', 'Child Category')
        ->set('slug', 'child-category')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('product_categories', [
        'name' => 'Child Category',
        'slug' => 'child-category',
        'parent_id' => $parentCategory->id,
        'is_parent' => false,
    ]);
});

test('tree view renders parent categories and their children with visual branch indicator', function () {
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'admin']);
    $role->givePermissionTo('product-categories.view');
    $user->assignRole($role);

    $this->actingAs($user);

    $parentCategory = ProductCategory::create([
        'name' => 'Parent Group',
        'slug' => 'parent-group',
        'created_by' => $user->id,
        'is_parent' => true,
    ]);

    $childCategory = ProductCategory::create([
        'name' => 'Child Group',
        'slug' => 'child-group',
        'parent_id' => $parentCategory->id,
        'created_by' => $user->id,
        'is_parent' => false,
    ]);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertSee('Parent Group')
        ->assertSee('Child Group')
        ->assertSee('└───');
});
