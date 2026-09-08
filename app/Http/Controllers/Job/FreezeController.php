<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\ApprovalFreez;
use App\Models\JobDivisi;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FreezeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = ApprovalFreez::orderBy("id", "desc")->paginate(12);
        return view("pages.Freeze.index", [
            'items' => $items
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
        $validasi = Validator::make($request->all(), [
            "job_id" => "required",
            "keterangan" => "required",
        ], [
            "required" => ":attribute harus diisi",
        ]);

        if ($validasi->fails()) {
            return redirect()->back()->with("error", $validasi->errors()->first())->withInput();
        }

        $jobDivisi = JobDivisi::find($request->job_id);

        if ($jobDivisi?->status !== "Akad") {
            // dd($jobDivisi, $jobDivisi?->status);
            return redirect()->back()->with("error", "Job tidak dapat di freeze");
        }

        DB::beginTransaction();

        try {


            $freezeData = ApprovalFreez::create([
                "job_divisi" => $request->job_id,
                "keterangan" => $request->keterangan,
                "created_by" => Auth::user()->id
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil simpan data');
            // dd($freezeData);
        } catch (Exception $th) {
            DB::rollBack();
            // dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = ApprovalFreez::with("jobDivisi")->findOrFail($id);
        $jobDivisi = $data->jobDivisi;

        $jobFormOrder = $jobDivisi->formOrder->groupBy("kategori");
        $dataPendukung = explode(",", $jobDivisi->jenisAkad->jenis_data);

        $fileAkad = $jobDivisi->fileJob->where("tipe", "foto_akad")->first();
        $fileSertifikat = $jobDivisi->fileJob->where("tipe", "sertifikat");


        return view("pages.Freeze.detail", [
            'freeze' => $data,
            "dataPendukung" => $dataPendukung,
            "jobDivisi" => $jobDivisi,
            "jobFormOrder" => $jobFormOrder,
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
        // dd($request->all());
        DB::beginTransaction();

        try {

            $data = ApprovalFreez::findOrFail($id);
            $jobDivisi = $data->jobDivisi;

            $status = "Disetujui";

            if (!$request->status) {
                $status = "Ditolak";
            } else if ($request->status == "Buka Freeze") {
                $data->end_date = now();
                $data->penambahan_sla = $request->tambah_sla;

                $newTanggalEstimasiSelesai = Carbon::parse($jobDivisi->tanggal_estimasi_selesai)->addDays((int)$request->tambah_sla);
                $newTanggalEstimasiSelesaiEksternal = Carbon::parse($jobDivisi->tanggal_estimasi_selesai_eksternal)->addDays((int)$request->tambah_sla);

                $jobDivisi->tanggal_estimasi_selesai = $newTanggalEstimasiSelesai;
                $jobDivisi->tanggal_estimasi_selesai_eksternal = $newTanggalEstimasiSelesaiEksternal;
                $jobDivisi->deleted_at = null;
                $status = "Buka Freeze";
                $jobDivisi->save();
            } else {
                $jobDivisi->delete();
                $data->start_date = now();
            }


            $data->approval_1 = Auth::user()->id;
            $data->status = $status;
            $data->save();

            // dd($data, "Data");
            DB::commit();

            return redirect()->route("berkas-bermasalah.freeze.index")->with("success", "Freeze Berhasil $status");
        } catch (Exception $th) {
            DB::rollBack();
            // dd($request->all(), $th);
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
