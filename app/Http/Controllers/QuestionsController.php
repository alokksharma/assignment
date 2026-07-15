<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Http;

class QuestionsController extends Controller
{
    public function index()
    {
       $questions = Question::query()->paginate(20);
        return view('masters.questions', compact('questions'));
    }

    public function syncQuestions()
    {
        $url = env('MARKET_XCEL') . '/get_master_data/question';

        $response = Http::withHeaders([
            'Token' => env('SUPPLIER_TOKEN'),
        ])->get($url);

        if (!$response->successful()) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch questions data.',
            ], 500);
        }

        $questions = collect($response->json('Data'));

        $questions
            ->chunk(900)
            ->each(function ($chunk) {

                foreach ($chunk as $item) {


                    // Assuming you have a Question model and the necessary fields
                    Question::updateOrCreate(
                        [
                            'question_id'   => $item['id']
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

        return response()->json([
            'status'  => true,
            'message' => 'Questions synced successfully.',
            'data'    => Question::all(),
        ]);
    }
}
