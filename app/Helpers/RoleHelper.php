<?php
namespace App\Helpers;

use Spatie\Permission\Models\Role;

class RoleHelper
{
    public static function getRegisterableRoles()
    {
        // Only allow Member and Admin for registration (not SuperAdmin)
        return Role::whereIn('name', ['Member', 'Admin'])->pluck('name', 'id');
    }
}
