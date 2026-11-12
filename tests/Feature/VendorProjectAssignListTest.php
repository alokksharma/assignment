<?php

namespace Tests\Feature;

use App\Models\Survey;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorProjectAssign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VendorProjectAssignListTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_project_assign_list_filters_by_survey_status(): void
    {
        Role::firstOrCreate(['name' => 'SuperAdmin']);
        Permission::firstOrCreate(['name' => 'view-vendor-assign-projects']);

        $user = User::factory()->create();
        $user->assignRole('SuperAdmin');
        $user->givePermissionTo('view-vendor-assign-projects');

        $liveSurvey = Survey::create([
            'project_id' => 1001,
            'project_code' => 'LIVE-001',
            'project_name' => 'Live Survey',
            'status' => 'Live',
        ]);

        $pauseSurvey = Survey::create([
            'project_id' => 1002,
            'project_code' => 'PAUSE-001',
            'project_name' => 'Paused Survey',
            'status' => 'Pause',
        ]);

        $vendor = Vendor::create([
            'user_id' => $user->id,
            'currency' => 1,
        ]);

        VendorProjectAssign::create([
            'vendor_id' => $vendor->id,
            'survey_id' => $liveSurvey->id,
        ]);

        VendorProjectAssign::create([
            'vendor_id' => $vendor->id,
            'survey_id' => $pauseSurvey->id,
        ]);

        $response = $this->actingAs($user)->get('/survey-projects-assign/list?survey_status=Live');

        $response->assertOk();
        $response->assertSee($liveSurvey->project_name);
        $response->assertDontSee($pauseSurvey->project_name);
    }

    public function test_vendor_list_has_copy_mail_template_button(): void
    {
        Role::firstOrCreate(['name' => 'SuperAdmin']);
        Role::firstOrCreate(['name' => 'Vendor']);
        Permission::firstOrCreate(['name' => 'view-vendor']);

        $admin = User::factory()->create();
        $admin->assignRole('SuperAdmin');
        $admin->givePermissionTo('view-vendor');

        $vendorUser = User::factory()->create([
            'name' => 'Vendor Example',
            'email' => 'vendor@example.com',
        ]);
        $vendorUser->assignRole('Vendor');
        $vendorUser->vendor()->create([
            'vendor_id' => 'V-1001',
            'currency' => 1,
        ]);

        $response = $this->actingAs($admin)->get(route('vendors.index'));

        $response->assertOk();
        $response->assertSee('Copy Mail Template');
        $response->assertSee('Vendor Example');
    }
}
