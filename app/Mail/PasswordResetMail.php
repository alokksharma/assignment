<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetUrl;
    public $expiry_time;
    public $appName;
    public $support_email;

    public function __construct($user, $resetUrl, $expiry_time = '60 minutes')
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
        $this->expiry_time = $expiry_time;

        $this->appName = config('app.name', '3AS Research Solutions');
        $this->support_email = 'support@3asresearchsolutions.in';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your Password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset',
        );
    }
}
