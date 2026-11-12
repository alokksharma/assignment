<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Survey extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function questions()
    {
        return $this->belongsToMany(
            Question::class,
            'survey_questions',
            'survey_id',
            'question_id',
            'id',
            'question_id'
        );
    }

    public function scopeWithQuestions()
    {
        return $this->questions
            ->where('project_id', $this->api_project_id)
            ->where('language_id', $this->language_id)
            ->values();
    }

    public function surveyQuestions()
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_id');
    }

    public function surveyQuotaQualifications()
    {
        return $this->hasMany(SurveyQuotaQualification::class, 'survey_id');
    }

    public function country()
    {
         return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function supplier()
    {
         return $this->belongsTo(Project::class, 'api_project_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    public function worldLanguage()
    {
        return $this->belongsTo(WorldLanguage::class, 'language_id', 'language_id');
    }

    public function demographics()
    {
        return $this->hasManyThrough(
            Demographic::class,
            WorldLanguage::class,
            'language_id',
            'world_language_id',
            'language_id',
            'id'
        )->select([
            'demographics.id',
            'demographics.world_language_id',
            'demographics.demographic_name',
            'demographics.question_id',
            'demographics.question_text',
            'demographics.question_type',
            'demographics.answer_codes',
        ]);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {

            $q->where('project_name', 'like', "%{$search}%")
            ->orWhere('project_code', 'like', "%{$search}%")
            ->orWhere('project_id', 'like', "%{$search}%")
            ->orWhereRaw(
              "CONCAT('3AS_API-', DATE_FORMAT(created_at, '%m%Y'), '-', LPAD(id,4,'0')) LIKE ?",
              ["%{$search}%"]
          )
            ->orWhereHas('language', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        });
    }

    protected function getSurveyCodeAttribute()
    {

        return '3AS_API' . '-' .
            optional($this->created_at)->format('mY') . '-' .
            str_pad($this->id, 4, '0', STR_PAD_LEFT);

    }

    public function vendorProjectAssign()
    {
        return $this->hasMany(VendorProjectAssign::class, 'survey_id');

    }

    public function surveyQuestionWithOptions($projectId, $questionIds)
    {
        if (empty($projectId) || empty($questionIds)) {
            return collect();
        }
        $questions = Question::where('project_id', $projectId)
                    ->whereIn('question_id', $questionIds)
                    ->get();
        return $questions;

    }

}
