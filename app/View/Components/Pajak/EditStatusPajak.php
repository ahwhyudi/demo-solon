<?php

namespace App\View\Components\Pajak;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditStatusPajak extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $formOrder,
        public $jobDivisi,
        public $statusJobOps,
        public $key
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $currentStatus = $this->statusJobOps->last()->status ?? "Belum diproses";

        return view('components.pajak.edit-status-pajak', compact("currentStatus"));
    }
}
