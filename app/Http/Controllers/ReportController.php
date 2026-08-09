<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\DataReconcileImport;
use App\Exports\ProofExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Proof;
use App\Models\VendorProjectAssign;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request){

        $survey_id =  (int) last(explode('-', $request->input('search')));
        $threeAsProjects = Proof::query()
                            ->when(Auth::user()->role === 'vendor', function ($query) {
                                $query->where('vendor_id', Auth::user()->vendor_id);
                            })
                            ->select('3ASProjectID')
                            ->distinct()
                            ->get();
        $vendorProjects  = VendorProjectAssign::query()
                ->when(Auth::user()->role === 'vendor', function ($query) {
                    $query->where('vendor_id', Auth::user()->vendor_id);
                })->get();

       if (!$request->hasAny([
            'three_as_project_id',
            'vendor_project_id',
            'from_date',
            'to_date'
        ])) {
            $selectedProjectId = optional($threeAsProjects->first())->getAttribute('3ASProjectID');
        } else {
            $selectedProjectId = $request->three_as_project_id;
        }
       $proofs = Proof::query()
                ->when(Auth::user()->role === 'vendor', function ($query) {
                    $query->where('vendor_id', Auth::user()->vendor_id);
                })
            ->selectRaw("
                `3ASProjectID`,
                SUM(status = 1) as complete,
                SUM(status = 2) as terminate,
                SUM(status = 3) as quotafull,
                SUM(status = 4) as quality,
                SUM(status = 5) as ir,
                SUM(status = 6) as loi,
                MIN(created_at) as created_at,
                MAX(updated_at) as updated_at,
                AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_minutes
            ")
            ->with('vendorProjectAssign')
            ->when( $selectedProjectId, fn ($q, $value) =>
                $q->where('3ASProjectID', $value))
            ->when($request->vendor_project_id, function ($q) use ($request) {
                $q->whereHas('vendorProjectAssign', function ($sub) use ($request) {
                    $sub->where('project_vendor_id', $request->vendor_project_id);
                });
            })->when($this->filters['from_date'] ?? null, function ($query, $value) {
                $query->whereDate('created_at', '>=', $value);
            })
            ->when($this->filters['to_date'] ?? null, function ($query, $value) {
                $query->whereDate('created_at', '<=', $value);
            })
             ->groupBy('3ASProjectID')
            ->get();


           $reports = $proofs
            ->groupBy('3ASProjectID')
            ->map(function ($rows, $projectId) {
                $total =  $rows->sum('complete') +  $rows->sum('terminate');
                 //median
            $projectMinutes = Proof::where('3ASProjectID', $projectId)
            ->get()
            ->map(fn ($proof) => $proof->created_at->diffInMinutes($proof->updated_at))
            ->sort()
            ->values();
             $count = $projectMinutes->count();

                if ($count === 0) {
                    $projectMedian = 0;
                } elseif ($count % 2) {
                    $projectMedian = $projectMinutes[intdiv($count, 2)];
                } else {
                    $projectMedian = (
                        $projectMinutes[$count / 2 - 1] +
                        $projectMinutes[$count / 2]
                    ) / 2;
                }

                 $projectAverage = gmdate('H:i:s', round($rows->avg('avg_minutes'), 2));
                 $projectMedian = gmdate('H:i:s', $projectMedian);
                return [
                    'project_id' => $projectId,

                    'complete'  => $rows->sum('complete'),
                    'terminate' => $rows->sum('terminate'),
                    'quotafull' => $rows->sum('quotafull'),
                    'quality'   => $rows->sum('quality'),
                    'ir'        => $total > 0 ? round(($rows->sum('complete') / $total) * 100, 2) : 0,
                    'average' => $projectAverage . ' min',
                    'median'  => $projectMedian . ' min',

                    'vendors' => $rows->map(function ($row) {
                        $total  = $row->complete + $row->terminate;
                         $minutes = Proof::where('3ASProjectID', $row->{"3ASProjectID"})
                        ->where('vendor_id', $row->vendor_id)
                        ->get()
                        ->map(fn($proof) => $proof->created_at->diffInMinutes($proof->updated_at))
                        ->sort()
                        ->values();
                        $count = $minutes->count();
                        $medianSeconds = 0;

                        if ($count > 0) {
                            $medianSeconds = $count % 2
                                ? $minutes[intdiv($count, 2)]
                                : ($minutes[$count / 2 - 1] + $minutes[$count / 2]) / 2;
                        }
                        $median = gmdate('H:i:s', (int) round($medianSeconds));

                        return [
                            'vendor_project_id' => optional($row->vendorProjectAssign)->project_vendor_id,
                            'complete'  => $row->complete,
                            'terminate' => $row->terminate,
                            'quotafull' => $row->quotafull,
                            'quality'   => $row->quality,
                            'ir'        => $total > 0
                                ? round(($row->complete / $total) * 100, 2)
                                : 0,

                            'average' => gmdate('H:i:s', (int) round($row->avg_seconds)),
                            'median'  => $median ,

                            'created_at'=> $row->created_at,
                            'updated_at'=> $row->updated_at,
                        ];
                    })->values(),
                ];
            })->values();
         return view('vendor_project_assign.report', compact('threeAsProjects', 'vendorProjects', 'reports', 'selectedProjectId'));
    }

    public function import(Request $request){
         $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new DataReconcileImport, $request->file('import_file'));

        return response()->json([
            'message' => 'Data import completed successfully'
        ]);

    }

    public function export(Request $request)
    {

        return Excel::download(
            new ProofExport($request->all(), auth()->user()),
            'proof-report.xlsx'
        );
    }
}
