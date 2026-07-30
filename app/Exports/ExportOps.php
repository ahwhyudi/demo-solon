<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportOps implements FromView
{
    public function __construct(public $data) {}

    public function view(): View
    {

        return view("excel.job.job-ops", [
            "items" => $this->data,
        ]);
    }
}
