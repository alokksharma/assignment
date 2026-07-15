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
        $member = Role::firstOrCreate(['name' => 'Member']);
        $vendor = Role::firstOrCreate(['name' => 'Vendor']);

        // Example permissions (add more as needed)
        $permissions = [
            'generate api',
            'invite admin',
            'invite member',
            'invite vendor',
            'create surveys',
            'view surveys',
            'view company surveys',
            'view own surveys',
            'view all surves',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign permissions to roles
        $superAdmin->givePermissionTo(['invite admin', 'view all surves', 'generate api']);
        $admin->givePermissionTo(['invite admin', 'invite member', 'create short url', 'view company urls']);
        $member->givePermissionTo(['create surveys', 'view own surveys', 'view own urls']);
        $vendor->givePermissionTo(['view own surveys', 'view company surveys']);

        // Create SuperAdmin user
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $user->assignRole('SuperAdmin');
    }
}
