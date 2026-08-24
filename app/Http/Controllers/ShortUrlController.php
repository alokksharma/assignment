<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Traits\CompanyTrait;
use App\Models\UrlHitCount;

class ShortUrlController extends Controller
{
    use CompanyTrait;

    public function create()
    {
        return view('short_urls.create');
    }

    public function store(Request $request)
    {
        try {
             $request->validate([
                'original_url' => 'required|url',
            ]);
            $user = Auth::user();
            $shortCode = Str::random(6);
            while (ShortUrl::where('short_code', $shortCode)->exists()) {
                $shortCode = Str::random(6);
            }
          $shortUrl = $this->processStore($request, $shortCode, $user);
             UrlHitCount::create([
                'short_url_id' => $shortUrl->id,
                'hit_count' => 0,
            ]);
            return redirect()->route('short_urls.index', $shortUrl)->with('success', 'Short URL created!');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Failed to create Short URL. Please try again.');
        }
         
           
    }

    public function show(ShortUrl $shortUrl)
    {
        return view('short_urls.show', compact('shortUrl'));
    }

    //short url list
    public function index()
    {
        $shortUrls = $this->shortUrlList();
        return view('short_urls.index', compact('shortUrls'));
    }

    //redirect to original url
    public function redirectToOriginalUrl($code)
    {
       try {
            $fullUrl = ShortUrl::where('short_code', $code)->first();
            if ($fullUrl) {
                $this->updateHitCount($fullUrl);
                return redirect($fullUrl->original_url);
            } else {
                return redirect('/')->with('error', 'Invalid Short URL');
            }

       } catch (\Throwable $th) {
         return redirect('/')->with('error', 'Invalid Short URL');
       }
    }

     public function updateHitCount($shortUrl){
        $checkHitUrl = UrlHitCount::where('short_url_id', $shortUrl->id)->first();
        try {
            if($checkHitUrl){
                $checkHitUrl->update([
                    'hit_count' => $checkHitUrl->hit_count + 1
                ]);
                return $checkHitUrl;
            }else{
                $hitCount = UrlHitCount::create([
                    'short_url_id' => $shortUrl->id,
                    'hit_count' => 1,
                ]);
                
                return $hitCount;
            }
        } catch (\Throwable $th) {
            return null;
        }
        
     }

    public function processStore($request, $shortCode, $user){

        $shortUrl = ShortUrl::create([
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
            'user_id' => $user->id,
        ]);
        return $shortUrl;

    }

}
