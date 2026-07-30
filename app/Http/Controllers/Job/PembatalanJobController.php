<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\BatalJobDivisi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PembatalanJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            "keterangan" => "required",
        ]);

        if ($validasi->fails()) {
            return redirect()->back()->withErrors([
                "error" => $validasi->errors()->first(),
            ])->with("error", $validasi->errors()->first());
        }

        try {
            $pembatalan = BatalJobDivisi::create([
                "job_divisi_id" => decodeHashIds($request->job_divisi_id),
                "keterangan" => $request->keterangan,
                "created_by" => Auth::user()->id
            ]);
        } catch (Exception $th) {

            return redirect()->back()->with("error", "Kesalahan Server : {$th->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = BatalJobDivisi::where("job_divisi_id", decodeHashIds($request->job_divisi_id))->first();

        if (!$item) {
            return redirect()->back()->with("error", "Data tidak ditemukan");
        }

        $item->update([
            "status" => $request->status,
            "approved_by" => Auth::user()->id
        ]);

        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
