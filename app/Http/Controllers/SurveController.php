<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\Project;
use App\Models\vendor;
use App\Models\SurveyQuestionOption;
use App\Models\VendorProjectAssign;
use Illuminate\Validation\Rule;
use DB;

class SurveController extends Controller
{

    public function index(Request $request)
    {

        $surveys = Survey::query()
        ->search(request('q'))
        ->with([
            'questions.options',
            'country:id,name',
            'language:id,name',
        ])
        ->paginate(20);
        return view('masters.surveys', compact('surveys'));
    }

    public function projects(Request $request)
    {

        $surveys = Survey::query()
        ->search(request('q'))
        ->with([
            'questions.options',
            'country:id,name',
            'language:id,name',
        ])
        ->paginate(20);
        $project = Project::where('name', '3AS_API')->first();
        return view('masters.survey_project', compact('surveys','project'));
    }

    public function syncSurve()
    {
        $url = config('marketxcel.base_url') . '/send_supplier_data';
        $response = Http::withHeaders([
            'Supplierid' => config('marketxcel.supplier_id'),
            'Token' => config('marketxcel.supplier_token'),
        ])->get($url);
        if (!$response->successful()) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch Survey data.',
            ], 300);
        }
        $surve = collect($response->json('Data'));
        $surve->chunk(500)->each(function ($chunk) {
            foreach ($chunk as $item) {

                DB::transaction(function () use ($item) {

                    $survey = Survey::updateOrCreate(
                        ['project_id' => $item['project_id']],
                        [
                            'project_code'         => $item['project_code'],
                            'project_name'         => $item['project_name'],
                            'country_id'           => $item['country_id'],
                            'language_id'          => $item['language_id'],
                            'category'             => $item['category'],
                            'currency'             => $item['currency'],
                            'audience_type'        => $item['audience_type'],
                            'pii_collection'       => $item['pii_collection'],
                            'application_download' => $item['application_download'],
                            'facial_coding'        => $item['facial_coding'],
                            'project_ir'           => $item['project_ir'],
                            'project_loi'          => $item['project_loi'],
                            'cpi'                  => $item['cpi'],
                            'quota'                => $item['quota'],
                            'live_click_quota'     => $item['live_click_quota'],
                            'test_click_quota'     => $item['test_click_quota'],
                            'project_start_date'   => $item['project_start_date'],
                            'project_end_date'     => $item['project_end_date'],
                            'live_url'             => $item['live_url'],
                            'test_url'             => $item['test_url'],
                            'status'               => $item['status'],
                            'device_type'          => $item['device_type'],
                            'target_spec'          => $item['target_spec'],
                        ]
                    );

                    $data = $item['qualifications'] ?? [];

                    // No questions available
                    if (empty($data['question'])) {
                        return;
                    }

                    foreach ($data['question'] as $questionId) {

                        $surveyQuestion = SurveyQuestion::updateOrCreate(
                            [
                                'survey_id'   => $survey->id,
                                'question_id' => $questionId,
                            ]
                        );

                        $optionsData = $data['option'][$questionId] ?? [];

                        // Delete options that are no longer available
                        if (!empty($optionsData)) {
                            SurveyQuestionOption::where(
                                'question_id',
                                $questionId
                            )
                            ->whereNotIn('option', $optionsData)
                            ->delete();

                        } else {

                            // Remove all old options if no options exist
                            SurveyQuestionOption::where(
                                'question_id',
                                $questionId
                            )->delete();

                            continue;
                        }

                        $options = [];

                        foreach ($optionsData as $optionCode) {

                            $options[] = [
                                'question_id'        => $questionId,
                                'option'        => $optionCode,
                                'option_text'        => $data['option_text'][$questionId][$optionCode] ?? '',
                                'option_quota'       => $data['option_quota'][$questionId][$optionCode] ?? 0,
                            ];
                        }

                        if (!empty($options)) {
                            SurveyQuestionOption::upsert(
                                $options,
                                ['question_id', 'option'],
                                ['option_text', 'option_quota']
                            );
                        }
                    }
                });
            }
        });

        return response()->json([
            'status'  => true,
            'message' => 'Survey synced successfully.',
            'data'    => Survey::all(),
        ]);
    }

    public function vendorProjectAssign($id)
    {
        // $assignedVendorIds = VendorProjectAssign::where('survey_id', $id)->pluck('vendor_id');
        // $vendors = Vendor::query()->whereNotIn('id', $assignedVendorIds)->take(10)->get();
         return view('masters.vendor-project-assign', compact('id'));
    }

    public function searchVendors(Request $request)
    {
        $surveyId = $request->survey_id;

        $assignedVendorIds = VendorProjectAssign::where('survey_id', $surveyId)
            ->pluck('vendor_id');

        $vendors = Vendor::query()
            ->whereNotIn('id', $assignedVendorIds)
            //->where('status', 1)
            ->when($request->search, function ($query) use ($request) {
                $query->where('vendor_id', 'like', '%' . $request->search . '%');
            })
           // ->orderBy('vendor_id', 'asc')
            ->paginate(10);

        return response()->json([
            'results' => $vendors->map(function ($vendor) {
                return [
                    'id'   => $vendor->id,
                    'text' => $vendor->vendor_id,
                ];
            }),

            'pagination' => [
                'more' => $vendors->hasMorePages()
            ]
        ]);
    }

    public function vendorProjectAssignList()
    {
        $vendor_projects = VendorProjectAssign::query()->with(['vendor.user','survey'])->paginate(10);
         return view('masters.vendor-project-assign-list', compact('vendor_projects'));

    }

    public function vendorProjectAssignStore(Request $request, $id)
    {
            $data = $request->validate([
                    'vendor_id' => [
                        'required',
                        'exists:vendors,id',
                        Rule::unique('vendor_project_assigns')
                            ->where(function ($query) use ($id) {
                                return $query->where('survey_id', $id);
                            }),
                    ],
                    'temp_complete' => ['required','string', 'max:255'],
                    'temp_terminate' => ['required','string', 'max:255'],
                    'temp_quotafull' => ['required','string', 'max:255'],
                    'temp_speedster' => ['required','string', 'max:255'],
                    'temp_cpi' => [
                                'required',
                                'numeric',
                                'decimal:0,2',
                                'max:9999.99',
                            ],
                ]);
           $vendor = Vendor::query()->findorfail($request->vendor_id);
            $data['survey_id'] = $id;
            $data['project_vendor_id'] =  $vendor->vendor_id.'_'.$id;
            VendorProjectAssign::create($data);

        return redirect()->route('vendor.project.assign.list')->with('success', 'Project assigned successfully.');
    }
}
