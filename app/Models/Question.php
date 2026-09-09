<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'mapped_options' => 'array',
    ];

    public function options()
    {
        return $this->belongsTo(SurveyQuestionOption::class, 'question_id', 'question_id');
    }

    public function scopeForSearch($query, $search)
    {
        return $query->where('question', 'like', "%{$search}%");
    }
}
