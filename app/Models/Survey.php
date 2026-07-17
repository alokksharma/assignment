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

    public function surveyQuestions()
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_id');
    }

    public function country()
    {
         return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
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
            ->orWhereHas('country', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
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
}
