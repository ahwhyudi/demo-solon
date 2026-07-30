<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\JobDivisi;

class InvoiceHistoryController extends Controller
{
    public function index()
    {
        $items = JobDivisi::with([
            'jenisAkad',
            'pembuat',
            'perwakilanAkad',
            'penanggungJawab'
        ])
        ->where('status', 'selesai')
        ->latest()
        ->paginate(20);

        return view(
            'pages.Laporan.job-divisi-history.index',
            compact('items')
        );
    }
}