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
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'users.view', 'users.create', 'users.edit', 'users.delete',

            // 1. General Settings
            'settings.view', 'settings.update',

            // 2. Testimonials
            'testimonials.view', 'testimonials.create', 'testimonials.edit', 'testimonials.delete',

            // 3. Newsletter Subscriptions
            'newsletter-subscriptions.view', 'newsletter-subscriptions.delete',

            // 4. News Categories
            'news-categories.view', 'news-categories.create', 'news-categories.edit', 'news-categories.delete',

            // 5. News
            'news.view', 'news.create', 'news.edit', 'news.delete',

            // 6. Services
            'services.view', 'services.create', 'services.edit', 'services.delete',

            // 7. Products
            'products.view', 'products.create', 'products.edit', 'products.delete',

            // 8. Contact Submissions
            'contact-submissions.view', 'contact-submissions.delete',

            // 9. Our Team
            'our-team.view', 'our-team.create', 'our-team.edit', 'our-team.delete',
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
            'news-categories.view'
        ]);
    }
}