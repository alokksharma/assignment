<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\Http;

class LanguageController extends Controller
{
    public function index()
    {
       $languages = Language::query()->paginate(20);

        return view('masters.languages', compact('languages'));
    }

    public function syncLanguages()
    {
        $url = env('MARKET_XCEL') . '/get_master_data/language';
        $response = Http::withHeaders([
            'Token' => env('SUPPLIER_TOKEN'),
        ])->get($url);
        if($response->json())
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
}
