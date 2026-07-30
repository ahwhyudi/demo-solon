<?php

namespace App\View\Components\Job\detail;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ListObjekComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $listObjek = [])
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.job.detail.list-objek-component');
    }
}
