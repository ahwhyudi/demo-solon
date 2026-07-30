<?php

namespace App\Http\Controllers\Laporan\PekerjaanStaff\Home;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StatusJobOps;
use Illuminate\Http\Request;

class homeLaporanPekerjaanStaffController extends Controller {

    public function index(Request $request, $staffName = null) {

        $statusTable = (new StatusJobOps())->getTable();
        $dateColumn = $request->input('date_type') ?: 'updated_at';
    

        $query = StatusJobOps::query();
        if ($request->filled('start_date')) {
            $query->whereDate($dateColumn, '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate($dateColumn, '<=', $request->end_date);
        }


        $jobOps = $query->get(); 



        $applyDateFilters = function ($subquery) use ($request, $dateColumn) {
        
            if ($request->filled('start_date')) {
                $subquery->whereDate($dateColumn, '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $subquery->whereDate($dateColumn, '<=', $request->end_date);
            }
        };

        
    $users = User::select('users.id', 'users.name')
        ->when($staffName, function ($q) use ($staffName) {
            $q->where("users.name", $staffName);
        })
        ->addSelect([
            'total_pekerjaan' => StatusJobOps::selectRaw('count(*)')
                ->whereColumn('created_by', 'users.id')
                ->tap($applyDateFilters), // Reuse the filter logic
                
            'total_pekerjaan_selesai' => StatusJobOps::selectRaw('count(*)')
                ->whereColumn('created_by', 'users.id')
                ->whereRaw('LOWER(status) LIKE ?', ['%selesai%'])
                ->tap($applyDateFilters),
                
            'total_pekerjaan_belum_selesai' => StatusJobOps::selectRaw('count(*)')
                ->whereColumn('created_by', 'users.id')
                ->whereRaw('LOWER(status) NOT LIKE ?', ['%selesai%'])
                ->tap($applyDateFilters)
        ])
        ->orderBy('users.name', 'asc');

    $datas = [
        'users'  => $users->get(),
        'jobOps' => $jobOps,
    ];

    return view('pages.Laporan.PekerjaanStaff.Home.homeLaporanPekerjaanStaff', compact('datas'));
}

}