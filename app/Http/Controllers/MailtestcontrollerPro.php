<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\Email;

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
    $to = 'anupam9211@gmail.com';

    try {

        Mail::raw(
            "Hello!\n\nThis is a test email from 3AS Research Solutions.\n\n"
            . "Time: " . now()->toDateTimeString(),
            function ($message) use ($to) {

                $message->to($to)
                    ->subject('3AS Research - SMTP Test')
                    ->from(
                        config('mail.from.address'),
                        config('mail.from.name')
                    )
                    ->replyTo(
                        config('mail.from.address')
                    );
            }
        );

        return response()->json([
            'status' => 'sent',
            'mailer' => config('mail.default'),
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'from' => config('mail.from.address'),
            'to' => $to,
            'sent_at' => now()->toDateTimeString(),
        ]);

    } catch (\Throwable $e) {

        Log::error('SMTP mail test failed', [
            'mailer' => config('mail.default'),
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'status' => 'failed',
            'mailer' => config('mail.default'),
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
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
}
