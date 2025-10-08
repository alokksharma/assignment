<?php

namespace Tests\Feature;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;

class UserListTest extends TestCase
{
    //use RefreshDatabase;

    /** @test */
    public function superadmin_and_admin_can_access_user_list()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('SuperAdmin');  
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $member = User::factory()->create();
        $member->assignRole('Member');
        $response = $this->actingAs($superAdmin)->get(route('companies.invite-list'));
        $response->assertStatus(200);
        $response = $this->actingAs($admin)->get(route('companies.invite-list'));
        $response->assertStatus(200);
        $response = $this->actingAs($member)->get(route('companies.invite-list'));
        $response->assertStatus(403); // Assuming members cannot access the user list

    }
}
