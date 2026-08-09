<?php

namespace App\Imports;

use App\Models\Proof;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataReconcileImport implements ToCollection, WithHeadingRow, WithChunkReading, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
          //  dd($row);
            Proof::where('3ASProjectID', $row['3asprojectid'])
                ->where('asr_hash_short', $row['asr_hash_short'])
                ->update([
                    'reconcile_status'  => (int) $row['reconcile_status'],
                ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.3asprojectid'    => 'required',
            '*.asr_hash_short' => 'required',
            '*.reconcile_status'         => 'required|in:0,1,2,3,4',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.3asprojectid.required' => '3ASProjectid is required.',
            '*.asr_hash_short.required' => 'Hash Short is required.',
            '*.reconil_status.in' => 'Status must be Pending, Approved or Rejected.',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
