<?php

namespace App\Http\Controllers\Job\Pajak;

use App\Models\User;
use Inertia\Inertia;
use App\Models\StatusJobOps;
use Illuminate\Http\Request;
use App\Models\JobDivisiFormOrder;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Job\UpdateStatusJobDivisiController;
use App\Models\JobDivisiFinance;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataPajakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipe = "pajak";
        $items = JobDivisiFormOrder::with([
            "jobDivisi.jenisAkad",
            "jobDivisi.objek",
            "jobDivisi.debitur",
            "jobDivisi.listBank",
            "jobDivisi.finance",
            "statusJobOps.createdBy",
            "statusJobOps.user",
            "nomorPpat"
        ])
            ->orderBy("id", "desc")
            ->whereNotIn("status", ["rejected", "Dibatalkan"])
            // ->where("status", "!=", "pending")
            ->where('kategori', $tipe) // ✅ legalisasi, pajak, akta ['notaris', 'ppat', 'legalisasi']
            ->whereHas("jobDivisi")
            ->paginate(10);

        $userOps = User::orderBy("name", "asc")->get()->map(function ($user) {
            return [
                'value' => (string)$user->id,
                'label' => $user->name,
            ];
        });


        return view("pages.Job.Pajak.index", [
            "items" => $items,
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
        // dd($request->all());
        $request->validate([
            "nominal_pembayaran" => "required",
        ]);

        DB::beginTransaction();

        try {
            $job_divisi_form_order = JobDivisiFormOrder::find($request->form_id);
            $item = StatusJobOps::create([
                "created_by" => Auth::id(),
                "job_divisi_form_order_id" => $request->form_id,
                "status" => "Selesai",
                "user_id" => $request->ops,
                "keterangan" => $request->keterangan
            ]);

            $finance = JobDivisiFinance::create(
                [
                    "job_divisi_id" => $job_divisi_form_order->job_divisi_id,
                    "tanggal" => Carbon::parse($request->tanggal)->format("Y-m-d"),
                    "total" => str_replace(".", "", $request->nominal_pembayaran),
                    "keterangan" => "Pembayaran pajak ",
                    "metode_pembayaran" => "Pembayaran pajak",
                    "created_at" => now(),
                    "updated_at" => now(),
                    "user_id" => Auth::user()->id,
                    "created_by" => Auth::user()->id,
                    "tipe" => "out",
                    "peruntukan" => "pajak",
                    "status" => "Disetujui",
                    "job_divisi_form_order_id" => $request->form_id
                ]
            );

            $job_divisi_form_order->update([
                "harga_jual" => str_replace(".", "", $request->nominal_pembayaran),
                "harga_modal" => $job_divisi_form_order->harga_jual,
            ]);

            // $updateStatus = new UpdateStatusJobDivisiController();
            // $formOrder = JobDivisiFormOrder::with("jobDivisi")->find($request->form_id);
            // $updateSelesai  = $updateStatus->updateSelesai($formOrder->jobDivisi->id);


            DB::commit();

            return redirect()->back()->with("success", "Berhasil simpan data");
        } catch (Exception $th) {
            DB::rollBack();

            return redirect()->back()->with("error", $th->getMessage());
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
        //
    }
}
