<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\Project;
use App\Models\SurveyQuestionOption;
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
        $project = Project::where('name', 'AS_API')->first();
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
}
