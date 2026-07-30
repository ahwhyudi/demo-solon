<?php

namespace App\Http\Controllers\Laporan\PekerjaanStaff\Detail;

use App\Http\Controllers\Controller;
use App\Models\StatusJobOps;
use App\Models\JobDivisiFormOrder;
use App\Models\JobDivisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Maatwebsite\Excel\Facades\Excel;

class detailLaporanPekerjaanStaffController extends Controller {
    
    public function index(Request $request, $encryptedId) {
        
        $data = $this->getBaseQuery($request, $encryptedId);
        $user = $data['user'];
        $jobOps = $data['query']->paginate(100)->withQueryString();

        $items = $jobOps->getCollection();
        foreach ($items as $index => $item) {
            $item->next_created_at = isset($items[$index + 1]) ? $items[$index + 1]->created_at : null;
        }
        $jobOps->setCollection($items);

        return view('pages.Laporan.PekerjaanStaff.Detail.detailLaporanPekerjaanStaff', [
            'datas' => ['user' => $user, 'jobOps' => $jobOps]
        ]);
    }
    
    public function exportDetailLaporanPekerjaanToExcel(Request $request, $encryptedId) {
        
        $data = $this->getBaseQuery($request, $encryptedId);
        $user = $data['user'];
        $items = $data['query']->get(); // This is already a Collection

        foreach ($items as $index => $item) {

            if ($index === 0) {
                $item->created_at = $item->form_created_at; 
            }
            
            $item->next_created_at = isset($items[$index + 1]) ? $items[$index + 1]->created_at : null;
            
            $item->user_name = $user->name;
            $item->keterangan = ($item->status_penolakan) ? 'Data ditolak - ' . $item->status_penolakan : $item->status;
    }
    
    $fileName = 'Laporan_Pekerjaan_' . str_replace(' ', '_', $user->name) . '.xlsx';
    
    return Excel::download(new \App\Http\Controllers\Export\DetailPekerjaanStaffExport($items), $fileName);
}

    private function getBaseQuery(Request $request, $encryptedId) {
        try {
            $id = Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        $user = User::findOrFail($id);
        $statusTable = (new StatusJobOps())->getTable();
        $formOrderTable = (new JobDivisiFormOrder())->getTable();
        $divisiTable = (new JobDivisi())->getTable();

        $query = StatusJobOps::query()
            ->leftJoin($formOrderTable, $formOrderTable.'.id', '=', $statusTable.'.job_divisi_form_order_id')
            ->leftJoin($divisiTable, $divisiTable.'.id', '=', $formOrderTable.'.job_divisi_id')
            ->where($statusTable.'.created_by', $id)
            ->select(
                $statusTable.'.*',
                $divisiTable.'.kode',
                $formOrderTable.'.nama',
                $formOrderTable.'.created_at as form_created_at' 
            );

        $dateColumn = $request->input('date_type'); 
        if ($request->filled('start_date')) {
            $query->whereDate($statusTable . '.updated_at' . $dateColumn, '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate($statusTable . '.updated_at' . $dateColumn, '<=', $request->end_date);
        }

        if ($request->input('sort') == 'desc') {
            $query->orderBy($statusTable . '.id', 'desc');
        } else {
            $query->orderBy($statusTable . '.id', 'asc');
        }

        return ['query' => $query, 'user' => $user];
    }
}