<?php

namespace App\View\Components\Job\Akta;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditAkta extends Component
{
    public function __construct(
        public $formOrder,
        public $jobDivisi,
        public $statusJobOps,
        public $tipe,
        public $users,
        public $key
    ) {
        //
    }

    public function render(): View|Closure|string
    {
        $tipe = $this->tipe ?? 'notaris';

        $tipeConfig = $this->tipe ?? 'notaris';
        if ($tipe === "notaris" || $tipe === "ppat") {
            $tipeConfig = "notaris";
        }

        $client = config('app.notaris', 'default');

        $workflow = config("workflow.akta.$client.$tipeConfig")
            ?? config("workflow.akta.default.$tipeConfig")
            ?? [];

        $steps = collect($workflow)->pluck('name')->toArray();

        $currentStatus = $this->statusJobOps->last()->status ?? 'Belum diproses';

        $nextStep = $this->getNextStep($workflow, $currentStatus);

        $nextStatus = $nextStep['name'] ?? 'Selesai';

        $isApproval = $nextStep['approval']['enabled'] ?? false;
        $isPenugasan = $nextStep['penugasan'] ?? false;

        $approvalLabel = $nextStep['approval']['label'] ?? 'Approve';
        $previousStep = $this->getPreviousStep($workflow, $nextStatus);

        $rejectStatus = $previousStep['name'] ?? $currentStatus;

        return view('components.job.akta.edit-akta', [
            'workflow' => $workflow,
            'steps' => $steps,
            'currentStatus' => $currentStatus,
            'nextStatus' => $nextStatus,
            'nextStep' => $nextStep,
            'isApproval' => $isApproval,
            'approvalLabel' => $approvalLabel,
            "rejectStatus" => $rejectStatus,
            "users" => $this->users,
            "isPenugasan" => $isPenugasan
        ]);
    }

    private function getNextStep(array $workflow, string $currentStatus): ?array
    {
        if ($currentStatus === 'Belum diproses') {
            return $workflow[0] ?? null;
        }

        $currentIndex = collect($workflow)->search(function ($step) use ($currentStatus) {
            return ($step['name'] ?? null) === $currentStatus;
        });

        if ($currentIndex === false) {
            return $workflow[0] ?? null;
        }

        return $workflow[$currentIndex + 1] ?? null;
    }

    private function getPreviousStep(array $workflow, string $nextStatus): ?array
    {
        $currentIndex = collect($workflow)->search(function ($step) use ($nextStatus) {
            return ($step['name'] ?? null) === $nextStatus;
        });

        if ($currentIndex === false) {
            return null;
        }

        return $workflow[$currentIndex - 2] ?? null;
    }
}
