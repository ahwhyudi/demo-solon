<?php

namespace App\Http\Controllers\Job\Ops;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Job\UpdateStatusJobDivisiController;
use App\Models\HargaPekerjaan;
use App\Models\JobDivisiFinance;
use App\Models\JobDivisiFormOrder;
use App\Models\JobDivisiObjek;
use App\Models\StatusJobOps;
use App\Models\User;
use App\Services\Notifikasi\NotifikasiServis;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $notifikasiServis;

    public function __construct(NotifikasiServis $notifikasiServis)
    {
        $this->notifikasiServis = $notifikasiServis;
    }



    public function index(Request $request)
    {
        $countFilter = 0;

        $items = JobDivisiFormOrder::query()
            ->with([
                "jobDivisi.jenisAkad",
                "jobDivisi.listBank",
                "jobdivisi.debitur",
                "jobDivisi.objek.desa.kecamatan.kota.provinsi",
                "jobDivisi.userOps",
                "jobDivisi.debitur",
                "statusJobOps.createdBy",
                "statusJobOps.user",
                "dispo"
            ])
            ->whereNotIn("status", ["rejected", "Dibatalkan"])
            ->where("kategori", "operasional")
            ->orderBy("id", "desc")
            ->whereHas("jobDivisi");

        if (Auth::user()->roles()->first()->name === "OPS staff") {
            $items->whereHas("statusJobOps", function ($query) {
                return $query->where("status", "Penugasan")->where("user_id", Auth::user()->id);
            });
        }

        $kodeParent = $request->parent;
        if ($kodeParent) {
            $items->whereHas("jobDivisi", function ($query) use ($kodeParent) {
                return $query->where("kode", "LIKE", "%$kodeParent%");
            });
            $countFilter++;
        }
        $nomorObjek = $request->nomor_objek;

        if ($nomorObjek) {

            $items->whereHas("jobDivisi.objek", function ($query) use ($nomorObjek) {

                $query->where("no_sertifikat", "LIKE", "%$nomorObjek%");
            });

            $countFilter++;
        }
        $namaPenghadap = $request->nama_penghadap;

        if ($namaPenghadap) {

            $items->whereHas("jobDivisi.debitur", function ($query) use ($namaPenghadap) {

                $query->where("nama", "LIKE", "%$namaPenghadap%");
            });

            $countFilter++;
        }

        $statusPengerjaan = $request->status_pengerjaan;
        if ($statusPengerjaan) {

            if ($statusPengerjaan === "belum dikerjakan") {
                $items->doesntHave("statusJobOps");
            } elseif ($statusPengerjaan === "dispo") {
                $items->where("status", "dispo");
            } else {
                $items->whereHas("statusJobOps", function ($query) use ($statusPengerjaan) {
                    return $query->where("status", $statusPengerjaan);
                });
            }
            $countFilter++;
        }

        $status_akad = $request->status_akad;
        if ($status_akad) {
            $items->whereHas("jobDivisi", function ($query) use ($status_akad) {
                return $query->where("status", $status_akad);
            });
            $countFilter++;
        }
        $proses = $request->proses;

        if ($proses) {
            $items->where("nama", $proses);
            $countFilter++;
        }

        $bank = $request->bank;

        if ($bank) {
            $items->whereHas("jobDivisi.listBank", function ($query) use ($bank) {
                $query->where("nama_bank", $bank);
            });

            $countFilter++;
        }
        $prosesList = JobDivisiFormOrder::query()
            ->where("kategori", "operasional")
            ->select("nama")
            ->distinct()
            ->orderBy("nama")
            ->pluck("nama");

        $bankList = \App\Models\Bank::orderBy("nama")
            ->pluck("nama");


        $userOps = User::orderBy("name", "asc")
            // ->whereHas('roles', function ($query) {
            //     $query->whereIn('name', ['operasional', "Ops Staff"]);
            // })
            ->get();

        $permissionAll = Auth::user()->getAllPermissions()->pluck("name")->toArray();


        return view("pages.Job.Ops.index", [
            "items" => $items->paginate(14),
            "userOps" => $userOps,
            "countFilter" => $countFilter,
            "permissionAll" => $permissionAll,
            "prosesList" => $prosesList,
            "bankList" => $bankList,
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
        if ($request->status === "Penugasan") {
            $request->validate([
                "ops" => "required",
            ]);
        }

        DB::beginTransaction();
        try {
            $status = $request->status;
            $formOrder = JobDivisiFormOrder::find($request->form_id);
            $biaya_ops = str_replace(".", "", $request->biaya_operasional ?? 0);



            if ($request->status === "Pengajuan Biaya") {

                $status_finance = "Ok";
                $objek = JobDivisiObjek::with("desa.kecamatan.kota")->find($formOrder->objek_id);
                $kota_id = $objek->desa->kecamatan->kota->id ?? null;
                $harga_pekerjaan = HargaPekerjaan::query()->where("kota_id", $kota_id)
                    ->whereHas("pekerjaan", function ($query) use ($formOrder) {
                        return $query->where("nama", $formOrder->nama);
                    })
                    ->with("pekerjaan")
                    ->first();



                $status = "Menunggu persetujuan finance";
                $status_finance = "Menunggu persetujuan finance";


                $formOrder->harga_proses = $biaya_ops;

                $finance = JobDivisiFinance::create([
                    "job_divisi_form_order_id" => $request->form_id,
                    "total" => $biaya_ops,
                    "tipe" => "out",
                    "peruntukan" => "operasional",
                    "keterangan" => $request->keterangan,
                    "created_by" => Auth::user()->id,
                    "job_divisi_id" => $formOrder->job_divisi_id,
                    "tanggal" => now(),
                    "status" => $status_finance,
                    "limit_ops" => $harga_pekerjaan->harga_limit ?? null
                ]);

                // dd($finance->toArray());
                if ($status_finance === "Menunggu persetujuan finance") {

                    $usersFinance = User::whereHas('roles', function ($query) {

                        $query->whereIn('name', [
                            'super admin',
                            'finance'
                        ]);
                    })->get();

                    foreach ($usersFinance as $user) {

                        $this->notifikasiServis->create(
                            $user->id,
                            "Persetujuan Finance",
                            "Terdapat pengajuan biaya",
                            route('finance.job-divisi.index', ['type' => 'out']),
                            $formOrder->job_divisi_id,
                            $formOrder->id
                        );
                    }
                }
            }

            if ($request->objek) {
                $formOrder->objek_id = $request->objek;
            }

            if ($request->ops) {
                $formOrder->penugasan_user = $request->ops;
            }

            $formOrder->save();

            $item = StatusJobOps::create([
                "created_by" => Auth::user()->id,
                "job_divisi_form_order_id" => $request->form_id,
                "status" => $status,
                "user_id" => $request->ops,
                "keterangan" => $request->keterangan
            ]);
            $formOrder = JobDivisiFormOrder::with('jobDivisi')
                ->find($request->form_id);
            // dd($formOrder->nama);
            if ($request->ops) {

                $this->notifikasiServis->create(
                    $request->ops,
                    "Penugasan OPS",
                    "Anda memiliki penugasan operasional",
                    route('job.ops.data.index'),
                    $formOrder->jobDivisi->id,
                    $formOrder->id
                );
            }

            if ($status === "Dikembalikan") {
                $updateStatus = new UpdateStatusJobDivisiController();

                $updateSelesai = $updateStatus->updateSelesai($formOrder->jobDivisi->id);
            }

            // dd("commit");
            DB::commit();
            return redirect()->back()->with("success", "Berhasil simpan data");
        } catch (Exception $th) {
            DB::rollBack();
            // dd($th);
            return redirect()->back()->with("error", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job_divisi = JobDivisiFormOrder::with("user", "jobDivisi")->find($id);
        if (! $job_divisi) {
            return redirect()->back()->with("msg_error", "Data tidak ditemukan");
        }

        $userOps = User::inRandomOrder()->orderBy("name")->limit(30)->get()->map(function ($user) {
            return [
                "value" => (string)$user->id,
                "label" => $user->name,
            ];
        });
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
