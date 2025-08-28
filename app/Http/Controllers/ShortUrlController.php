<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Traits\CompanyTrait;

class ShortUrlController extends Controller
{
    use CompanyTrait;

    public function create()
    {
        return view('short_urls.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
        ]);
        $user = Auth::user();
        $shortCode = Str::random(6);
        while (ShortUrl::where('short_code', $shortCode)->exists()) {
            $shortCode = Str::random(6);
        }
        $shortUrl = ShortUrl::create([
            'user_id' => $user->id,
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
        ]);
        return redirect()->route('short_urls.index', $shortUrl)->with('success', 'Short URL created!');
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


}
