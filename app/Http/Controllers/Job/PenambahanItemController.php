<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\JobDivisi;
use App\Models\JobDivisiFormOrder;
use App\Models\Pekerjaan;
use App\Models\PenambahanItemJobDivisi;
use App\Models\PenambahanItemJobDivisiDetail;
use App\Models\User;
use App\Services\Notifikasi\NotifikasiServis;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PenambahanItemController extends Controller
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
        $items = PenambahanItemJobDivisi::with("jobDivisi")
            ->orderBy("id", 'desc')
            ->paginate(12);

        return view("pages.Job.Penambahan-item.index", compact("items"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jobDivisi = JobDivisi::get();
        $pekerjaan = Pekerjaan::get();

        return view("pages.Job.Penambahan-item.create", compact("jobDivisi", "pekerjaan"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $jobDivisi = JobDivisi::find($request->parent);

        DB::beginTransaction();

        try {
            $lastData  = PenambahanItemJobDivisi::orderBy("id", "desc")->first()->kode ?? "JDI-000";
            $kode = ++$lastData;

            $penambahanItem = PenambahanItemJobDivisi::create([
                "job_divisi_id" => $jobDivisi->id,
                "keterangan" => $request->keterangan,
                "created_by" => Auth::user()->id,
                "kode" => $kode,
            ]);




            $formDataPenambahanItem = collect($request->pekerjaan)->map(function ($item, $index) use ($penambahanItem, $request) {
                return [
                    "penambahan_item_job_divisi_id" => $penambahanItem->id,
                    "created_at" => now(),
                    "updated_at" => now(),
                    "pekerjaan_id" => $item,
                    "harga_jual" => str_replace(".", "", $request->harga_jual[$index]),
                    "harga_modal" => 0,
                    "harga_proses" => 0,
                    "masuk_invoice" => isset($request->masuk_invoice[$index]) ? 1 : 0,
                ];
            });

            $insertDetail = PenambahanItemJobDivisiDetail::insert($formDataPenambahanItem->toArray());


            $superAdmins = User::whereHas('roles', function ($query) {

                $query->where('name', 'super admin');
            })->get();

            foreach ($superAdmins as $admin) {

                $this->notifikasiServis->create(
                    $admin->id,
                    "Permintaan Penambahan Item",
                    Auth::user()->name . " mengajukan penambahan item",
                    route('job.penambahan-item.show', $penambahanItem->id),
                    $jobDivisi?->id
                );
            }

            DB::commit();
            return redirect()->route("job.penambahan-item.index")->with("success", "Penambahan Item Berhasil Ditambahkan");
        } catch (Exception $th) {
            DB::rollBack();
            dd($th->getMessage());
            return back()->with("error", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penambahanItem = PenambahanItemJobDivisi::with(
            "detail.pekerjaan",
            "jobDivisi.objek",
            "jobDivisi.debitur",
            "jobDivisi.jenisAkad",
            "jobDivisi.formOrder",
            "jobDivisi.userOps",
            "jobDivisi.pembuat",
            "user",
            "userApprove"
        )->find($id);

        $dataPendukung = explode(",", $penambahanItem->jobDivisi->jenisAkad->jenis_data);

        return view("pages.Job.Penambahan-item.detail", [
            "penambahan_item" => $penambahanItem,
            "jobDivisi" => $penambahanItem->jobDivisi,
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
        $item = PenambahanItemJobDivisi::with("detail.pekerjaan")->findOrFail($id);

        DB::beginTransaction();

        try {

            $status = null;

            if ($request->has("approved")) {
                $status = "approved";

                $formDataFormOrder = $item->detail->map(function ($row) use ($item) {

                    return [
                        "job_divisi_id" => $item->job_divisi_id,
                        "pekerjaan_id" => $row->pekerjaan_id,
                        "nama" => $row->pekerjaan->nama,
                        "kategori" => $row->pekerjaan->kategori,
                        "created_by" => $item->created_by,
                        "harga_jual" => $row->harga_jual,
                        "masuk_invoice" => $row->masuk_invoice,
                        "harga_modal" => 0,
                        "harga_proses" => 0,
                        "diskon" => 0,
                        "status" => "approved",
                        "created_at" => now(),
                        "updated_at" => now()
                    ];
                });

                $insertFormOrder = JobDivisiFormOrder::insert($formDataFormOrder->toArray());
            } else {
                $status = "rejected";
            }

            $item->update(["status" => $status, "approved_by" => Auth::user()->id]);

            DB::commit();

            return redirect()->route("job.penambahan-item.index")->with("success", "Penambahan Item Berhasil Diubah");
        } catch (Exception $th) {
            DB::rollBack();

            return back()->with("error", "Gagal simpan data");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(Request $request)
    {

        DB::beginTransaction();

        try {

            $penambahanItem = PenambahanItemJobDivisi::with("detail.pekerjaan")->find($request->id);
            $penambahanItem->status = $request->status;
            $penambahanItem->approved_by = Auth::user()->id;
            $penambahanItem->save();

            if ($request->status === "disetujui") {
                $formDataInsertItem = $penambahanItem->detail->map(function ($item) use ($penambahanItem) {

                    return [
                        "job_divisi_id" => $penambahanItem->job_divisi_id,

                        "created_at" => now(),
                        "updated_at" => now(),

                        "nama" => $item->pekerjaan->nama,
                        "kategori" => $item->pekerjaan->kategori,
                        "harga_modal" => $item->harga_modal,
                        "harga_jual" => $item->harga_jual,
                        "harga_proses" => $item->harga_proses,
                        "lama_proses" => 1,
                        "masuk_invoice" => $item->masuk_invoice,
                        'created_by' => $penambahanItem->created_by
                    ];
                });

                $insertPenambahan = JobDivisiFormOrder::insert($formDataInsertItem->toArray());
            }

            DB::commit();

            return redirect()->back()->with("success", "Penambahan Item Berhasil $request->status");
        } catch (Exception $th) {
            DB::rollBack();

            return back()->with("error", $th->getMessage());
        }
    }
}
