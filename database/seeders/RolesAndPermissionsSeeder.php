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

        // Create permissions
        $perms = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'posts.view',
            'posts.create',
            'posts.edit',
            'posts.delete',
            // add to $perms list:
'roles.view','roles.create','roles.edit','roles.delete',
'users.view','users.create','users.edit','users.delete',

        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user  = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Give admin everything
        $admin->givePermissionTo(Permission::all());
        // Give user limited perms
        $user->givePermissionTo(['posts.view']);
    }
}
