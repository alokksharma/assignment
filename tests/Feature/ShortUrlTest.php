<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ShortUrl;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    //use RefreshDatabase;

    // public function setUp(): void
    // {
    //     parent::setUp();
    //     Role::create(['name' => 'SuperAdmin']);
    //     Role::create(['name' => 'Admin']);
    //     Role::create(['name' => 'Member']);
    // }

    /** @test */
    public function admin_can_create_short_url()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->post(route('short_urls.store'), [
            'original_url' => 'https://example-' . uniqid() . '.com',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function member_can_create_short_url()
    {
        $member = User::factory()->create();
        $member->assignRole('Member');

        $response = $this->actingAs($member)->post(route('short_urls.store'), [
            'original_url' => 'https://example-' . uniqid() . '.com',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function short_url_redirects_to_original_url()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $uniqueUrl = 'https://example-' . uniqid() . '.com';
        $uniqueCode = 'code' . uniqid();
        $shortUrl = ShortUrl::create([
            'original_url' => $uniqueUrl,
            'short_code' => $uniqueCode,
            'user_id' => $admin->id,
        ]);

        $response = $this->get(route('short_urls.redirect', ['code' => $uniqueCode]));
        $response->assertRedirect($uniqueUrl);
    }
}
