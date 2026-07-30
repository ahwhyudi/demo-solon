<?php

namespace App\Http\Controllers\Export;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class DetailPekerjaanStaffExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $items;

    /**
     * @param $items
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }


    public function map($row): array {

        $tanggalMulai = $row->created_at ? Carbon::parse($row->created_at)->format('l, d-F-y, H:i:s') : '-';    
        $tanggalSelesai = $row->next_created_at ? Carbon::parse($row->next_created_at)->format('l, d-F-y, H:i:s') : '-';
        
        return [
            $row->user_name ?? '-',
            $row->kode ?? '-',
            $row->nama ?? '-',
            $row->keterangan ?? '-',
            $tanggalMulai,
            $tanggalSelesai,
        ];
    }
    
    public function headings(): array
    {
        return [
            'Nama Staff',
            'Nomor Job Divisi',
            'Pekerjaan',
            'Keterangan proses',
            'Tanggal mulai',
            'Tanggal selesai',
        ];
    }
}