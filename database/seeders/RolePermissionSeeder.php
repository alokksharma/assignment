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

        // Example permissions (add more as needed)
        $permissions = [
            'invite admin',
            'invite member',
            'create short url',
            'view all urls',
            'view company urls',
            'view own urls',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign permissions to roles
        $superAdmin->givePermissionTo(['invite admin', 'view all urls']);
        $admin->givePermissionTo(['invite admin', 'invite member', 'create short url', 'view company urls']);
        $member->givePermissionTo(['create short url', 'view own urls']);

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
