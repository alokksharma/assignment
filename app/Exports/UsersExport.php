<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use App\Exports\Traits\ExportableTemporaryStorage;
use App\Storage\TemporaryStorableExport;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Excel;

class UsersExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    Responsable,
    WithCustomChunkSize,
    TemporaryStorableExport,
    ShouldQueue
{
    use ExportableTemporaryStorage;

     /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private string $fileName = 'users_export.xlsx';

    /**
     * Optional Writer Type
     */
    private string $writerType = Excel::XLSX;

    public function __construct()
    {

    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function query(): Builder
    {
        return User::query()
            ->with(['company']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Role',
            'Email',
            'company_name',
            'created_at',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->roles->pluck('name')->join(', '),
            $user->email,
            $user->company?->name,
            $user->created_at,
        ];
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }
}
