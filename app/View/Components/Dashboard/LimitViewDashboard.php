<?php

namespace App\View\Components\Dashboard;

use App\Models\NomorPpat;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LimitViewDashboard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $satuMingguLagi = Carbon::now()->addWeek();
        $nomorPpats = NomorPpat::query()
            ->with("formOrder.jobDivisi")
            ->whereDate('tanggal_expired', '<=', $satuMingguLagi)
            ->orderBy('tanggal_expired')
            ->get();
        return view('components.dashboard.limit-view-dashboard', compact('nomorPpats'));
    }
}
