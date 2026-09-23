<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invites()
    {
        return $this->hasMany(Invite::class, 'user_id');
    }

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'user_id');
    }

    public function partner()
    {
        return $this->hasOne(Partner::class, 'user_id');
    }

    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        $subject = 'Password Reset';
        $message = view('emails.reset-password', [
            'appName' => config('app.name', '3AS Research'),
            'user' => $this,
            'resetUrl' => $resetUrl,
            'expiry_time' => '60 minutes',
            'support_email' =>  config('mail.from.address', 'support@example.com'),
        ])->render();

        $message = preg_replace('/(?<!\r)\n/', "\r\n", $message);
         // Headers must also use CRLF
        $headers = "From: no-reply@3asresearchsolutions.in\r\n";
        $headers .= "Reply-To: support@3asresearchsolutions.in\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        try {
            mail($this->email, $subject, $message, $headers);
        } catch (\Throwable $e) {
            dd($e->getMessage());
            \Log::error('Password reset mail failed', [
                'email' => $this->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function scopeSearch($query, $searchTerm)
    {
        if (blank($searchTerm)) {
            return $query;
        }

        return $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
              ->orWhere('email', 'like', "%{$searchTerm}%")
              ->orwhereHas('vendor', function ($subQuery) use ($searchTerm) {
                  $subQuery->where('vendor_id', 'like', "%{$searchTerm}%");
              });
        });
    }


}
