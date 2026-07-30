<?php

namespace App\View\Components\job\detail;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ListBadanUsahaPembeliComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $listBadanUsahaPembeli = [])
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.job.detail.list-badan-usaha-pembeli-component');
    }
}
