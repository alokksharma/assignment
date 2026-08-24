<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }


    /** @test */
    public function admin_can_create_short_url()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
       // dd($admin);
        $originalUrl = 'https://adminexample-' . uniqid() . '.com';
        $response = $this->actingAs($admin)->post(route('short_urls.store'), [
            'original_url' => $originalUrl ,
        ]);
        
        // correct redirection
        $shortUrl = ShortUrl::where('original_url', $originalUrl)->first();
        
        $response->assertRedirect(route('short_urls.index', $shortUrl));
        // Assert session flash and no errors
      //  dd($shortUrl);
        $response->assertSessionHas('success', 'Short URL created!');
         $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        // Assert: record exists in the DB
        $this->assertDatabaseHas('short_urls', [
            'original_url' => $originalUrl,
            'user_id' => $admin->id,
        ]);
       // $response->assertStatus(200);
    }

    /** @test */
    public function superadmin_can_not_create_short_url()
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('SuperAdmin');

        $response = $this->actingAs($superadmin)->post(route('short_urls.store'), [
            'original_url' => 'https://superexample-' . uniqid() . '.com',
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function member_can_create_short_url()
    {
        $member = User::factory()->create();
        $member->assignRole('Member');
        $originalUrl = 'https://example1-' . uniqid() . '.com';
        $response = $this->actingAs($member)->post(route('short_urls.store'), [
            'original_url' => $originalUrl,
        ]);
        $shortUrl = ShortUrl::where('original_url', $originalUrl)->first();
        $response->assertRedirect(route('short_urls.index', $shortUrl));
        // Assert session flash and no errors
        $response->assertSessionHas('success', 'Short URL created!');
         $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        // Assert: record exists in the DB
        $this->assertDatabaseHas('short_urls', [
            'original_url' => $originalUrl,
            'user_id' => $member->id,
        ]);
    }

    public function short_url_list_insert(){
       
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        ShortUrl::factory()->count(15)->create([
            'user_id' => $admin->id
        ]);
         $response->assertStatus(200);
    }

    /** test */
    public function invalid_code_does_not_redirect_and_shows_error()
    {
        //dd('sembark');
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $uniqueUrl = 'https://example-'.uniquid().'.com';
        $uniqueCode = 'code' . uniquid();
        $shortUrl = ShortUrl::create([
            'orignal_url' => $uniqueUrl,
            'short_code' => $uniqueCode,
            'user_id' => $admin->id,
        ]);
        $response = $this->get(route('short_urls.redirect', ['code' => 'invalidcode']));
        $response->assertRedirect($uniqueUrl);

    }

    public function redirect(){
        $code = 'someCode';
        $shortUrl = ShortUrl::where('short_code', $code)->first();

        if (! $shortUrl) {
            abort(404); // or redirect()->route('some.fallback')
        }

        return redirect($shortUrl->original_url);
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

        ShortUrl::factory()->count(15)->create([
            'user_id' => $admin->id
        ]);

        $response = $this->get(route('short_urls.redirect', ['code' => $uniqueCode]));
        $response->assertRedirect($uniqueUrl);
    }

     

   
 

}



