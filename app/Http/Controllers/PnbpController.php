<?php

namespace App\Http\Controllers;

use App\Models\JobDivisiFinance;
use App\Models\JobDivisiFormOrder;
use App\Models\Pnbp;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PnbpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $parent = $request->parent;
        $proses = $request->proses;
        $nomorObjek = $request->nomor_objek;
        $namaDebitur = $request->nama_debitur;
        $namaBank = $request->nama_bank;
        $status = $request->status;

        $countFilter = collect([
            'parent' => $parent,
            'proses' => $proses,
            'nomor_objek' => $nomorObjek,
            'nama_debitur' => $namaDebitur,
            'nama_bank' => $namaBank,
            'status' => $status,
        ])
            ->filter(fn($value) => filled($value))
            ->count();

        $prosesOptions = JobDivisiFormOrder::query()
            ->whereNotIn("status", ["rejected", "Dibatalkan", "pending"])
            ->where("kategori", "pnbp_voucher")
            ->whereHas("jobDivisi")
            ->select('nama')
            ->distinct()
            ->orderBy('nama')
            ->pluck('nama');

        $statusOptions = Pnbp::query()
            ->whereNotNull('status')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status')
            ->prepend('Belum dikerjakan')
            ->unique()
            ->values();

        $items = JobDivisiFormOrder::orderBy("id", "desc")
            ->with(
                "pnbp.user",
                "jobDivisi.objek",
                "jobDivisi.debitur",
                "jobDivisi.listBank",
            )
            ->whereNotIn("status", ["rejected", "Dibatalkan", "pending"])
            ->where("kategori", "pnbp_voucher")
            ->whereHas("jobDivisi")
            ->when($parent, function ($query) use ($parent) {
                return $query->whereHas('jobDivisi', function ($query) use ($parent) {
                    return $query->where('kode', 'LIKE', "%$parent%");
                });
            })
            ->when($proses, function ($query) use ($proses) {
                return $query->where('nama', $proses);
            })
            ->when($nomorObjek, function ($query) use ($nomorObjek) {
                return $query->whereHas('jobDivisi.objek', function ($query) use ($nomorObjek) {
                    return $query->where('no_sertifikat', 'LIKE', "%$nomorObjek%");
                });
            })
            ->when($namaDebitur, function ($query) use ($namaDebitur) {
                return $query->whereHas('jobDivisi.debitur', function ($query) use ($namaDebitur) {
                    return $query->where('nama', 'LIKE', "%$namaDebitur%");
                });
            })
            ->when($namaBank, function ($query) use ($namaBank) {
                return $query->whereHas('jobDivisi.listBank', function ($query) use ($namaBank) {
                    return $query->where('nama_bank', 'LIKE', "%$namaBank%");
                });
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'Belum dikerjakan') {
                    return $query->doesntHave('pnbp');
                }

                return $query->whereHas('pnbp', function ($query) use ($status) {
                    return $query->where('status', $status);
                });
            })
            ->paginate(12)
            ->withQueryString();
        $users = User::orderBy('name')->get();

        return view("pages.Job.Pnbp.index", compact(
            "items",
            "countFilter",
            "prosesOptions",
            "statusOptions",
            "users"
        ));
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
    // public function store(Request $request)
    // {

    //     $item = JobDivisiFormOrder::findOrFail($request->item_id);

    //     Pnbp::create([
    //         "job_divisi_form_order_id" => $item->id,
    //         "created_by" => Auth::user()->id,
    //         "va" => $request->va
    //     ]);

    //     return redirect()->route("job.pnbp.index")->with("success", "Pengajuan berhasil dibuat");
    // }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:job_divisi_form_orders,id',
            'user_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();

        try {

            $item = JobDivisiFormOrder::findOrFail($request->item_id);

            $exists = Pnbp::where(
                'job_divisi_form_order_id',
                $item->id
            )->exists();

            if ($exists) {

                return redirect()
                    ->route("job.pnbp.index")
                    ->with("error", "PNBP sudah dibuat");
            }

            Pnbp::create([
                "job_divisi_form_order_id" => $item->id,
                "created_by" => Auth::id(),
                "user_id" => $request->user_id,
                "status" => "Sedang Online",
                "assigned_at" => now(),
            ]);

            DB::commit();

            return redirect()
                ->route("job.pnbp.index")
                ->with("success", "PNBP berhasil ditugaskan");
        } catch (Exception $e) {

            DB::rollBack();
            dd($e);
            return redirect()
                ->route("job.pnbp.index")
                ->with("error", "Terjadi kesalahan server");
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
    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         "nominal" => "required"
    //     ]);


    //     DB::beginTransaction();

    //     try {
    //         $nominal = str_replace(".", "", $request->nominal);

    //         $item = Pnbp::with("formOrder")->findOrFail($id);
    //         $item->update([
    //             "status" => "Disetujui",
    //             "user_id" => Auth::user()->id,
    //             "nominal" => $nominal,
    //             "tanggal" => now()
    //         ]);



    //         $finance = JobDivisiFinance::create([
    //             "job_divisi_form_order_id" => $item->job_divisi_form_order_id,
    //             "job_divisi_id" => $item->formOrder->job_divisi_id,
    //             "total" => $nominal,
    //             "tipe" => "out",
    //             "peruntukan" => "pnbp",
    //             "keterangan" => "Pembayaran Pnbp",
    //             "created_by" => Auth::user()->id,
    //             "tanggal" => now()
    //         ]);

    //         DB::commit();

    //         return redirect()->route("job.pnbp.index")->with("success", "Pengajuan berhasil disetujui");
    //     } catch (Exception $th) {
    //         DB::rollBack();
    //         // dd($th);
    //         return redirect()->route("job.pnbp.index")->with("error", "terjadi kesalahan server");
    //     }
    // }

        // public function assign(Request $request, string $id)
        // {
        //     $request->validate([
        //         'user_id' => 'required|exists:users,id'
        //     ]);

        //     DB::beginTransaction();

        //     try {

        //         $item = Pnbp::findOrFail($id);

        //         if ($item->status !== 'Penugasan') {
        //             return back()->with(
        //                 'error',
        //                 'Status tidak valid'
        //             );
        //         }

        //         $item->update([
        //             'user_id' => $request->user_id,
        //             'status' => 'Sedang Online',
        //             'assigned_at' => now()
        //         ]);

        //         DB::commit();

        //         return back()->with(
        //             'success',
        //             'User berhasil ditugaskan'
        //         );
        //     } catch (Exception $th) {

        //         DB::rollBack();

        //         return back()->with(
        //             'error',
        //             'Terjadi kesalahan server'
        //         );
        //     }
        // }

    public function inputVa(Request $request, string $id)
    {
        $request->validate([
            'va' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $item = Pnbp::findOrFail($id);

            if ($item->status !== 'Sedang Online') {
                return back()->with(
                    'error',
                    'Status tidak valid'
                );
            }

            $item->update([
                'va' => $request->va,
                'status' => 'Menunggu Pembayaran',
                'va_at' => now()
            ]);

            DB::commit();

            return back()->with(
                'success',
                'Nomor VA berhasil disimpan'
            );
        } catch (Exception $th) {

            DB::rollBack();

            return back()->with(
                'error',
                'Terjadi kesalahan server'
            );
        }
    }

    public function payment(Request $request, string $id)
    {
        $request->merge([
            'nominal' => preg_replace(
                '/[^0-9]/',
                '',
                $request->nominal
            )
        ]);

        $request->validate([
            'nominal' => 'required|numeric|min:1'
        ]);

        DB::beginTransaction();

        try {

            $item = Pnbp::with("formOrder")
                ->findOrFail($id);

            if ($item->status !== 'Menunggu Pembayaran') {
                return back()->with(
                    'error',
                    'Status tidak valid'
                );
            }

            $item->update([
                'nominal' => $request->nominal,
                'status' => 'Terbayar',
                'paid_at' => now(),
                'approved_by' => Auth::id()
            ]);

            JobDivisiFinance::create([
                "job_divisi_form_order_id" => $item->job_divisi_form_order_id,
                "job_divisi_id" => $item->formOrder->job_divisi_id,
                "total" => $request->nominal,
                "tipe" => "out",
                "peruntukan" => "pnbp",
                "keterangan" => "Pembayaran Pnbp",
                "created_by" => Auth::id(),
                "status" => "Disetujui",
                "tanggal" => now()
            ]);

            DB::commit();

            return back()->with(
                "success",
                "Pembayaran berhasil"
            );
        } catch (Exception $th) {

            DB::rollBack();

            return back()->with(
                "error",
                "Terjadi kesalahan server"
            );
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
