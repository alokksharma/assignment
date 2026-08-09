<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Country;
use App\Models\Question;
use Illuminate\Support\Facades\Http;

class LanguageController extends Controller
{
    public function syncLanguages()
    {
        $url = env('MARKET_XCEL') . '/get_master_data/language';
        $response = Http::withHeaders([
            'Token' => env('SUPPLIER_TOKEN'),
        ])->get($url);

        // if (!$response->successful()) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Unable to fetch languages.'
        //     ], 500);
        // }

        $languages = collect($response->json()['Data']);
        $languages->chunk(50)->each(function ($chunk) {
            foreach ($chunk as $language) {
                Language::updateOrCreate(
                    ['id' => $language['id']],
                    [
                        'name'       => $language['name'],
                        'short_name' => $language['short_name'] ?? null,
                    ]
                );
            }
        });

        return response()->json([
            'status' => true,
            'message' => 'Languages synced successfully.',
            'data' => Language::all()
        ]);
    }

    public function syncCountries()
    {
        $url = env('MARKET_XCEL') . '/get_master_data/country';

        $response = Http::withHeaders([
            'Token' => env('SUPPLIER_TOKEN'),
        ])->get($url);

        if (!$response->successful()) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch countries data.',
            ], 500);
        }

        $countries = collect($response->json('Data'));

        $countries
            ->chunk(500)
            ->each(function ($chunk) {

                foreach ($chunk as $item) {

                    $country = Country::updateOrCreate(
                        [
                            'id' => $item['id'],
                        ],
                        [
                            'name'         => $item['name'],
                            'short_name'   => $item['short_name'],
                            'iso_code'     => $item['iso_code'],
                            'code'         => $item['country_code'],
                        ]
                    );

                    $languageIds = collect(explode(',', $item['language'] ?? ''))
                        ->filter()
                        ->map(fn ($id) => (int) trim($id))
                        ->values()
                        ->toArray();

                    $country->languages()->sync($languageIds);
                }
            });

        return response()->json([
            'status'  => true,
            'message' => 'Countries synced successfully.',
            'data'    => Country::with('languages')->get(),
        ]);
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
            ->chunk(500)
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
};
