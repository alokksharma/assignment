<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\Project;
use App\Models\vendor;
use App\Models\Country;
use App\Models\Language;
use App\Models\SurveyQuestionOption;
use App\Models\VendorProjectAssign;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\{DB, Auth};
use Illuminate\Pagination\LengthAwarePaginator;

class SurveController extends Controller
{

    public function index(Request $request)
    {

        $surveys = Survey::query()
        ->search(request('q'))
        ->when(request('survey_status'), function ($query, $status) {
            $query->where('status', $status);
        })
        ->when(request('supplier_id'), function ($query, $supplierId) {
            $query->where('api_project_id', $supplierId);
        })
        ->with([
            'questions.options',
            'surveyQuestions.surveyOption',
            'surveyQuestions.question',
            'surveyQuotaQualifications',
            'country:id,name',
            'language:id,name',
            'supplier',
           'demographics' => function ($query) {
                $query->select([
                    'demographics.id',
                    'demographics.world_language_id',
                    'demographics.demographic_name',
                    'demographics.question_id',
                    'demographics.question_text',
                    'demographics.question_type',
                    'demographics.answer_codes',
                ]);
            },
        ])
        ->orderBy('cpi', 'desc')
        ->paginate(20);
        $suppliers = Project::where('status', 1)->get();
        return view('masters.surveys', compact('surveys', 'suppliers'));
    }

    public function projects(Request $request)
    {
        $surveys = Survey::query()
        ->search(request('q'))
        ->when($request->survey_status, function ($query, $status) {
            $query->where('status', $status);
        })
        ->when($request->supplier_id, function ($query, $supplierId) {
            $query->where('api_project_id', $supplierId);
        })
        ->with([
            'questions.options',
             'surveyQuestions.surveyOption',
            'surveyQuestions.question',
            'surveyQuotaQualifications',
            'country:id,name',
            'language:id,name',
             'demographics' => function ($query) {
                $query->select([
                    'demographics.id',
                    'demographics.world_language_id',
                    'demographics.demographic_name',
                    'demographics.question_id',
                    'demographics.question_text',
                    'demographics.question_type',
                    'demographics.answer_codes',
                ]);
            },
        ])
        ->orderBy('cpi', 'desc')
        ->paginate(20);
        $suppliers = Project::get();
        return view('masters.survey_project', compact('surveys','suppliers'));
    }

