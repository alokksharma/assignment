<?php

namespace App\Jobs;

use Throwable;
use App\Exports\UsersExport;
use App\Storage\TemporaryExport;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessUsersExport implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        public string $id
    ) {
    }

    public function handle(): void
    {
        try {

            $file = "exports/{$this->id}.xlsx";

            Excel::store(
                new UsersExport(),
                $file,
                'public'
            );

            TemporaryExport::complete(
                $this->id,
                $file
            );

        } catch (Throwable $e) {

            TemporaryExport::failed($this->id);

            throw $e;
        }
    }
}
