<?php

namespace App\Http\Controllers\Arsip;

use App\Http\Controllers\Controller;
use App\Services\Job\JobDivisiIndexServis;
use Illuminate\Http\Request;

class ArsipWarkahController extends Controller
{
    public function __construct(protected JobDivisiIndexServis $jobDivisiIndexServis) {}

    public function index(Request $request)
    {
        return view("pages.arsip.warkah.index", $this->jobDivisiIndexServis->execute($request, [
            "completed_only" => true,
        ]));
    }
}
