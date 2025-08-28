<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // inviter
        'invite_id', // invited user
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invited()
    {
        return $this->belongsTo(User::class, 'invite_id');
    }
}
