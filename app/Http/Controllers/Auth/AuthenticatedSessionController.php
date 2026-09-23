<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\SuccessfulLoginNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        $now = Carbon::now();

        // Check vendor expiration
        if ($user->vendor && $user->vendor->expired_at) {
            $expiredAt = $user->vendor->expired_at instanceof Carbon ? $user->vendor->expired_at : Carbon::parse($user->vendor->expired_at);
            if ($expiredAt->lt($now)) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Your vendor account expired on ' . $expiredAt->format('Y-m-d H:i')]);
            }
        }

        // Check partner expiration
        if ($user->partner && $user->partner->expired_at) {
            $expiredAt = $user->partner->expired_at instanceof Carbon ? $user->partner->expired_at : Carbon::parse($user->partner->expired_at);
            if ($expiredAt->lt($now)) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Your partner account expired on ' . $expiredAt->format('Y-m-d H:i')]);
            }
        }

        $request->session()->regenerate();
        // if($user->partner && $user->vendor){
        //     $user->notify(new SuccessfulLoginNotification(
        //         $user->name ?? 'User',
        //         now()->format('d M, Y'),
        //         now()->format('h:i A'),
        //         config('app.timezone', 'UTC'),
        //         $request->ip() ?: 'Unknown',
        //         'Unknown',
        //         $request->header('User-Agent', 'Unknown'),
        //         url('/profile')
        //     ));

        // }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
