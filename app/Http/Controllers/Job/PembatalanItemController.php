<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\JobDivisi;
use App\Models\JobDivisiFormOrder;
use App\Models\PembatalanItem;
use App\Models\PembatalanItemDetail;
use App\Models\User;
use App\Services\Notifikasi\NotifikasiServis;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembatalanItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $notifikasiServis;

    public function __construct(NotifikasiServis $notifikasiServis)
    {
        $this->notifikasiServis = $notifikasiServis;
    }

    public function index()
    {
        $items = PembatalanItem::orderBy("id", "desc")->paginate(12);
        $jobDivisi = JobDivisi::orderBy("id", "desc")
            // ->where(function ($query) {
            //     // Kondisi 1: Belum pernah diajukan pembatalan item
            //     $query->doesntHave("pembatalanItem")

            //         // ATAU
            //         ->orWhereHas("pembatalanItem", function ($subQuery) {
            //             // Kondisi 2: Sudah pernah diajukan, tapi statusnya BUKAN 'Disetujui' atau 'menunggu persetujuan'
            //             // Karena kita mencari keberadaan *satu saja* pembatalan item
            //             // yang memenuhi kondisi ini, kita perlu memastikan bahwa setidaknya
            //             // ADA satu relasi yang memenuhi kriteria status yang diinginkan.

            //             // Kita akan mencari JobDivisi yang memiliki setidaknya satu
            //             // PembatalanItem dengan status YANG BUKAN 'Disetujui' atau 'menunggu persetujuan'
            //             $subQuery->whereNotIn("status", ["Disetujui", "menunggu persetujuan"]);
            //         });
            // })
            ->get();

        return view("pages.Job.pembatalan-item.index", [
            "items" => $items,
            "jobDivisi" => $jobDivisi
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $jobDivisi = JobDivisi::with("formOrder.finance")->findOrFail($request->job_divisi_id);
        $formOrder = $jobDivisi->formOrder->where("finance.status", "!=", "Disetujui");

        // dd($formOrder->toArray());

        return view("pages.Job.pembatalan-item.create", [
            "jobDivisi" => $jobDivisi,
            "formOrder" => $formOrder
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $pembatalanItem = PembatalanItem::create([
                "job_divisi_id" => $request->job_divisi_id,
                "created_by" => Auth::user()->id,
                "keterangan" => $request->keterangan
            ]);

            $detailFormData = collect($request->item)->map(function ($item, $index) use ($pembatalanItem) {

                return [
                    "pembatalan_item_id" => $pembatalanItem->id,
                    "job_form_order_id" => $item,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            });

            $insertDetail =  PembatalanItemDetail::insert($detailFormData->toArray());
            $pembatalanItem = PembatalanItem::create([
                "job_divisi_id" => $request->job_divisi_id,
                "created_by" => Auth::user()->id,
                "keterangan" => $request->keterangan
            ]);

            $detailFormData = collect($request->item)->map(function ($item, $index) use ($pembatalanItem) {

                return [
                    "pembatalan_item_id" => $pembatalanItem->id,
                    "job_form_order_id" => $item,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            });

            $insertDetail = PembatalanItemDetail::insert($detailFormData->toArray());

            $jobDivisi = JobDivisi::find($request->job_divisi_id);

            $superAdmins = User::whereHas('roles', function ($query) {

                $query->where('name', 'super admin');
            })->get();

            foreach ($superAdmins as $admin) {

                $this->notifikasiServis->create(
                    $admin->id,
                    "Permintaan Pembatalan Item",
                    Auth::user()->name . " mengajukan pembatalan item",
                    route('job.pembatalan-items.show', $pembatalanItem->id),
                    $jobDivisi?->id
                );
            }

            DB::commit();
            return redirect()->route("job.pembatalan-items.index")->with("success", "Berhasil menambahkan pembatalan item");
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with("error", "Terjadi kesalahan server");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pembatalanItem = PembatalanItem::with("detail")
            ->with(
                "detail.pekerjaan",
                "jobDivisi.objek",
                "jobDivisi.debitur",
                "jobDivisi.jenisAkad",
                "jobDivisi.formOrder",
                "jobDivisi.userOps",
                "jobDivisi.pembuat",
                "user",
                "userApprove"
            )
            ->findOrFail($id);

        $dataPendukung = explode(",", $pembatalanItem->jobDivisi->jenisAkad->jenis_data);


        return view("pages.Job.pembatalan-item.detail", [
            "pembatalanItem" => $pembatalanItem,
            "jobDivisi" => $pembatalanItem->jobDivisi,
            "dataPendukung" => $dataPendukung
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
        $pembatalanItem = PembatalanItem::with("detail")
            ->find($id);

        DB::beginTransaction();

        try {
            $status = null;

            if ($request->approved) {
                $status = "Setujui";

                $pembatalanItem->update([
                    "approved_by" => Auth::user()->id,
                    "status" => "Disetujui"
                ]);

                $detail = $pembatalanItem->detail;

                $job_form_order = JobDivisiFormOrder::whereIn("id", $detail->pluck("job_form_order_id")->toArray())->update([
                    "status" => "Dibatalkan"
                ]);
            } else {
                $pembatalanItem->update([
                    "approved_by" => Auth::user()->id,
                    "keterangan_tolak" => $request->keterangan,
                    "status" => "Ditolak"
                ]);

                $status = "Tolak";
            }

            DB::commit();

            return redirect()->route("job.pembatalan-items.index")->with("success", "Berhasil $status item");
        } catch (Exception $th) {
            DB::rollBack();
            dd($th);
            return redirect()->back()->with("error", "Terjadi kesalahan server");
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
