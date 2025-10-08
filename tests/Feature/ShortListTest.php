<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShortListTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function super_admin_admin_and_member_can_access_short_url_list()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('SuperAdmin');
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $member = User::factory()->create();
        $member->assignRole('Member');
        $response = $this->actingAs($superAdmin)->get('/short_urls');
        $response->assertStatus(200);
        $response = $this->actingAs($admin)->get('/short_urls');
        $response->assertStatus(200);
        $response = $this->actingAs($member)->get('/short_urls');
        $response->assertStatus(200);

        $response->assertStatus(403); // Assuming members cannot access the user list

    }
}
