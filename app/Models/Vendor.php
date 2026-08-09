<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expired_at' => 'date',
    ];

    public function getCurrencyNameAttribute()
    {
        return match ($this->currency) {
            1 => 'INR',
            2 => 'USD',
            3 => 'GBP',
            default => '-',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendorProjectAssigns()
    {
        return $this->hasMany(VendorProjectAssign::class, 'vendor_id', 'id');
    }



}
