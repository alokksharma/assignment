<?php

namespace App\Exports;

use App\Models\Proof;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\User;

class ProofExport implements FromQuery, WithHeadings, WithMapping, WithCustomChunkSize
{
    protected $filters;
    protected $user;

    public function __construct(array $filters = [], User $user)
    {
        $this->filters = $filters;
        $this->user = $user;
    }

    public function query()
    {
        return Proof::query()
            ->when($this->filters['three_as_project_id'] ?? null, function ($query, $value) {
                $query->where('3ASProjectID', $value);
            })
           ->when($this->filters['vendor_project_id'] ?? null, function ($query, $value) {
                $query->whereHas('vendorProjectAssign', function ($sub) use ($value) {
                    $sub->where('project_vendor_id', $value);
                });
            })
            ->when($this->filters['from_date'] ?? null, function ($query, $value) {
                $query->whereDate('created_at', '>=', $value);
            })
            ->when($this->filters['to_date'] ?? null, function ($query, $value) {
                $query->whereDate('created_at', '<=', $value);
            })
            ->when($this->user->role === 'vendor', function ($query) {
                $query->where('vendor_id', $this->user?->vendor->id);
            })
            ->select([
                'id',
                'ccode',
                '3ASProjectID',
                'vendor_id',
                'status',
                'reconcile_status',
                'asr_hash_short',
                'created_at',
                'updated_at',
            ]);
    }

    public function map($proof): array
    {
        return [
            $proof->id,
            $proof->ccode,
            $proof->{'3ASProjectID'},
            $proof->vendor_id,
            $this->getStatus($proof->status),
            $this->getReconcileStatus($proof->reconcile_status),
            $proof->asr_hash_short,
            optional($proof->created_at)->format('d-m-Y H:i:s'),
            optional($proof->updated_at)->format('d-m-Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Ccode',
            '3AS Project ID',
            'Vendor ID',
            'Status',
            'Reconcile Status',
            'ASR Hash Short',
            'Created At',
            'Updated At'
        ];
    }

    public function chunkSize(): int
    {
        return 200;
    }

    private function getStatus($status)
    {
        return match ($status) {
            1 => 'Complete',
            2 => 'Terminate',
            3 => 'Quota Full',
            4 => 'Quality',
            default => 'Pending',
        };
    }

    private function getReconcileStatus($status)
    {
        return match ($status) {
            1 => 'Complete',
            2 => 'Terminate',
            3 => 'Quota Full',
            4 => 'Quality',
            default => 'Pending',
        };
    }
}
