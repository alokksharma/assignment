<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Http;

class CountriesController extends Controller
{
    public function index()
    {
       $countries = Country::query()->with('languages')->paginate(20);
        return view('masters.countries', compact('countries'));
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
}
