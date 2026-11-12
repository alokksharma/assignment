<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorldLanguage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function demographics()
    {
        return $this->hasMany(Demographic::class, 'world_language_id');
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'language_id', 'language_id');
    }
}
