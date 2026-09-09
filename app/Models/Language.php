<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
    ];

    public function scopeForSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('short_name', 'like', "%{$search}%");
    }

}
