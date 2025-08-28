<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Traits\CompanyTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use CompanyTrait;
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Update or create company
        if ($request->has('company_name')) {
            $user->company = $request->company_name;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function dashboard(Request $request): View
    {
        if (Auth::user()->hasRole('SuperAdmin')) {
          $userCount =  $this->userCount('SuperAdmin');
          $shortUrlsCount = \App\Models\ShortUrl::count();
        }elseif (Auth::user()->hasRole('Admin')) {
          $userCount =  $this->userCount('Admin');
          //short url count based on company id which place in users table
            $shortUrlsCount = \App\Models\ShortUrl::whereIn('user_id', function($query) {
                    $query->select('id')
                        ->from('users')
                        ->where('company_id', Auth::user()->company_id);
                })->count();
        }else{
          $userCount = 0;
            $shortUrlsCount = \App\Models\ShortUrl::where('user_id', Auth::id())->count();
        }
        // $shortUrls = $this->shortUrlList();
        //$users = $this->inviteList();
        return view('dashboard', compact('userCount', 'shortUrlsCount'));
    }
}
