<?php

namespace App\Http\Controllers;

 use Illuminate\Http\Request;
use App\Http\Requests\S2CRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use App\Models\Country;
use App\Models\Language;
use App\Models\WorldLanguage;
use App\Models\Survey;
use App\Models\Question;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use App\Models\ZampilaQuota;
use App\Models\Demographic;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use App\Models\SurveyQuotaQualification;

class ApiController extends Controller
{
    public function languageSync(){
        try {
            $supplier = Project::where('code', 'ZAM')->first();
            $url            = $supplier->url . '/Attributes/GetLanguages';
            $supplierToken  = $supplier->supplier_token;

            $response = Http::withHeaders([
                'ZAMP-KEY' => $supplierToken,
            ])->get($url);

            if (!$response->successful()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unable to fetch languages.'
                ], 500);
            }

            $languages = collect($response->json()['result']['data']);
            $lang = [];
            $languages->chunk(50)->each(function ($chunk) use ( &$lang, $supplier) {
                foreach ($chunk as $language) {
                    if (empty($language) || $language['LanguageCode'] === null) {
                        continue;
                    }
                    $country = Country::where('short_name', $language['CountryCode'])
                        ->orWhere('name', $language['Country'])
                        ->first();
                        $languageName = Str::before($language['LanguageCode'], '-');
                        $shortName = Str::lower(Str::substr($languageName, 0, 2));

                    $languageModel = Language::where('name', $languageName)->orWhere('short_name', $shortName)->first();
                    if (!$languageModel) {
                        $languageModel = Language::create([
                            'name'       => $languageName,
                            'short_name' => $shortName,
                        ]);
                    }
                    if ($country) {
                        $country_ids[] = $country->id;
                    }
                    $lang[] = WorldLanguage::updateOrCreate(
                        ['api_lang_id' => $language['LanguageId']],
                        [
                            'name'       => $language['LanguageCode'],
                            'country_id' => $country?->id,
                            'language_id' => $languageModel->id,
                            'api_id' => $supplier?->id ?? null,
                        ]
                    );

                }
            });
        $updatedCount = count($lang);
        $requestCount = $languages->count();
        $full_url = url()->full();
        $content = 'Languages synced successfully.';

