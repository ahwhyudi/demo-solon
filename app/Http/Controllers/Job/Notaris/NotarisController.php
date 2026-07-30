<?php

namespace App\Http\Controllers\Job\Notaris;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Job\UpdateStatusJobDivisiController;
use App\Models\JobDivisiFormOrder;
use App\Models\NomorPpat;
use App\Models\StatusJobOps;
use App\Models\User;
use App\Services\Akta\InputNomorServis;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class NotarisController extends Controller
{
    public function __construct(protected InputNomorServis $inputNomorServis) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tipe = $request->type ?? "notaris";
        $items = JobDivisiFormOrder::with([
            "jobDivisi.jenisAkad",
            "jobDivisi.objek",
            "jobDivisi.debitur",
            "statusJobOps.createdBy",
            "statusJobOps.user",
            "nomorPpat"
        ])
            ->whereNotIn("status", ["rejected", "Dibatalkan", "pending"])

            ->where('kategori', $tipe)
            ->whereHas("jobDivisi")
            ->paginate(10);

        $userOps = User::orderBy("name", "asc")->get()->map(function ($user) {
            return [
                'value' => (string)$user->id,
                'label' => $user->name,
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $item = StatusJobOps::create([
                "created_by" => Auth::id(),
                "job_divisi_form_order_id" => $request->form_id,
                "status" => $request->status,
                "user_id" => $request->ops,
                "keterangan" => $request->keterangan
            ]);

            if ($request->status === "Selesai Minuta") {
                $updateStatus = new UpdateStatusJobDivisiController();
                $formOrder = JobDivisiFormOrder::with("jobDivisi")->find($request->form_id);
                $updateSelesai  = $updateStatus->updateSelesai($formOrder->jobDivisi->id);
            }

            DB::commit();
            return redirect()->back()->with("success", "Berhasil simpan data");
        } catch (Exception $th) {

            return redirect()->back()->with("error", $th->getMessage());
        }
    }

    /**
     * Display detail notaris.
     */
    public function show(string $id)
    {
        $job_divisi = JobDivisiFormOrder::with("user", "jobDivisi")->find($id);
        if (!$job_divisi) {
            return redirect()->back()->with("msg_error", "Data tidak ditemukan");
        }

        $userOps = User::orderBy("name", 'asc')->get()->map(function ($user) {
            return [
                "value" => (string)$user->id,
                "label" => $user->name,
            ];
        });
    }

    public function simpanNomorPPAT(Request $request)
    {
        $request->validate([
            "tanggal_nomor" => "required",
        ], [
            "tanggal_nomor.required" => "Tanggal nomor harus diisi",
        ]);

        DB::beginTransaction();

        try {

            if ($request->rekanan && $request->nomor_rekanan) {
                $item = NomorPpat::create([
                    "nomor" => $request->nomor_rekanan,
                    "job_divisi_form_order_id" => $request->form_id,
                    "user_id" => Auth::user()->id,
                    "tanggal" => Carbon::parse($request->tanggal_nomor),
                    "rekanan" => 1,
                    "tanggal_expired" => $request->tanggal_expired,
                    "kategori" => $request->kategori
                ]);


                DB::commit();
                return redirect()->back()->with("success", "Berhasil simpan nomor rekanan");
            }

            $kategori = $request->kategori;
            $tanggal_nomor = $request->tanggal_nomor;

            $nomor = $this->inputNomorServis->execute($kategori, $tanggal_nomor);

            $item = NomorPpat::create([
                "nomor" => $nomor,
                "job_divisi_form_order_id" => $request->form_id,
                "user_id" => Auth::user()->id,
                "tanggal" => Carbon::parse($request->tanggal_nomor),
                "rekanan" => 0,
                "tanggal_expired" => $request->tanggal_expired,
                "kategori" => $request->kategori
            ]);

            DB::commit();

            return redirect()->back()->with("success", "Berhasil simpan Nomor ");
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with("error", $th->getMessage());
        }
    }
}
