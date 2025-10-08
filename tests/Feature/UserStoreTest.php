<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use App\Models\Invite;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserStoreTest extends TestCase
{
   

    /** @test */
    public function superadmin_can_create_user_and_company()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('SuperAdmin');
        $userEmail = 'user' . uniqid() . '@example.com';
        $response = $this->actingAs($superAdmin)->post(route('companies.invite-admin.store'),[
            'name' => 'User One'.uniqid(),
            'email' => $userEmail,
            'company_name' => 'New Company Inc',
            'role' => 'Admin',
        ]);

        $response->assertRedirect(route('companies.invite-list'));

        // $this->assertDatabaseHas('companies', ['name' => 'New Company Inc']);
        // $this->assertDatabaseHas('users', ['email' => 'user1@example.com']);
        $createdUser = User::where('email', $userEmail)->first();
        $this->assertDatabaseHas('invites', [
            'user_id' => $createdUser->id,
            'invite_id' => $superAdmin->id,
        ]);

        $this->assertTrue($createdUser->hasRole('Admin'));
    }

    /** @test */
    public function admin_can_create_admin_and_member_with_own_company()
    {
        // Step 1: Create a company
        $company = Company::create(['name' => 'Admin Co'. uniqid()]);

        // Step 2: Create an Admin user assigned to that company
        $admin = User::factory()->create([
            'company_id' => $company->id,
        ]);
        $admin->assignRole('Admin');

        // Step 3: Acting as admin, create a new user with role 'Admin'
        $adminUserEmail = 'adminuser' . uniqid() . '@example.com';
        $responseAdmin = $this->actingAs($admin)->post(route('companies.invite-admin.store'),[
            'name' => 'Admin User'. uniqid(),
            'email' => $adminUserEmail,
            'role' => 'Admin',
        ]);

        $responseAdmin->assertRedirect(route('companies.invite-list'));

        $adminUser = User::where('email', $adminUserEmail)->first();
        $this->assertNotNull($adminUser);
        $this->assertTrue($adminUser->hasRole('Admin'));
        $this->assertEquals($admin->company_id, $adminUser->company_id);

        // Step 4: Acting as admin again, create a new user with role 'Member'
        $memberUserEmail = 'memberuser' . uniqid() . '@example.com';
        $responseMember = $this->actingAs($admin)->post(route('companies.invite-admin.store'), [
            'name' => 'Member User'. uniqid(),
            'email' => $memberUserEmail,
            'role' => 'Member',
        ]);

        $responseMember->assertRedirect(route('companies.invite-list'));

        $memberUser = User::where('email', $memberUserEmail)->first();
        $this->assertNotNull($memberUser);
        $this->assertTrue($memberUser->hasRole('Member'));
        $this->assertEquals($admin->company_id, $memberUser->company_id);
    }

    //check validation for user creation
    /** @test */
    public function user_creation_requires_valid_data()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('SuperAdmin');
        $response = $this->actingAs($superAdmin)->post(route('companies.invite-admin.store'), [
            'name' => '', // Missing name
            'email' => 'invalid-email', // Invalid email format
            'company_name' => '', // Missing company name
            'role' => 'InvalidRole', // Invalid role
        ]);
        $response->assertSessionHasErrors(['name', 'email', 'company_name']);   

        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $response = $this->actingAs($admin)->post(route('companies.invite-admin.store'), [
            'name' => '', // Missing name
            'email' => 'invalid-email', // Invalid email format
            'role' => 'InvalidRole', // Invalid role
        ]);
        $response->assertSessionHasErrors(['name', 'email', 'role']);   

    }


}
