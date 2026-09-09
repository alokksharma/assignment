<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SurveyResource;
use App\Models\Language;
use App\Models\Country;
use App\Models\Question;
use App\Models\Partner;
use App\Models\Survey;

class PartnerApiController extends Controller
{
    public function parners()
    {
       $suppliers = Partner::all()->makeVisible('supplier_token');
        return response()->json([
            'status' => true,
            'Data'   => $suppliers,
        ]);
    }

    public function languages()
    {
       $languages = Language::query()->get();
        return response()->json([
            'status' => true,
            'Data'   => $languages,
        ]);
    }

    public function countries()
    {
       $languages = Country::query()->get();
        return response()->json([
            'status' => true,
            'Data'   => $languages,
        ]);
    }

    public function qualifications()
    {
       $languages = Question::query()->get();
        return response()->json([
            'status' => true,
            'Data'   => $languages,
        ]);
    }

    public function survey(){
         $surveys = Survey::query()
      //  ->select('project_id', 'project_code', 'project_name')
        ->search(request('q'))
        ->where('status', 'Live')
        // ->when(request('supplier_id'), function ($query, $supplierId) {
        //     $query->where('api_project_id', $supplierId);
        // })
        ->with([
            'questions.options',
            'country:id,name',
            'language:id,name',
            'supplier',
        ])
        ->get();
      //  $suppliers = Project::where('status', 1)->get();
         return response()->json([
            'status' => true,
            'Data'   => SurveyResource::collection($surveys),
        ]);
    }
}
