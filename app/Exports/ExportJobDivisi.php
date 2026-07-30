<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportJobDivisi implements FromView
{
    public function __construct(public $data) {}

    public function view(): View
    {

        return view("excel.job.job-divisi", [
            "items" => $this->data,
        ]);
    }
}
