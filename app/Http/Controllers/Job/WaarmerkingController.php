<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WaarmerkingController extends Controller
{
    public function index()
    {
        return view('pages.Job.Waarmerking.index');
    }
}
