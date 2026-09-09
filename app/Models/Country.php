<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'country_languages');
    }

    public function scopeForSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('short_name', 'like', "%{$search}%")
            ->orWhere('iso_code', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%");
    }
}
