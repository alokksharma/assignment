<?php

namespace Tests;

use App\Models\User;
use App\Models\Company;

trait TestHelper
{

    public function createTestRoles($role, $user, $userEmail)
    {
       
        if ($role === 'SuperAdmin') {
            $data = [
                'name' => $role. uniqid(),
                'email' => $userEmail,
                'company_name' => 'New Company Inc '. uniqid(),
            ];
        } else {
            $data = [
                'name' => $role. uniqid(),
                'email' => $userEmail,
                'role' => $role,
            ];
        }
        $response = $this->actingAs($user)->post(route('companies.invite-admin.store'),$data);
        
        return $response;
    }

    public function createUniqueEmail(string $prefix = 'user')
    {
        return $prefix . uniqid() . '@example.com';
    }
}
