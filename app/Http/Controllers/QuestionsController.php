<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Project;
use Illuminate\Support\Facades\Http;

class QuestionsController extends Controller
{
    public function index()
    {
        $questions = Question::query()
            ->when(request('supplier_id'), function ($query, $supplierId) {
                $query->where('project_id', $supplierId);
            })
            ->paginate(20);

        $suppliers = Project::where('status', 1)->get();

        return view('masters.questions', compact('questions', 'suppliers'));
    }

    public function syncQuestions(Request $request)
    {
        try {
            $url = config('marketxcel.base_url') . '/get_master_data/question';
            $supplier_id = config('marketxcel.supplier_id');
            $supplier_token = config('marketxcel.supplier_token');
            $supplier = null;
            if($request->supplier_id){
                $supplier = Project::findorfail($request->supplier_id);
                $url =  $supplier->url. '/get_master_data/question';
                $supplier_id = $supplier->supplier_id;
                $supplier_token = $supplier->supplier_token;
            }
            $response = Http::withHeaders([
                'Supplierid' => $supplier_id,
                'Token' => $supplier_token,
            ])->get($url);

            if (!$response->successful()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Failed to fetch questions data.',
                ], 500);
            }

            $questions = collect($response->json('Data'));
            $requestCount = $questions->count();
            $questions
                ->chunk(900)
                ->each(function ($chunk) use ($supplier) {

                    foreach ($chunk as $item) {
                        // Assuming you have a Question model and the necessary fields
                        Question::updateOrCreate(
                            [
                                'question_id'   => $item['id'],
                                'project_id' => $supplier?->id,
                            ],
                            [
                                'country_id'     => $item['country'],
                                'language_id'    => $item['language'],
                                'control_type_id'=> $item['control_type'],
                                'title'          => $item['title'] ?? null,
                                'question'       => $item['question'] ?? null,
                                'mapped_options' => $item['mapped_option'] ?? [],
                                'from_range'     => $item['from_range'] ?? null,
                                'to_range'       => $item['to_range'] ?? null,
                            ]
                        );
                    }
                });
            $updatedCount = Question::where('project_id', $request->supplier_id)->count();
            $full_url = url()->full();
            $content = "Questions synced successfully.";
            return view('survey.success_response', compact('updatedCount','requestCount', 'content', 'full_url'));
            // return response()->json([
            //     'status'  => true,
            //     'message' => 'Questions synced successfully.',
            //     'data'    => [
            //                     'updated_count' => $updatedQuestions,
            //                     'request_count' => $apiResponseQuestionCount,
            //                   // 'current_url'   => url()->full(),
            //                 ],
            // ]);
        } catch (\Throwable $th) {
            $error = $th->getMessage();
             return view('survey.error_response', compact('error'));
        }


    }
}
