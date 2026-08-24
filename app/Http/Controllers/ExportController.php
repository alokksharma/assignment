<?php

namespace App\Http\Controllers;

use App\Models\Export;
use App\Exports\UsersExport;
use App\Jobs\ProcessUsersExport;
use App\Storage\UsersTemporaryExport;
use App\Storage\TemporaryExport;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ExportUsersJob;
use Laravel\Mcp\Request;
use Maatwebsite\Excel\Excel;

class ExportController extends Controller
{
    public function create(Request $request)
    {
         $export = new UsersExport();

        if ($request->has('_raw')) {
            return $export->raw(Excel::CSV);
        }

        return $export;

        $export = (new UsersTemporaryExport())->store();
            return response()->json([
                'id' => $export->getId(),
                'status' => 'pending',
                'url' => null,
            ]);
    }

    public function export()
    {
        ExportUsersJob::dispatch();

        return response()->json([
            'message' => 'Export started'
        ]);
    }

    /**
     * Check export status
     */
    public function status(string $id)
    {
        $data = UsersTemporaryExport::get($id);

        if (! $data) {
            return response()->json([
                'status' => 'pending',
                'url' => null,
            ], 404);
        }

        return response()->json($data);
    }

    public function download(Export $export)
    {
        abort_if($export->status !== 'completed', 404);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if (! $disk->exists($export->file_path)) {
            abort(404, 'Export file not found.');
        }

        $filename = basename($export->file_path);

        return $disk->download($export->file_path, $filename);
    }
}
