<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\JobDivisi;
use App\Models\JobDivisiFinance;
use App\Models\StatusJobOps;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FinanceJobDivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = JobDivisiFinance::orderBy("id", 'desc')
            ->with(
                "jobDivisi.userOps",
                "jobDivisi.jenisAkad",
                'jobDivisi.debitur',
                "jobDivisi.objek.desa.kecamatan.kota",
                "formOrder.pembatalanItemDetail.pembatalanItem",

                "user",
                "pembuat"
            )
            ->whereHas("jobDivisi")
            ->where("tipe", $request->type)
            ->get();


        $type = $request->type;

        $jobDivisi = JobDivisi::query()->get();

        return view("pages.Finance.index", [
            "items" => $items,
            "type" => $type,
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
        //
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
        $item = JobDivisiFinance::with("formOrder")->find($id);

        if (!$item) {
            return redirect()->back()->with("error", "Data tidak ditemukan");
        }

        DB::beginTransaction();

        try {
            $formOrder = $item->formOrder;


            $item->update([
                "total" => str_replace(".", "", $request->harga_proses),
                "user_id" => Auth::user()->id,
                "status" => $request->status
            ]);
            // dd($item->toArray());

            if ($request->status === "Disetujui") {
                $statusOps = StatusJobOps::query()
                    ->where("job_divisi_form_order_id", $item->job_divisi_form_order_id)
                    ->orderBy("id", "desc")
                    ->first();

                $statusOpsNew = $statusOps->replicate();
                $statusOpsNew->status = "Pengerjaan";
                $statusOpsNew->keterangan = "Disetujui " . Auth::user()->name;
                $statusOpsNew->save();

                // dd($statusOpsNew->toArray());

                $formOrder->update([
                    "harga_modal" => $item->total,
                    "harga_proses" => $item->total,
                ]);
            }

            // dd($item->toArray(), "commit");
            DB::commit();
            return redirect()->back()->with("success", "Data berhasil diubah");
        } catch (Exception $th) {
            DB::rollBack();

            return redirect()->back()->with("error", "Gagal melakukan perubahan, Coba beberapa saat lagi");
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
