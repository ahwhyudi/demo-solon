<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WasiatController extends Controller
{
    public function index()
    {
        return view('pages.Job.Wasiat.index');
    }
}
