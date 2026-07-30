<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\FormOrderLuarInvoice;
use App\Models\JobDivisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class DetailJobOrderLuarInvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = decodeHashIds($request->job_divisi);
        $jobDivisi = JobDivisi::find($id);
        $data = FormOrderLuarInvoice::where("job_divisi_id", $id)
            ->orderBy("created_at", "desc")
            ->get();

        return Inertia::render('Job/FormOrderLuarInvoice/JobDetailOrderLuarInv', [
            'data' => $data,
            "jobDivisi" => $jobDivisi
        ]);
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
        $job_id = decodeHashIds($request->job_id);

        DB::beginTransaction();

        try {
            $insertData = FormOrderLuarInvoice::create([
                "job_divisi_id" => $job_id,
                "harga_proses" => $request->harga,
                "harga_percepatan_proses" => $request->harga_percepat,
                "keterangan" => $request->keterangan,
                "proses" => $request->proses ?? "-",
            ]);

            DB::commit();

            Session::flash('success', 'Form Order Luar Invoice Berhasil Ditambahkan');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', "Terjadi kesalahan server : " . $th->getMessage());
            return redirect()->back();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = decodeHashIds($id);

        $item = FormOrderLuarInvoice::find($id);

        $item->delete();

        Session::flash('success', 'Data Berhasil Dihapus');
        return redirect()->back();
    }
}
