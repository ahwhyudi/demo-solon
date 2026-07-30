<?php

namespace App\Http\Controllers\Export;

use App\Exports\ExportDebiturJobDivisi;
use App\Exports\ExportJobDivisi;
use App\Http\Controllers\Controller;
use App\Models\Debitur;
use App\Models\JobDivisi;
use App\Models\JobDivisiFormOrder;
use App\Models\Setting;
use App\Models\skNotaris;
use App\Models\skPpat;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportJobDivisiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->q;

        $items = JobDivisi::with("bank", 'divisiYangDituju', "jenisAkad", "debitur", "pembatalan.user", "pembatalan.user2")
            ->orderBy("is_pending", "desc")
            ->orderBy("id", "desc")
            ->when($search, function ($query) use ($search) {
                return $query->where("kode", "like", "%$search%");
            })
            ->get();


        return Excel::download(new ExportJobDivisi($items), 'job-divisi.xlsx');
    }

    public function debitru($id)
    {
        $items = Debitur::where("job_divisi_id", $id)
            ->orderBy("id", "desc")
            ->get();

        return Excel::download(new ExportDebiturJobDivisi($items), 'debitur-job-divisi.xlsx');
    }
    public function printQuotation($id)  {

    $dataNotaris = Setting::first();
    $dataSKNotaris = skNotaris::first();
    $dataSKPPAT = skPpat::first();
    $job_divisi = JobDivisi::findOrFail($id);
    $formOrders = JobDivisiFormOrder::where('job_divisi_id', $job_divisi->id)->get();
    $formDebitur = Debitur::where('job_divisi_id', $job_divisi->id)->get();

    Carbon::setLocale('id');
    $formattedAddress = preg_replace('/\s+(Jl\.)/u', "\n$1", $dataNotaris->alamat);



    $data = [
        'notaris_name' => $dataNotaris->nama_perusahaan ?? "Nama Notaris Anda",
        'sk_kumham'    => 'SK. MENTERI HUKUM DAN HAK ASASI MANUSIA RI NOMOR: ' . ($dataSKNotaris->sk_kemenkumham ?? '-'),      
        'tanggal_sk_kumham' => 'TANGGAL ' . (isset($dataSKNotaris->tanggal_sk) 
        ? strtoupper(Carbon::parse($dataSKNotaris->tanggal_sk)->isoFormat('D MMMM YYYY')) 
        : '-'),
        'sk_bpn'       => 'SK. KEPALA BADAN PERTANAHAN NASIONAL RI, NOMOR: ' . ($dataSKPPAT->sk_kemenkumham ?? '-'),   
        'tanggal_sk_bpn' => 'TANGGAL ' . (isset($dataSKPPAT->tanggal_sk) 
        ? strtoupper(Carbon::parse($dataSKPPAT->tanggal_sk)->isoFormat('D MMMM YYYY')) 
        : '-'),
        'client_name' => $formDebitur->first()->nama ?? "-",  
        'notaris_location' => "TANGERANG",
        'notaris_address_location' => $formattedAddress,        
        'notaris_phone_number'     => $dataNotaris->telepon ?? "Nomor Telepon Notaris Anda",
        'notaris_email'     => $dataNotaris->email ?? "Nomor Telepon Notaris Anda",
        'date'         => date('d F Y'),
        
        'items' => $formOrders->map(function ($item) {
            return [
                'nama'   => $item->nama,
                'total'  => $item->harga_jual - $item->diskon,
            ];
        })
    ];

    $data['total_bayar'] = $data['items']->sum('total');

    $pdf = Pdf::loadView('pdf.quotationJobDivisiPDF', $data)->setPaper('A4', 'portrait');
    return $pdf->stream('invoice.pdf');
    }
}