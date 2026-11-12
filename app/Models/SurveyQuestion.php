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
        return $this->hasMany(SurveyQuestionOption::class, 'question_id');
    }

    public function surveyOption()
    {
        return $this->hasMany(SurveyQuestionOption::class, 'survey_question_id');
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id')
           ->whereExists(function ($q) {
            $q->selectRaw(1)
              ->from('surveys as s')
              ->join('survey_questions as sq', 'sq.survey_id', '=', 's.id')
              ->whereColumn('sq.question_id', 'questions.question_id')
              ->whereColumn('s.api_project_id', 'questions.project_id')
              ->whereColumn('s.language_id', 'questions.language_id');
        });
    }

    public function surveyQuestionWithOptions($projectId, $questionIds)
    {

        $questions = Question::whereIn('project_id', $projectIds)
                    ->whereIn('question_id', $questionIds)
                    ->get();
        return $questions;

    }

}
