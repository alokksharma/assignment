<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
//use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\Email;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Auth;

class MailTestController extends Controller
{
    /**
     * Quick raw send test — bypasses Mailables/Blade entirely so you can
     * isolate "is the transport working at all" from "is my template broken".
     *
     * Visit: /mail-test?to=you@example.com
     */
    public function raw(Request $request): JsonResponse
    {
        // $request->validate([
        //     'to' => ['required', 'email'],
        // ]);

        $to = 'akkkk@yopmail.com';

        try {
            $subject = 'Password Reset';

            $appName = config('app.name', '3AS Research Solutions');
            $supportEmail = 'support@3asresearchsolutions.in';
            $user = User::latest()->first();
            $resetUrl = '';
            $message = view('emails.password-reset', [
                'appName' => $appName,
                'user' => $user,
                'resetUrl' => $resetUrl,
                'expiry_time' => '60 minutes',
                'support_email' => $supportEmail,
            ])->render();


        // IMPORTANT: Convert all LF line endings to CRLF
        $message = preg_replace("/(?<!\r)\n/", "\r\n", $message);


        // Headers must also use CRLF
        $headers = "From: no-reply@3asresearchsolutions.in\r\n";
        $headers .= "Reply-To: support@3asresearchsolutions.in\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";


        if (mail($to, $subject, $message, $headers)) {
            return response()->json([
                'status' => 'sent',
                'to' => $to,
                'sent_at' => now()->toDateTimeString(),
            ]);
        }


            return response()->json([
                'status' => 'not sent',

                'to' => $to,
                'sent_at' => now()->toDateTimeString(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Mail transport test failed', [
                'mailer' => config('mail.default'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'failed',
                'mailer' => config('mail.default'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sends the real ForgotPasswordMail template with dummy data, so you can
     * confirm both the transport AND the Blade template render/send correctly
     * together — the actual thing your users will receive.
     *
     * Visit: /mail-test/forgot-password?to=you@example.com
     */
    public function forgotPasswordTemplate(Request $request): JsonResponse
    {
        $request->validate([
            'to' => ['required', 'email'],
        ]);

        $to = $request->query('to');

        try {
            Mail::to($to)->send(new \App\Mail\ForgotPasswordMail(
                user_name: 'Test User',
                reset_password_link: url('/reset-password/test-token?email=' . urlencode($to)),
                expiry_time: '60 minutes',
            ));

            return response()->json([
                'status' => 'sent',
                'mailer' => config('mail.default'),
                'template' => 'emails.forgot-password',
                'to' => $to,
            ]);
        } catch (\Throwable $e) {
            Log::error('ForgotPasswordMail template test failed', [
                'mailer' => config('mail.default'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'failed',
                'mailer' => config('mail.default'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dumps the active mail config (minus secrets) so you can confirm
     * .env / config:cache actually picked up your changes before testing send.
     *
     * Visit: /mail-test/config
     */
    public function config(): JsonResponse
    {
        return response()->json([
            'default_mailer' => config('mail.default'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
            'sendmail_path' => config('mail.mailers.sendmail.path') ?? null,
            'smtp_host' => config('mail.mailers.smtp.host') ?? null,
            'smtp_port' => config('mail.mailers.smtp.port') ?? null,
        ]);
    }

    public function resetmail(){
        try {
            $user = Auth::user();
            $to = 'akkkk@yopmail.com';
            $resetUrl = '';
            Mail::to($to)->send(
                    new PasswordResetMail(
                        $user,
                        $resetUrl,
                        '60 minutes'
                    )
                );
             return response()->json([
                'status' => 'sent',
                'mailer' => config('mail.default'),
                'template' => 'emails.reset-password',
                'to' => $to,
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => 'failed',
                'mailer' => config('mail.default'),
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
