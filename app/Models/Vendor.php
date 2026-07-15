<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getCurrencyNameAttribute()
    {
        return match ($this->currency) {
            1 => 'INR',
            2 => 'USD',
            3 => 'GBP',
            default => '-',
        };
    }

}
