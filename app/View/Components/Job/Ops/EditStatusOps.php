<?php

namespace App\View\Components\Job\Ops;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class EditStatusOps extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $key,
        public $formOrder,
        public $statusOps,
        public $jobDivisi,
        public $userOps,
        public $userPermission = []
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $statusOps = [
            "Penugasan" => "Pengajuan Biaya",
            "Pengerjaan" => "Selesai",
            "Selesai Operasional" => "Selesai",
        ];
        $lastStatus = $this->statusOps->last()->status ?? "Belum diproses";
        $currentStatus = $statusOps[$lastStatus] ?? "Penugasan";

        $showButton = false;

        // if (in_array("$currentStatus ops", $this->userPermission)) {
        //     $showButton = true;
        // }
        if ((int)$this->formOrder->penugasan_user === (int)Auth::user()->id || (int)Auth::user()->id === 1) {
            $showButton = true;
        }

        if ($lastStatus === "Selesai") {
            $showButton = false;
        }


        return view('components.job.ops.edit-status-ops', [
            "key" => $this->key,
            "formOrder" => $this->formOrder,
            "lastStatus" => $lastStatus,
            "jobDivisi" => $this->jobDivisi,
            "currentStatus" => $currentStatus,
            "userOps" => $this->userOps,
            "showButton" => $showButton
        ]);
    }
}
