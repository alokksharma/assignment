<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demographic extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'answer_codes' => 'array',
    ];

    public function worldLanguage()
    {
        return $this->belongsTo(WorldLanguage::class, 'world_language_id');
    }
}