   public function syncSurve(Request $request)
    {
        try {
            $url = config('marketxcel.base_url') . '/send_supplier_data';
            $supplier_id = config('marketxcel.supplier_id');
            $supplier_token = config('marketxcel.supplier_token');
            $supplier = $request->supplier_id ? Project::findOrFail($request->supplier_id) : null;
            $url            = $supplier ? $supplier->url . '/send_supplier_data' : config('marketxcel.base_url') . '/send_supplier_data';
            $supplierId     = $supplier ? $supplier->supplier_id : config('marketxcel.supplier_id');
            $supplierToken  = $supplier ? $supplier->supplier_token : config('marketxcel.supplier_token');
            $apiProjectPk   = $supplier ? $supplier->id : null;

            $response = Http::withHeaders([
                'Supplierid' => $supplierId,
                'Token'      => $supplierToken,
            ])->get($url);

            if (!$response->successful()) {
                return view('survey.error_response', ['error' => 'Failed to fetch Survey data.']);
            }
            $allRows = collect($response->json('Data'));
            $requestCount = $allRows->count();
            $apiProjectIds = collect($allRows)->pluck('project_id')->filter()->unique();
            // --- pagination on the already-fetched collection ---
            $perPage    = (int) $request->input('per_page', 100);
            $page       = (int) $request->input('page', 1);
            // Preload lookups once instead of per-row queries
            $paginatedRows = new LengthAwarePaginator(
                $allRows->forPage($page, $perPage)->values(), // items for this page
                $allRows->count(),                            // total items
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            $rows = collect($paginatedRows->items());


            $countries = Country::pluck('id', 'name');
            $languages = Language::pluck('id', 'name');
            $rows->chunk(50)->each(function ($chunk) use ($supplier, $apiProjectPk, $countries, $languages){
                foreach ($chunk as $item) {
                    $survey = Survey::updateOrCreate(
                        [
                            'project_id' => $item['project_id'],
                            'api_project_id' =>  $apiProjectPk,
                        ],
                        [
                            'project_code'         => $item['project_code'],
                            'project_name'         => $item['project_name'],
                            'country_id'           => $countries[$item['country'] ?? ''] ?? null,
                            'language_id'          => $languages[$item['language'] ?? ''] ?? null,
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
                            'live_click_quota'     => $item['live_click_quota'] ?? $item['live_quota'] ?? 0,
                            'test_click_quota'     => $item['test_click_quota'] ??  $item['test_quota'] ?? 0,
                            'project_start_date'   => $item['project_start_date'],
                            'project_end_date'     => $item['project_end_date'],
                            'live_url'             => $item['live_url'],
                            'test_url'             => $item['test_url'],
                            'status'               => $item['status'] ?? 'Live',
                            'device_type'          => $item['device_type'],
                            'target_spec'           => is_array($item['target_spec'] ?? null) ? json_encode($item['target_spec']) : ($item['target_spec'] ?? null),
                            'updated_at' => now(),
                        ]
                    );
                    $data = $item['qualifications'] ?? [];
                    // No questions available
                        if (!empty($data['question']) && is_array($data['question'])) {
                            foreach ($data['question'] as $questionId) {
                                $surveyQuestion = $survey->surveyQuestions()->updateOrCreate(
                                    ['question_id' => $questionId],
                                    []
                                );
                                $optionsData = $data['option'][$questionId] ?? [];
                                // Delete options that are no longer available
                                if (!empty($optionsData)) {
                                    SurveyQuestionOption::where('survey_question_id', $surveyQuestion->id)
                                        ->Where('question_id', $questionId)
                                        ->whereNotIn('option', $optionsData)
                                        ->delete();

                                } else {
                                    // Remove all old options if no options exist
                                    SurveyQuestionOption::where('survey_question_id', $surveyQuestion->id)
                                        ->Where('question_id', $questionId)
                                        ->delete();
                                    continue;
                                }
                                $options = [];
                                foreach ($optionsData as $optionCode) {
                                    $options[] = [
                                        'survey_question_id' => $surveyQuestion->id,
                                        'question_id'        => $questionId,
                                        'option'             => $optionCode,
                                        'option_text'        => $data['option_text'][$questionId][$optionCode] ?? '',
                                        'option_quota'       => $data['option_quota'][$questionId][$optionCode] ?? 0,
                                    ];
                                }

                                if (!empty($options)) {
                                    SurveyQuestionOption::upsert(
                                        $options,
                                        ['survey_question_id', 'option'],
                                        ['option_text', 'option_quota']
                                    );
                                }
                            }
                        } elseif (!empty($data) && is_array($data)) {
                            foreach ($data as $qua) {
                                $questionId = isset($qua['question_id']) ? (string)$qua['question_id'] : null;
                                if ($questionId === null) {
                                    continue;
                                }

                                // $surveyQuestion = SurveyQuestion::updateOrCreate(
                                //     ['survey_id' => $survey->id, 'question_id' => $questionId]
                                // );
                                 $surveyQuestion = $survey->surveyQuestions()->updateOrCreate(
                                    ['question_id' => $questionId],
                                    []
                                );

                                $optionsData = [];
                                $optionTextMap = [];
                                $optionQuotaMap = [];

                                // range control (control_type = 6)
                                if (isset($qua['control_type']) && (int)$qua['control_type'] === 6) {
                                    if (!empty($qua['range']) && is_array($qua['range'])) {
                                        foreach ($qua['range'] as $r) {
                                            if (isset($r['range'])) {
                                                $optionsData[] = (string)$r['range'];
                                            }
                                        }
                                    }
                                    foreach ($qua['quota'] ?? [] as $q) {
                                        if (isset($q['range'])) {
                                            $optionQuotaMap[(string)$q['range']] = (int)($q['quota'] ?? 0);
                                        }
                                    }
                                } else {
                                    // normal options shape: [{"option":1}, ...] or keyed by question id
                                    if (isset($qua['option'][$questionId]) && is_array($qua['option'][$questionId])) {
                                        $optionsData = array_map('strval', $qua['option'][$questionId]);
                                        $optionTextMap = $qua['option_text'][$questionId] ?? [];
                                        $optionQuotaMap = $qua['option_quota'][$questionId] ?? [];
                                    } elseif (!empty($qua['option']) && is_array($qua['option'])) {
                                        foreach ($qua['option'] as $o) {
                                            if (is_array($o) && isset($o['option'])) {
                                                $optionsData[] = (string)$o['option'];
                                            } elseif (!is_array($o)) {
                                                $optionsData[] = (string)$o;
                                            }
                                        }
                                        foreach ($qua['quota'] ?? [] as $q) {
                                            if (isset($q['option'])) {
                                                $optionQuotaMap[(string)$q['option']] = (int)($q['quota'] ?? 0);
                                            }
                                        }
                                    }
                                }

                                if (!empty($optionsData)) {
                                    SurveyQuestionOption::where('survey_question_id', $surveyQuestion->id)
                                        ->Where('question_id', $questionId)
                                        ->whereNotIn('option', $optionsData)
                                        ->delete();
                                } else {
                                    SurveyQuestionOption::where('survey_question_id', $surveyQuestion->id)
                                        ->Where('question_id', $questionId)
                                        ->delete();
                                }

                                $options = [];
                                foreach ($optionsData as $opt) {
                                    $options[] = [
                                        'survey_question_id' => $surveyQuestion->id,
                                        'question_id' => $questionId,
                                        'option' => $opt,
                                        'option_text' => $optionTextMap[$opt] ?? '',
                                        'option_quota' => $optionQuotaMap[$opt] ?? 0,
                                    ];
                                }

                                if (!empty($options)) {
                                    SurveyQuestionOption::upsert($options, ['survey_question_id', 'option'], ['option_text', 'option_quota']);
                                }
                            }
                        }
                        /* elseif (!empty($data['question']) && is_array($data['question'])) {
                            // fallback: existing keyed-by-question-id shape
                            foreach ($data['question'] as $questionId) {
                                $surveyQuestion = SurveyQuestion::updateOrCreate(
                                    ['survey_id' => $survey->id, 'question_id' => $questionId]
                                );

                                $optionsData = $data['option'][$questionId] ?? [];
                                $optionTextMap = $data['option_text'][$questionId] ?? [];
                                $optionQuotaMap = $data['option_quota'][$questionId] ?? [];

                                if (!empty($optionsData)) {
                                    SurveyQuestionOption::where('question_id', $questionId)
                                        ->whereNotIn('option', $optionsData)
                                        ->delete();
                                } else {
                                    SurveyQuestionOption::where('question_id', $questionId)->delete();
                                    continue;
                                }

                                $options = [];
                                foreach ($optionsData as $optionCode) {
                                    $options[] = [
                                        'question_id' => $questionId,
                                        'option' => $optionCode,
                                        'option_text' => $optionTextMap[$optionCode] ?? '',
                                        'option_quota' => $optionQuotaMap[$optionCode] ?? 0,
                                    ];
                                }

                                if (!empty($options)) {
                                    SurveyQuestionOption::upsert($options, ['question_id', 'option'], ['option_text', 'option_quota']);
                                }
                            }
                        } */

                    }
            });
            Survey::whereNotIn('project_id', $apiProjectIds->all())
                        ->where('api_project_id', $request->supplier_id)
                                ->update([
                                    'status' => 'Pause',
                                ]);
            $updatedCount = Survey::where('api_project_id', $request->supplier_id)->where('status','Live')->count();

            $content = "Survey synced successfully.";
            $full_url = url()->full();
             return view('survey.api_response', [
                'updatedCount' => $updatedCount,
                'requestCount' => $requestCount,
                'content'      => 'Survey synced successfully.',
                'full_url'     => url()->full(),
                'paginator'    => $paginatedRows,
                'supplierId'   => $request->supplier_id,
            ]);
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return view('survey.error_response', compact('error'));
        }

    }

    public function vendorProjectAssign(Request $request, $id)
    {
        $assignedVendorIds = VendorProjectAssign::where('survey_id', $id)->pluck('vendor_id');
        $vendors = Vendor::query()
            ->whereNotIn('id', $assignedVendorIds)
           ->when(Auth::user()->hasRole('Vendor'), function ($query) {
                $query->where('vendor_id', Auth::user()->vendor->vendor_id);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where('vendor_id', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
         return view('masters.vendor-project-assign', compact('id','vendors'));
    }

    public function searchVendors(Request $request)
    {
        $surveyId = $request->survey_id;

        $assignedVendorIds = VendorProjectAssign::where('survey_id', $surveyId)
            ->pluck('vendor_id');

        $vendors = Vendor::query()
            ->whereNotIn('id', $assignedVendorIds)
           ->when(Auth::user()->role === 'vendor', function ($query) {
                $query->where('vendor_id', Auth::user()->vendor->vendor_id);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where('vendor_id', 'like', '%' . $request->search . '%');
            })
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
        $supplierId = request('supplier_id');
        $surveyStatus = request('survey_status');

        $vendor_projects = VendorProjectAssign::query()
           ->with([
                'vendor.user',
                'survey.surveyQuestions.surveyOption',
                'survey.surveyQuestions.question',
                 'survey.surveyQuotaQualifications',
                'survey.country:id,name',
                'survey.language:id,name',
            'survey.demographics' => function ($query) {
                $query->select([
                    'demographics.id',
                    'demographics.world_language_id',
                    'demographics.demographic_name',
                    'demographics.question_id',
                    'demographics.question_text',
                    'demographics.question_type',
                    'demographics.answer_codes',
                ]);
            },
    ])
            ->search(request('q'))
            ->when($supplierId, function ($query, $supplierId) {
                $query->whereHas('survey', function ($subQuery) use ($supplierId) {
                    $subQuery->where('api_project_id', $supplierId);
                });
            })
            ->when($surveyStatus, function ($query, $surveyStatus) {
                $query->whereHas('survey', function ($subQuery) use ($surveyStatus) {
                    $subQuery->where('status', $surveyStatus);
                });
            });

        if (Auth::user()->hasRole('Vendor')) {
            $vendor_projects->where('vendor_id', Auth::user()->vendor->id);
        }

        $vendor_projects = $vendor_projects->with(['vendor.user', 'survey'])->paginate(10);
        $suppliers = Project::where('status', 1)->get();

        return view('masters.vendor-project-assign-list', compact('vendor_projects', 'suppliers'));
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
                    'temp_complete' => ['nullable','string', 'max:255'],
                    'temp_terminate' => ['nullable','string', 'max:255'],
                    'temp_quotafull' => ['nullable','string', 'max:255'],
                    'temp_speedster' => ['nullable','string', 'max:255'],
                    'temp_cpi' => [
                                'required',
                                'numeric',
                                'decimal:0,2',
                                'max:9999.99',
                            ],
                ]);
           $vendor = Vendor::query()->findorfail($request->vendor_id);
           $survey = Survey::query()->findorfail($id);
            $data['survey_id'] = $id;
            $data['three_as_project_id'] = $survey->survey_code;
            $data['project_vendor_id'] =  $vendor->vendor_id.'_'.$id;
            VendorProjectAssign::create($data);

        return redirect()->route('vendor.project.assign.list')->with('success', 'Project assigned successfully.');
    }

    public function destroy(VendorProjectAssign $vendorProjectAssign)
    {
            // Delete related assigned projects
            $vendorProjectAssign->delete();

        return redirect()
            ->route('vendor.project.report')
            ->with('success', 'Survey project deleted successfully.');
    }

    public function demographics(Request $request, Survey $survey)
    {
        $rows = $survey->demographics()
            ->orderBy('id')
            ->paginate(10, ['*'], 'page', $request->integer('page', 1));

        return view('survey.partials.demographics', compact('rows', 'survey'));
    }
}