        return view('survey.success_response', compact('updatedCount', 'requestCount', 'content', 'full_url'));
        } catch (\Throwable $th) {
            return view('survey.error_response', ['error' => $th->getMessage()]);
        }

    }

    public function zampliaServe(Request $request)
    {
        try {

            $supplier = Project::where('code', 'ZAM')->first();
            if(!$supplier){
                return view('survey.error_response', ['error' => 'Zamp project not found.']);
            }
            $supplierToken  = $supplier->supplier_token;

            $response = Http::withHeaders(['ZAMP-KEY' => $supplierToken])
                ->get($supplier->url . '/Surveys/GetAllocatedSurveys');

            if (!$response->successful()) {
                return view('survey.error_response', ['error' => 'Failed to fetch Survey data.']);
            }

            $allRows      = collect($response->json('result.data', []));
            $requestCount = $allRows->count();
            $apiSurveyIds = $allRows->pluck('SurveyId')->filter()->unique();
            // Paginate the already-fetched list (each survey costs 2 extra API calls)
            $perPage = max(1, min((int) $request->input('per_page', 20), 100));
            $page = max(1, (int) $request->input('page', 1));
            $paginatedRows = new LengthAwarePaginator(
                $allRows->forPage($page, $perPage)->values(),
                $requestCount,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $languages = WorldLanguage::where('api_id', $supplier->id)->get()->keyBy('api_lang_id');
            foreach ($paginatedRows->items() as $item) {
                if (empty($item['SurveyId'])) {
                    continue;
                }
                $surveyId = $item['SurveyId'];
                $quotas   = $this->getResponse($surveyId, $supplier, '/Surveys/GetSurveyQuotas');
                $questions = $this->getResponse($surveyId, $supplier, '/Surveys/GetSurveyQualifications');
                $lang = WorldLanguage::where('api_lang_id', $item['LanguageId'])->first();

                $piiCollection = strtolower((string) ($item['CollectPII'] ?? 'No'));
                $piiCollection = in_array($piiCollection, ['1', 'yes', 'true', 'y'], true) ? 'Yes' : 'No';

                $survey = Survey::updateOrCreate(
                    [
                        'project_id'     => $surveyId,
                        'api_project_id' => $supplier->id,
                    ],
                    [
                        'project_code'         => $surveyId,
                        'project_name'         => $item['Name'] ?? null,
                        'country_id'           => $lang?->country_id,
                        'language_id'          => $lang?->language_id,
                        'category'             => 'Consumer',
                        'currency'             => 'USD',
                        'audience_type'        => 'B2C',
                        'pii_collection'       => $piiCollection,
                        'application_download' => 'No',
                        'facial_coding'        => 'No',
                        'project_ir'           => $item['IR'] ?? null,
                        'project_loi'          => $item['LOI'] ?? null,
                        'cpi'                  => $item['CPI'] ?? null,
                        'quota'                => $quotas['TotalQuotaCount'] ?? 0,
                        'project_start_date'   => ! empty($item['LastUpdateTimeStamp'])
                                                    ? Carbon::parse($item['LastUpdateTimeStamp'])->format('Y-m-d')
                                                    : null, // not in the allocated-survey response
                        'project_end_date'     => ! empty($item['SurveyEndDate'])
                                                    ? Carbon::parse($item['SurveyEndDate'])->format('Y-m-d')
                                                    : null,
                        'status'               => 'Live',
                        'device_type'          => $item['Device'] ?? null,
                        'updated_at'           => !empty($item['LastUpdateTimeStamp'])
                                                    ? Carbon::parse($item['LastUpdateTimeStamp'])
                                                    : now(),
                    ]
                );

                if (!empty($questions) && is_array($questions)) {
                    foreach ($questions as $question) {
                        $questionId = $question['QuestionID'] ?? null;
                        if (empty($questionId)) {
                            continue;
                        }

                        $surveyQuestion = $survey->surveyQuestions()->updateOrCreate(
                            [
                                'question_id' => $questionId,
                            ],
                            []
                        );

                        $answerCodes = $question['AnswerCodes'] ?? [];

                        if (!empty($answerCodes)) {
                            SurveyQuestionOption::where('survey_question_id', $surveyQuestion->id)
                                ->Where('question_id', $questionId)
                                ->delete();

                            $options = collect($answerCodes)->map(function ($option) use ($questionId, $surveyQuestion) {
                                $optionValue = (string) $option;

                                return [
                                    'survey_question_id' => $surveyQuestion->id,
                                    'question_id' => $questionId,
                                    'option' => $optionValue,
                                    'option_text' => $optionValue,
                                    'option_quota' => 0,
                                ];
                            })->all();

                            if (!empty($options)) {
                                SurveyQuestionOption::upsert(
                                    $options,
                                    ['survey_question_id', 'option'],
                                    ['option_text', 'option_quota']
                                );
                            }
                        } else {
                            SurveyQuestionOption::where('survey_question_id', $surveyQuestion->id)
                                ->Where('question_id', $questionId)
                                ->delete();
                        }
                    }
                }

                ZampilaQuota::updateOrCreate(
                    ['serve_id' => $surveyId],
                    [
                        'quota_qualification' => !empty($quotas['QuotaQualifications'])
                            ? json_encode($quotas['QuotaQualifications'])
                            : null,
                    ]
                );

                if (!empty($quotas['QuotaQualifications']) && is_array($quotas['QuotaQualifications'])) {
                    foreach ($quotas['QuotaQualifications'] as $quotaQualification) {
                        $questionId = $quotaQualification['QuestionId'] ?? null;
                        if (empty($questionId)) {
                            continue;
                        }

                        SurveyQuotaQualification::updateOrCreate(
                            [
                                'survey_id' => $survey->id,
                                'question_id' => $questionId,
                                'answer_code' => (string) ($quotaQualification['AnswerCode'] ?? ''),
                            ],
                            [
                                'project_id' => $supplier->id,
                                'answer_text' => $quotaQualification['AnswerText'] ?? null,
                                'total_qualification_count' => (int) ($quotaQualification['TotalQualificationCount'] ?? 0),
                                'total_quota_count' => (int) ($quotaQualification['TotalQuotaCount'] ?? 0),
                            ]
                        );
                    }
                }

            }

            // Surveys no longer allocated -> Pause (same as syncSurve)
            Survey::where('api_project_id', $supplier->id)
                ->whereNotIn('project_id', $apiSurveyIds->all())
                ->update(['status' => 'Pause']);

            $updatedCount = Survey::where('api_project_id', $supplier->id)
                ->where('status', 'Live')
                ->count();

            return view('survey.api_zamp_response', [
                'updatedCount' => $updatedCount,
                'requestCount' => $requestCount,
                'content'      => 'Zamplia surveys synced successfully.',
                'full_url'     => url()->full(),
                'paginator'    => $paginatedRows,
                'supplierId'   => $supplier->id,
            ]);

        } catch (\Throwable $th) {
            return view('survey.error_response', ['error' => $th->getMessage()]);
        }
    }

    public function createZampLink(Request $request)
    {
         $ipAddress = config('marketxcel.ip_source') === 'request' ? file_get_contents('https://api.ipify.org') : request()->ip();
        $supplier = Project::where('code', 'ZAM')->first();
        $supplierToken  = $supplier->supplier_token;
        $response =  Http::withHeaders([
                        'ZAMP-KEY' => $supplierToken,
                    ])->get( $supplier->url . '/Surveys/GenerateLink',[
                        'SurveyId' => $request->SurveyId,
                        'IpAddress' => $ipAddress,
                        'TransactionId' => '[PanelistID]',
                    ]);
        if (!$response->successful()) {
            return view('survey.error_response', ['error' => 'Failed to fetch Survey data.']);
        }

        $link = $response->json('result.data.0.LiveLink');
            if (! $link) {
                return back()->with('error', 'Unable to generate link.');
            }

            Survey::where('project_id', $request->query('SurveyId'))
                ->where('api_project_id', $supplier->id)
                ->update(['live_url' => $link, 'test_url' => $link]);

            return back()->with('success', 'Link generated successfully.');
    }

    public function DemoGraphicsSync(Request $request)
    {
        try {
            $supplier = Project::where('code', 'ZAM')->first();
            if (! $supplier) {
                return view('survey.error_response', ['error' => 'ZAM supplier not found.']);
            }
            $supplierToken = $supplier->supplier_token;
            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min($perPage, 50));

            $languages = WorldLanguage::where('api_id', $supplier->id)
                ->orderBy('id')
                ->paginate($perPage)
                ->withQueryString();

            $requestCount = 0;
            $updatedCount = 0;

            foreach ($languages as $language) {
                $demographicsResponse = Http::withHeaders([
                    'ZAMP-KEY' => $supplierToken,
                ])->get($supplier->url . '/Attributes/GetDemoGraphics', [
                    'LanguageId' => $language->api_lang_id,
                ]);

                if (! $demographicsResponse->successful()) {
                    continue;
                }

                $demographics = collect($demographicsResponse->json('result.data', []));
                $requestCount += $demographics->count();

                foreach ($demographics as $demographic) {
                    $questionId = $demographic['QuestionID'] ?? null;
                    if (empty($questionId)) {
                        continue;
                    }
                    Demographic::updateOrCreate(
                        [
                            'world_language_id' => $language->id,
                            'question_id' => $questionId,
                        ],
                        [
                            'demographic_name' => $demographic['DemographicName'] ?? null,
                            'question_text' => $demographic['QuestionText'] ?? null,
                            'question_type' => $demographic['QuestionType'] ?? null,
                            'answer_codes' => isset($demographic['AnswerCodes'])
                                ? json_encode($demographic['AnswerCodes'])
                                : null,
                        ]
                    );

                    $updatedCount++;
                }
            }

            $full_url    = url()->full();
            $content     = "Demographics synced for page {$languages->currentPage()} of {$languages->lastPage()}.";
            $nextPageUrl = $languages->nextPageUrl();

            return view('survey.zamp_api_response', compact(
                'updatedCount',
                'requestCount',
                'content',
                'full_url',
                'languages',
                'nextPageUrl'
            ));
        } catch (\Throwable $th) {
            return view('survey.error_response', ['error' => $th->getMessage()]);
        }
    }

    // public function createQualification(Request $request)
    // {
    //     try {
    //         $supplier = $request->supplier_id
    //             ? Project::findOrFail($request->supplier_id)
    //             : Project::where('code', 'ZAM')->first();

    //         if (! $supplier) {
    //             return view('survey.error_response', ['error' => 'Supplier not found.']);
    //         }

    //         $requestCount = 0;
    //         $updatedCount = 0;
    //         $controlType = ['text' => 2, 'MultiPunch' => 4, 'radio' => 1];
    //         WorldLanguage::where('api_id', $supplier->id)
    //             ->orderBy('id')
    //             ->chunk(50, function ($languages) use ($supplier, &$requestCount, &$updatedCount,  $controlType) {
    //                 foreach ($languages as $language) {
    //                     $response = Http::withHeaders([
    //                         'ZAMP-KEY' => $supplier->supplier_token,
    //                     ])->get($supplier->url . '/Attributes/GetDemoGraphics', [
    //                         'LanguageId' => $language->api_lang_id,
    //                     ]);

    //                     if (! $response->successful()) {
    //                         continue;
    //                     }

    //                     $questions = collect($response->json('result.data', []));

    //                     $requestCount += $questions->count();

    //                     foreach ($questions as $question) {
    //                         if (empty($question['QuestionID'])) {
    //                             continue;
    //                         }

    //                         Question::updateOrCreate(
    //                             [
    //                                 'project_id' => $supplier->id,
    //                                 'question_id' => $question['QuestionID'],
    //                                 'language_id' => $language->language_id ?? $language->id,
    //                             ],
    //                             [
    //                                 'country_id' => $language->country_id ?? null,
    //                                 'question' => $question['QuestionText'] ?? null,
    //                                 'mapped_options' => $question['AnswerCodes'] ?? [],
    //                                 'title' => $question['DemographicName'] ?? null,
    //                                 'control_type_id' => $controlType[$question['QuestionType']] ?? 0,
    //                             ]
    //                         );

    //                         $updatedCount++;
    //                     }
    //                 }
    //             });

    //         $full_url = url()->full();
    //         $content = 'Qualifications synced successfully.';

    //         return view('survey.success_response', compact('updatedCount', 'requestCount', 'content', 'full_url'));
    //     } catch (\Throwable $th) {
    //         return view('survey.error_response', ['error' => $th->getMessage()]);
    //     }
    // }

    public function createQualification(Request $request)
    {
        try {
            $supplier = $request->supplier_id
                ? Project::findOrFail($request->supplier_id)
                : Project::where('code', 'ZAM')->first();

            if (! $supplier) {
                return view('survey.error_response', ['error' => 'Supplier not found.']);
            }

            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min($perPage, 50)); // keep it sane

            $controlType  = ['text' => 2, 'MultiPunch' => 4, 'radio' => 1, 'Range' => 6];
            $requestCount = 0;
            $updatedCount = 0;

            $languages = WorldLanguage::where('api_id', $supplier->id)
                ->orderBy('id')
                ->paginate($perPage)
                ->withQueryString(); // keeps supplier_id, per_page in the links

            foreach ($languages as $language) {
                $response = Http::withHeaders([
                    'ZAMP-KEY' => $supplier->supplier_token,
                ])->get($supplier->url . '/Attributes/GetDemoGraphics', [
                    'LanguageId' => $language->api_lang_id,
                ]);

                if (! $response->successful()) {
                    continue;
                }

                $questions = collect($response->json('result.data', []));
                $requestCount += $questions->count();

                foreach ($questions as $question) {
                    if (empty($question['QuestionID'])) {
                        continue;
                    }

                    Question::updateOrCreate(
                        [
                            'project_id'  => $supplier->id,
                            'question_id' => $question['QuestionID'],
                            'language_id' => $language->language_id ?? $language->id,
                        ],
                        [
                            'country_id'      => $language->country_id ?? null,
                            'question'        => $question['QuestionText'] ?? null,
                            'mapped_options'  => $question['AnswerCodes'] ?? [],
                            'title'           => $question['DemographicName'] ?? null,
                            'control_type_id' => $controlType[$question['QuestionType'] ?? ''] ?? 0,
                        ]
                    );

                    $updatedCount++;
                }
            }

            $full_url    = url()->full();
            $content     = "Qualifications synced for page {$languages->currentPage()} of {$languages->lastPage()}.";
            $nextPageUrl = $languages->nextPageUrl(); // null on the last page

            return view('survey.zamp_api_response', compact(
                'updatedCount', 'requestCount', 'content', 'full_url', 'languages', 'nextPageUrl'
            ));
        } catch (\Throwable $th) {
            return view('survey.error_response', ['error' => $th->getMessage()]);
        }
    }

    public function getSurveyQuota($surveyId)
    {
        $supplier = Project::where('code', 'ZAM')->first();

        if (! $supplier) {
            return view('survey.error_response', ['error' => 'ZAM supplier not found.']);
        }

        $response = Http::withHeaders([
            'ZAMP-KEY' => $supplier->supplier_token,
        ])->get($supplier->url . '/Surveys/GetSurveyQuotas', [
            'SurveyId' => $surveyId,
        ]);

        if (! $response->successful()) {
            return view('survey.error_response', ['error' => 'Failed to fetch survey quota data.']);
        }

        return $response->json('result.data', []);
    }


    public function getResponse($surveyId, $supplier, $link)
    {

        if ( !$supplier) {
            return view('survey.error_response', ['error' => 'ZAM supplier not found.']);
        }

        $response = Http::withHeaders([
            'ZAMP-KEY' => $supplier->supplier_token,
        ])->get($supplier->url . $link, [
            'SurveyId' => $surveyId,
        ]);

        if (!$response->successful()) {
            return view('survey.error_response', ['error' => 'Failed to fetch survey quota data.']);
        }

        return $response->json('result.data', []);
    }

}
