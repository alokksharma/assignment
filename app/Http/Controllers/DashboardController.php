<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Language;
use App\Models\Country;
use App\Models\Question;
use App\Models\Survey;
use App\Models\VendorProjectAssign;
use App\Models\Proof;
use App\Models\Project;

class DashboardController extends Controller
{
    public function dashboard(Request $request): View
    {
        $languagesCount = 0;
        $countriesCount = 0;
        $questionsCount = 0;
        $surveysCount = 0;
        $projectCount = 0;
        $completeProjectCount = 0;
        $totalVendorAssign = 0;
        $totalAssignProject = 0;
        $projectCount = Proof::query();
        $vendorProjectAssignQuery = VendorProjectAssign::query();
        $suppliers = Project::where('status', 1)->get();
        if (Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('Admin')) {
            $languagesCount = Language::query()->count();
            $countriesCount = Country::query()->count();
            $questionsCount = Question::query()->count();
            $surveysCount = Survey::selectRaw("
                                COUNT(*) as total,
                                SUM(status = 'Live') as live,
                                SUM(status = 'Pause') as pause,
                                SUM(status = 'Test') as test
                            ")->first();
            $totalVendorAssign = VendorProjectAssign::query()->distinct('vendor_id')->count('vendor_id');
        } elseif (Auth::user()->hasRole('Vendor')) {
            $completeProjectCount = Proof::query()->where('vendor_id', Auth::user()->vendor->id)->where('status', 1)->count();
            $projectCount->where('vendor_id', Auth::user()->vendor->id);
            $vendorProjectAssignQuery = $vendorProjectAssignQuery->where('vendor_id', Auth::user()->vendor->id);
        }
        $totalAssignProject = $vendorProjectAssignQuery->count();

        $projectCount = $projectCount->selectRaw("
                COUNT(*) as total_count,
                SUM(status = 0) as in_progress_count,
                SUM(status = 1) as complete_count,
                SUM(status = 2) as terminate_count,
                SUM(status = 3) as quotafull_count,
                SUM(status = 4) as quality_count
            ")
            ->first();
        return view('dashboard', compact('questionsCount', 'languagesCount', 'countriesCount', 'surveysCount','projectCount', 'completeProjectCount', 'totalVendorAssign', 'totalAssignProject', 'suppliers'));
    }
}
