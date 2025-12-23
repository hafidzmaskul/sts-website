<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app('cache')->forget('spatie.permission.cache');

        // Create Permissions
        $perms = [
            // User & Role Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // 1. General Settings
            'settings.view',
            'settings.update',

            // 2. Testimonials
            'testimonials.view',
            'testimonials.create',
            'testimonials.edit',
            'testimonials.delete',

            // 3. Newsletter Subscriptions
            'newsletter-subscriptions.view',
            'newsletter-subscriptions.delete',

            // 4. News Categories
            'news-categories.view',
            'news-categories.create',
            'news-categories.edit',
            'news-categories.delete',

            // 5. News
            'news.view',
            'news.create',
            'news.edit',
            'news.delete',

            // 6. Services
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',

            // 7. Products
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            // 7b. Product Categories
            'product-categories.view',
            'product-categories.create',
            'product-categories.edit',
            'product-categories.delete',

            // 8. Transactions (Sales)
            'transactions.view',

            // 9. Contact Submissions
            'contact-submissions.view',
            'contact-submissions.delete',

            // 10. Our Team
            'our-team.view',
            'our-team.create',
            'our-team.edit',
            'our-team.delete',

            // 10b. Customers
            'customers.view',
            'customers.edit',
            'customers.delete',

            // 10c. Brands
            'brands.view',
            'brands.create',
            'brands.edit',
            'brands.delete',


            // 11. Careers (New)
            'careers.view',
            'careers.create',
            'careers.edit',
            'careers.delete',

            // 12. Banners
            'banner.view',
            'banner.create',
            'banner.edit',
            'banner.delete',

            // 13. Quotes
            'quotes.view',
            'quotes.delete',
        ];

        // Create permissions
        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Create Roles
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        // Standard User Role (e.g., "Content Editor")
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->givePermissionTo([
            'news.view',
            'news.create',
            'news.edit',
            'news.delete',
            'news-categories.view',
            'careers.view', // Can view careers
        ]);

        // Customer Role
        $customer = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $customer->givePermissionTo([
            // Add basic customer permissions here if needed
        ]);

        // Fixed Roles requested by user
        $fixedRoles = [
            'guest',
            'trade account',
            'credit facilities account',
            'child'
        ];

        foreach ($fixedRoles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
    }
}