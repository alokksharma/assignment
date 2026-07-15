<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }
}
