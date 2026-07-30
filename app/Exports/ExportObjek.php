<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportObjek implements FromView
{
    public function __construct(public $data)
    {
    } 

    public function view(): View
    {
        return view("excel.job.objek-job-divisi", [
            "items" => $this->data
        ]);
    }
}