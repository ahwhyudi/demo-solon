<?php

namespace App\Http\Controllers\Job\Bermasalah;

use Exception;
use Carbon\Carbon;
use App\Models\JobPending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = JobPending::orderBy("id", "desc")->with("jobDivisi")->paginate(12);

        return view("pages.job-pending.index", compact("items"));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pending = JobPending::with("jobDivisi")->findOrFail($id);

        $jobDivisi = $pending->jobDivisi;

        $jobFormOrder = $jobDivisi->formOrder->groupBy("kategori");
        $dataPendukung = explode(",", $jobDivisi->jenisAkad->jenis_data);

        $fileAkad = $jobDivisi->fileJob->where("tipe", "foto_akad")->first();
        $fileSertifikat = $jobDivisi->fileJob->where("tipe", "sertifikat");

        return view("pages.job-pending.detail", [
            "pending" => $pending,
            "jobDivisi" => $pending->jobDivisi,
            "jobFormOrder" => $jobFormOrder,
            "dataPendukung" => $dataPendukung,
            "fileAkad" => $fileAkad,
            "fileSertifikat" => $fileSertifikat
        ]);
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

        DB::beginTransaction();

        try {
            $data = JobPending::with("jobDivisi")->findOrFail($id);
            $jobDivisi = $data->jobDivisi;

            $data->end_date = now();
            $data->penambahan_sla = $request->tambah_sla;
            $data->save();

            $newTanggalEstimasiSelesai = Carbon::parse($jobDivisi->tanggal_estimasi_selesai)->addDays((int)$request->tambah_sla);
            $newTanggalEstimasiSelesaiEksternal = Carbon::parse($jobDivisi->tanggal_estimasi_selesai_eksternal)->addDays((int)$request->tambah_sla);

            $jobDivisi->tanggal_estimasi_selesai = $newTanggalEstimasiSelesai;
            $jobDivisi->tanggal_estimasi_selesai_eksternal = $newTanggalEstimasiSelesaiEksternal;
            $jobDivisi->is_pending = 0;
            $jobDivisi->status = "Akad";
            $jobDivisi->deleted_at = null;

            $jobDivisi->save();

            DB::commit();

            return redirect()->back()->with("success", "Pending Berhasil Dibuka");
        } catch (Exception $th) {
            return redirect()->back()->with("error", "Gagal buka pending, terjadi kesalahan server");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
