<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VendorProjectAssign extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->whereHas('survey', function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('project_id', 'like', "%{$search}%")
                    ->orWhereHas('country', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('language', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })->orWhereHas('vendor', function ($q) use ($search) {
                $q->where('vendor_id', 'like', "%{$search}%");
            })
            ->orWhere('three_as_project_id', 'like', "%{$search}%")
            ->orWhere('project_vendor_id', 'like', "%{$search}%");
        });
    }
}
