<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $supplierId = $request->header('Supplierid') ?? (optional($this->supplier)->id ?? '');

        return [
            // 'project_id'           => $this->supplier?->code ?? 'mx'.''.$this->project_id,
            // 'project_code'         => $this->project_code,
            // 'project_name'         => $this->project_name,
            'country'           => $this->country,
            'language'          => $this->language,
            'category'             => $this->category,
            'currency'             => $this->currency,
            'audience_type'        => $this->audience_type,
            'pii_collection'       => $this->pii_collection,
            'application_download' => $this->application_download,
            'facial_coding'        => $this->facial_coding,
            'project_ir'           => $this->project_ir,
            'project_loi'          => $this->project_loi,
           // 'cpi'                  => optional($this->vendorProjectAssign()->latest()->first())->temp_cpi,
            'quota'                => $this->quota,
            'live_click_quota'     => $this->live_click_quota,
            'test_click_quota'     => $this->test_click_quota,
            'project_start_date'   => $this->project_start_date,
            'project_end_date'     => $this->project_end_date,
            'test_url' => config('app.url') . '/s2c?3ASPID=' . $this->survey_code
                . '&country=' . urlencode(optional($this->country)->name)
                . '&link=test&ccode=[PanelistID]&supplier=' . $supplierId,
            'live_url' => config('app.url') . '/s2c?3ASPID=' . $this->survey_code
                . '&country=' . urlencode(optional($this->country)->name)
                . '&link=live&ccode=[PanelistID]&supplier=' . $supplierId,
            // 'test_url'             => $this->test_url,
            // 'status'               => $this->status,
            'device_type'          => $this->device_type,
            'target_spec'          => $this->target_spec,
            'questions'          => $this->questions,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
