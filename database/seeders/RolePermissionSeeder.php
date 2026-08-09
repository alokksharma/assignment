<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $tenant = Role::firstOrCreate(['name' => 'Tenant']);

        // Example permissions (add more as needed)
        $permissions = [
            'create-role',
            'edit-role',
            'destroy-role',
            'show-role',
            'generate-api',
            'view-user',
            'create-user',
            'edit-user',
            'destroy-user',
            'create-vendor',
            'view-vendor',
            'delete-vendor',
            'edit-vendor',
            'view-surveys',
            'view-surveys-projects',
            'view-languages',
            'view-countries',
            'view-qualififcations',
            'delete-vendor-assign-projects',
            'view-vendor-assign-projects',
            'create-vendor-assign-projects',
            'data-reconcile-create',
            'report-export',
            'report-view',
            'dashboard'
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
        // Delete permissions that no longer exist in the array
        Permission::whereNotIn('name', $permissions)->delete();
        // Assign permissions to roles
        $superAdmin->givePermissionTo($permissions);
        $tenant->givePermissionTo([
            'dashboard',
            'create-role',
            'edit-role',
            'destroy-role',
            'show-role',
        ]);
        // Create SuperAdmin user
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $user->assignRole('SuperAdmin');

        $tenatUser = User::firstOrCreate(
            ['email' => 'tenant@example.com'],
            [
                'name' => 'Tenant Admin',
                'password' => bcrypt('password'),
            ]
        );
        $tenatUser->assignRole('Tenant');
    }
}
