<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordChangedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $subject = 'Password Changed';

        $companyName = config('app.name', '3AS Research');
        $userName = $user->name ?? $user->email;
        $changeDate = now()->format('Y-m-d');
        $changeTime = now()->format('H:i:s');
        $timeZone = now()->format('T');
        $accountSecurityLink = url('/account/security');
        $supportEmail = config('mail.from.address', 'support@example.com');
        $companyWebsite = config('app.url');

        $message = view('emails.password-changed', [
            'company_name' => $companyName,
            'user_name' => $userName,
            'change_date' => $changeDate,
            'change_time' => $changeTime,
            'time_zone' => $timeZone,
            'account_security_link' => $accountSecurityLink,
            'support_email' => $supportEmail,
            'company_website' => $companyWebsite,
        ])->render();
        $message = preg_replace("/(?<!\r)\n/", "\r\n", $message);
         // Headers must also use CRLF
        $headers = "From: no-reply@3asresearchsolutions.in\r\n";
        $headers .= "Reply-To: support@3asresearchsolutions.in\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        try {
            mail($user->email, $subject, $message, $headers);
        } catch (\Throwable $e) {
            dd( $e);
            \Log::error('Password changed mail failed', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('status', 'password-updated');
    }
}
