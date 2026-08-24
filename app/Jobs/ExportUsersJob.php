<?php

namespace App\Jobs;

use App\Exports\UsersExport;
use App\Events\ExportCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ExportUsersJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct() {}

    public function handle()
    {
        $fileName = 'users-' . Str::uuid() . '.xlsx';

        Excel::store(
            new UsersExport,
            "exports/{$fileName}",
            'local'
        );

        event(new ExportCompleted(
            $fileName
        ));
    }
}
