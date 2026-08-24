<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ShortUrl;
use App\Models\UrlHitCount;

class UrlHitCountTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }


    /** @test */
    public function url_hit_count_increments()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

                // Create a unique short URL for each test run
                $uniqueCode = 'test' . uniqid();
                $uniqueUrl = 'https://example.com/' . uniqid();
                $shortUrl = $this->createShortUrl($uniqueCode, $uniqueUrl, $admin);
                // Create UrlHitCount record with hit_count = 0
                UrlHitCount::create([
                        'short_url_id' => $shortUrl->id,
                        'hit_count' => 0,
                ]);

                // First hit
                $response1 = $this->get(route('short_urls.redirect', ['code' => $uniqueCode]));
                $response1->assertRedirect($uniqueUrl);
                $checkHitUrl = UrlHitCount::where('short_url_id', $shortUrl->id)->first();
                $this->assertNotNull($checkHitUrl);
                $this->assertEquals(1, $checkHitUrl->hit_count);

                // Second hit
                $response2 = $this->get(route('short_urls.redirect', ['code' => $uniqueCode]));
                $response2->assertRedirect($uniqueUrl);
                $checkHitUrl->refresh();
                $this->assertEquals(2, $checkHitUrl->hit_count);

                // Third hit
                $response3 = $this->get(route('short_urls.redirect', ['code' => $uniqueCode]));
                $response3->assertRedirect($uniqueUrl);
                $checkHitUrl->refresh();    
                $this->assertEquals(3, $checkHitUrl->hit_count);
     }

     private function createShortUrl($uniqueCode, $uniqueUrl, $admin){
        $shortUrl = ShortUrl::create([
            'original_url' => $uniqueUrl,
            'short_code' => $uniqueCode,
            'user_id' => $admin->id,
        ]);
        return $shortUrl;
     }

    
}
