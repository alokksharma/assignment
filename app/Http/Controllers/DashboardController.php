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
        if (Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Tenant')) {
            $languagesCount = Language::query()->count();
            $countriesCount = Country::query()->count();
            $questionsCount = Question::query()->count();
            //$surveysCount = Survey::query()->count();
            $surveysCount = Survey::selectRaw("
                                COUNT(*) as total,
                                SUM(status = 'Live') as live,
                                SUM(status = 'Pause') as pause,
                                SUM(status = 'Test') as test
                            ")->first();
            $totalAssignProject = VendorProjectAssign::query()->count();
            $totalVendorAssign = VendorProjectAssign::query()->distinct('vendor_id')->count('vendor_id');
        } elseif (Auth::user()->hasRole('Vendor')) {
          //  $projectCount = VendorProjectAssign::select('id', 'vendor_id')->where('vendor_id', Auth::user()->vendor->id)->count();
            $completeProjectCount = Proof::query()->where('vendor_id', Auth::user()->vendor->id)->where('status', 1)->count();

            $projectCount->where('vendor_id', Auth::user()->vendor->id);
        }
        $projectCount = $projectCount->selectRaw("
                COUNT(*) as total_count,
                SUM(status = 1) as complete_count,
                SUM(status = 2) as terminate_count,
                SUM(status = 3) as quotafull_count,
                SUM(status = 4) as quality_count
            ")
            ->first();
          //  dd($projectCount);
        return view('dashboard', compact('questionsCount', 'languagesCount', 'countriesCount', 'surveysCount','projectCount', 'completeProjectCount', 'totalVendorAssign', 'totalAssignProject'));
    }
}
