<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlHitCount extends Model
{
    use HasFactory;
    protected $fillable = [
        'short_url_id',
        'hit_count',
    ];
}
