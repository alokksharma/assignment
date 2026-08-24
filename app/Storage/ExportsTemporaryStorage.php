<?php

namespace App\Storage;

use App\Storage\TemporaryStorableExport;
use Illuminate\Foundation\Bus\PendingDispatch;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @template TExport of TemporaryStorableExport
 */
class ExportsTemporaryStorage extends TemporaryStorage
{
    /** @var TExport */
    protected $export;

    /**
     * @param  TExport  $export
     */
    public function __construct(TemporaryStorableExport $export)
    {
        $this->export = $export;
    }

    public function init($filePath, $disk): PendingDispatch
    {
        return Excel::queue($this->export, $filePath, $disk, null, [
            'visibility' => self::VISIBILITY,
        ]);
    }

    /**
     * Get the filename
     */
    public function getFileNameToStore(): string
    {
        return $this->export->getFileName();
    }
}
