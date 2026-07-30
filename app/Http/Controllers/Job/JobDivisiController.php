<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Broker;
use App\Models\Desa;
use App\Models\Developer;
use App\Models\Divisi;
use App\Models\FileJobDivisi;
use App\Models\JobDivisi;
use App\Models\JobDivisiFormOrder;
use App\Models\MasterDataFormOrder;
use App\Models\MasterDataFormOrderDetail;
use App\Models\Pekerjaan;
use App\Models\Status;
use App\Models\StatusDetail;
use App\Models\User;
use App\Services\FileStoreServis;
use App\Services\Job\JobDivisiIndexServis;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class JobDivisiController extends Controller
{

    public function __construct(
        protected FileStoreServis $fileStoreServis,
        protected JobDivisiIndexServis $jobDivisiIndexServis
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view("pages.Job.Divisi.index", $this->jobDivisiIndexServis->execute($request));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $status = Status::whereIn("nama", ["Status", "Status Akad Developer", "Status Akad Unit", "Status Akad"])
            ->get()->pluck("id");

        $all_status = StatusDetail::whereIn("status_id", $status)
            ->get()->map(function ($status) {
                return [
                    "value" => (string)$status->id,
                    "label" => $status->nama,
                    "status" => $status->status->nama
                ];
            });

        $userOps = User::orderBy("name")->limit(30)->get()->map(function ($user) {
            return [
                "value" => (string)$user->id,
                "label" => $user->name,
            ];
        });

        $bank = Bank::orderBy("nama")->get()->map(function ($bank) {
            return [
                "value" => (string)$bank->id,
                "label" => $bank->nama,
            ];
        });

        $developer = Developer::get()->map(function ($developer) {
            return [
                "value" => (string)$developer->id,
                "label" => $developer->nama_perumahan,
            ];
        });
        $sertifikat = [
            [
                "value" => "mobil",
                "label" => "mobil"
            ],
            [
                "value" => "SHGB",
                "label" => "SHGB"
            ],
            [
                "value" => "SHM",
                "label" => "SHM"
            ],
        ];
        $divisi = Divisi::orderBy("nama")->get()->map(function ($divisi) {
            return [
                "value" => (string)$divisi->id,
                "label" => $divisi->nama,
            ];
        });

        $desa = Desa::orderBy("name")

            ->get()
            ->map(function ($desa) {
                return [
                    "value" => (string)$desa->id,
                    "label" => $desa->name,
                ];
            });

        $masterDataFormOrder = MasterDataFormOrder::with("details")
            ->orderBy("nama", "asc")
            ->get()->map(function ($row) {
                return [
                    "value" => (string)$row->id,
                    "label" => $row->nama,
                    "jenis_data" => explode(",", $row->jenis_data)
                ];
            });

        return view("pages.Job.Divisi.create", [
            "status" => [...$all_status->where("status", "Status")->toArray()],
            "status_akad_developer" => $masterDataFormOrder,
            "userOps" => $userOps->toArray(),
            "bank" => $bank->toArray(),
            "developer_perumahaan" => $developer->toArray(),
            "sertifikat" => $sertifikat,
            // "provinsi"=>$provinsi->toArray(),
            // "kota"=>$kota->toArray(),
            // "kecamatan"=>$kecamatan->toArray(),
            "desa" => $desa->toArray(),
            "divisi" => $divisi->toArray(),
        ]);
    }

    public function storeFormAkad(Request $request)
    {

        $request->validate([
            "group_proses" => "required",
            "divisi" => "required",
            "user_ops" => "required",
            "tgl_rencana_akad" => "required",
            "keterangan" => "required",
        ]);

        $data = $request->except("_token");

        $session = Session::put("form_akad", $data);

        return redirect()->route("job.divisi-step2", ["step" => 2]);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            // $lastJob = JobDivisi::orderBy("id", "desc")->first()->kode ?? "JOB-000";
            $bulanRomawi = [
                1 => 'I',
                2 => 'II',
                3 => 'III',
                4 => 'IV',
                5 => 'V',
                6 => 'VI',
                7 => 'VII',
                8 => 'VIII',
                9 => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII',
            ];

            $prefix = now()->year . '/' . $bulanRomawi[now()->month] . '/';

            $lastJob = JobDivisi::where('kode', 'like', $prefix . '%')
                ->orderByDesc('kode')
                ->first();

            $nomor = 1;

            if ($lastJob) {
                $parts = explode('/', $lastJob->kode);
                $nomor = (int) $parts[2] + 1;
            }

            $kode = sprintf(
                '%s/%s/%04d',
                now()->year,
                $bulanRomawi[now()->month],
                $nomor
            );
            $insertData = JobDivisi::create([
                "kode" => $kode,
                "user_id" => Auth::user()->id,
                "created_by" => Auth::user()->id,
                "jenis_akad" => $request->group_proses,
                "status" => "Pra Akad",
                "divisi_yang_dituju" => null,
                "user_ops" => null,
                "keterangan" => "-"
            ]);

            $masterDataFormOrder = MasterDataFormOrder::with("details.pekerjaan")
                ->where("id", $request->group_proses)
                ->first();

            $details = MasterDataFormOrderDetail::where("master_data_form_order_id", $masterDataFormOrder->id)
                ->whereHas("pekerjaan")
                ->get();

            $dataFormOrderJob = $details->map(function ($detail) use ($insertData) {

                return [
                    "job_divisi_id" => $insertData->id,
                    "pekerjaan_id" => $detail->pekerjaan->id,
                    "created_by" => Auth::user()->id,
                    "nama" => $detail->pekerjaan->nama,
                    "kategori" => $detail->pekerjaan->kategori,
                    "harga_modal" => 0,
                    "harga_proses" => 0,
                    "harga_jual" => 0,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            });

            $insertDetailJob = JobDivisiFormOrder::insert($dataFormOrderJob->toArray());

            DB::commit();

            return redirect()->back()->with("success", "Berhasil simpan data $insertData->kode");
        } catch (Exception $th) {
            DB::rollBack();
            dd($th);
            return redirect()->back()->with("error", "Terjadi kesalahan server");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobDivisi = JobDivisi::with(
            "perubahanHarga",
            "jenisAkad",
            "pembuat",
            'userOps',
            'listBroker',
            'badanHukum',
            'developer.developer',
            "objek.desa.kecamatan.kota.provinsi",
            "formOrder.statusJobOps",
            "formOrder.statusJobOps.createdBy",
            "formOrder.statusJobOps.user",
            "finance.invoice",
            "finance.user",
            "finance.formOrder",
            "finance.pnbp.user",
            "debitur",
            "penjual",
            "pembeli",
            "listBank.bank",
            "listBank.headLegalBank",
            "listBank.legalBank",
            "listBank.headMarketing",
            "listBank.marketing",
            "listDebitur",
            "listBadanUsahaDebitur",
            "listBadanUsahaPembeli",
            "listBadanUsahaPenjual",
            "fileJob.user",
            "freeze",
            "invoice.detail"
        )->find($id);

        $approvedFinance = $jobDivisi->finance->filter(function ($finance) {

            $status = strtolower((string) $finance->status);

            return in_array($status, ['disetujui', 'approved']);
        });

        $filteredFinance = $approvedFinance
            ->when(request()->filled('tipe'), function ($query) {
                return $query->where('tipe', request()->tipe);
            })
            ->when(request()->filled('peruntukan'), function ($query) {
                return $query->where('peruntukan', request()->peruntukan);
            });

        $totalPemasukan = (float) $approvedFinance
            ->where('tipe', 'in')
            ->sum('total');

        $totalPengeluaran = (float) $approvedFinance
            ->where('tipe', 'out')
            ->sum('total');

        $totalBiaya = (float) $jobDivisi->formOrder->sum('harga_jual') - $jobDivisi->formOrder->sum('diskon');

        $profit = $totalPemasukan - $totalPengeluaran;

        $piutang = $totalBiaya - $totalPemasukan;

        $approveFormOrder = false;
        if ($jobDivisi->perubahanHarga?->status === "menunggu persetujuan") {
            $approveFormOrder = true;
        }

        if (!$jobDivisi) {
            Session::flash('error', 'Data Tidak Ditemukan');
            return to_route('job.divisi.index');
        }

        $dataPendukungObjek = $jobDivisi->jenisAkad->data_pendukung ? explode(",", $jobDivisi->jenisAkad->data_pendukung) : null;

        $dataPendukung = explode(",", $jobDivisi->jenisAkad->jenis_data);

        $roles = Cache::remember('master_roles', 86400, fn() => Role::orderBy("name", "asc")->get());
        $user = Cache::remember('master_users', 86400, fn() => User::orderBy("name", "asc")->get());
        $masterPekerjaan = Cache::remember('master_pekerjaan', 86400, fn() => Pekerjaan::orderBy("nama", "asc")->get());

        $jobFormOrder = $jobDivisi->formOrder->groupBy("kategori");

        $myRoles = Auth::user()->roles->first()->id;

        $bank = Cache::remember('master_bank', 86400, function () {
            return Bank::orderBy("nama")->with("kepalaLegal", "legal", "kepalaMarketing", "marketing")->get();
        });

        $developer = Cache::remember('master_developer', 86400, fn() => Developer::with("marketing", "legal")->get());
        $broker = Cache::remember('master_broker', 86400, fn() => Broker::with("marketing")->get());

        $fileAkad = $jobDivisi->fileJob->where("tipe", "foto akad")->first();
        $fileSertifikat = $jobDivisi->fileJob->where("tipe", "sertifikat")->first();
        $fileCovernot = $jobDivisi->fileJob->where("tipe", "covernot")->first();

        $countFilter = collect([
            request()->tipe,
            request()->peruntukan,
        ])->filter()->count();

        $peruntukanList = $jobDivisi->finance
            ->pluck('peruntukan')
            ->filter()
            ->unique()
            ->values();

        return view("pages.Job.Divisi.detail", [
            "jobDivisi" => $jobDivisi,
            "roles" => $roles,
            "user" => $user,
            "jobFormOrder" => $jobFormOrder,
            "masterPekerjaan" => $masterPekerjaan,
            "myRoles" => $myRoles,
            "dataPendukung" => $dataPendukung,
            "bank" => $bank,
            // "desa" => $desa,
            "developer" => $developer,
            "dataPendukungObjek" => $dataPendukungObjek,
            "broker" => $broker,
            "fileAkad" => $fileAkad,
            "fileSertifikat" => $fileSertifikat,
            "fileCovernot" => $fileCovernot,
            "approveFormOrder" => $approveFormOrder,
            "approvedFinance" => $approvedFinance,
            "totalPemasukan" => $totalPemasukan,
            "totalPengeluaran" => $totalPengeluaran,
            "totalBiaya" => $totalBiaya,
            "profit" => $profit,
            "piutang" => $piutang,
            "countFilter" => $countFilter,
            "peruntukanList" => $peruntukanList,
            "filteredFinance" => $filteredFinance,
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
        $jobDivisi = JobDivisi::find($id);

        if (!$jobDivisi) {
            return redirect()->back()->with("error", "Data Tidak Ditemukan");
        }

        $dataValidasi = [];
        if ($request->ringkasan) {
            $dataValidasi = [
                "ringkasan" => "required|array",
            ];
        }

        $validasi  = Validator::make($request->all(), $dataValidasi);

        if ($validasi->fails()) {
            return redirect()->back()->with("error", "data belum lengkap")->withInput();
        }

        DB::beginTransaction();

        try {

            if ($request->ringkasan) {

                $formUpdate = [];

                if ($request->ringkasan["tipe_servis"]) {
                    $formUpdate["tipe_servis"] = $request->ringkasan["tipe_servis"];
                }

                if (isset($request->ringkasan["penanggung_jawab"])) {
                    $formUpdate["user_ops"] = $request->ringkasan["penanggung_jawab"];
                }

                if (isset($request->ringkasan["perwakilan_akad"])) {

                    $formUpdate["user_perwakilan_akad"] = $request->ringkasan["perwakilan_akad"][0] ?? null;
                    $formUpdate["user_perwakilan_akad_2"] = $request->ringkasan["perwakilan_akad"][1] ?? null;
                }

                if ($request->ringkasan["tanggal_rencana_akad"]) {
                    $formUpdate["tanggal_rencana_akad"] = $request->ringkasan["tanggal_rencana_akad"];
                }

                if ($request->ringkasan["tempat_akad"]) {
                    $formUpdate["tempat_akad"] = $request->ringkasan["tempat_akad"];
                }

                if ($request->ringkasan["tanggal_kirim_berkas"]) {
                    $formUpdate["tanggal_kirim_berkas"] = $request->ringkasan["tanggal_kirim_berkas"];
                }

                if ($request->ringkasan["catatan"]) {
                    $formUpdate["keterangan"] = $request->ringkasan["catatan"];
                }

                $jobDivisi->update($formUpdate);
            }

            if ($request->foto_akad) {
                $fileUpload = $this->fileStoreServis->uploadFile(
                    $request->foto_akad,
                    "job_divisi/foto_akad",
                    $jobDivisi,
                    "foto akad"
                );
            }

            if ($request->covernot) {
                $fileUpload = $this->fileStoreServis->uploadFile(
                    $request->covernot,
                    "job_divisi/covernot",
                    $jobDivisi,
                    "covernot"
                );
            }
            if ($request->sertifikat) {
                $fileUpload = $this->fileStoreServis->uploadFile(
                    $request->sertifikat,
                    "job_divisi/sertifikat",
                    $jobDivisi,
                    "sertifikat"
                );
            }

            DB::commit();

            return redirect()->back()
                ->with("success", "Data Job Divisi Berhasil Diperbarui");
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with("error", "Terjadi kesalahan server");
        }
    }

    public function batalAkad(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string'
        ]);

        $jobDivisi = JobDivisi::findOrFail($id);
        $jobDivisi->status = 'Batal Akad';
        $jobDivisi->keterangan = $request->keterangan;
        $jobDivisi->save();

        return back()->with('success', 'Status berhasil diubah.');
    }

    public function bukaBatal($id)
    {
        $jobDivisi = JobDivisi::findOrFail($id);
        $jobDivisi->status = 'Pra Akad';
        $jobDivisi->save();

        return back()->with('success', 'Status berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function countData($id)
    {
        $jobDivisi = JobDivisi::withCount("formOrder", "finance")->find($id);

        // $jobDivisi->finance = $jobDivisi->finance_count;

        return response()->json($jobDivisi);
    }

    public function updateStatusSelesai(string $job_id)
    {

        $jobDivisi = JobDivisi::findOrFail($job_id);

        $jobDivisi->status = "Selesai";
        $jobDivisi->save();

        return redirect()->back()->with("success", "Berhasil ubah selesai job");
    }

    public function changeStatusAkad(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {

            $status = $request->status;

            $jobDivisi = JobDivisi::find($request->job_divisi_id);

            $jobDivisi->status = $status;

            if ($status === "Akad") {
                $jobDivisi->tanggal_akad = now();

                $sla_internal = Carbon::now()->addDays((int)$jobDivisi->jenisAkad->sla_internal);
                $sla_eksternal = Carbon::now()->addDays((int)$jobDivisi->jenisAkad->sla_eksternal);

                $jobDivisi->tanggal_estimasi_selesai = $sla_internal;
                $jobDivisi->tanggal_estimasi_selesai_eksternal = $sla_eksternal;

                $updateJobDivisiPending = JobDivisiFormOrder::where("job_divisi_id", $jobDivisi->id)
                    ->where("status", "pending")
                    ->update([
                        "status" => "approve"
                    ]);
            } elseif ($status === "Batal Akad") {
                $this->ItemBatalAkad($jobDivisi);
            }

            $jobDivisi->save();
            DB::commit();

            return redirect()->back()->with("success", "Status Berhasil Diubah");
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with("error", "Terjadi kesalahan server : " . $th->getMessage());
        }
    }

    public function ItemBatalAkad($jobDivisi)
    {
        $updateJobDivisiPending = JobDivisiFormOrder::where("job_divisi_id", $jobDivisi->id)
            ->with("statusJobOps")
            ->get();

        $idDelete = [];

        foreach ($updateJobDivisiPending as $item) {
            if ($item->kategori === "operasional") {
                $cekPenugasan = $item->statusJobOps->where("status", "Penugasan")->first();
                if ($cekPenugasan) {
                    continue;
                }
            }

            $idDelete[] = $item->id;
        }

        JobDivisiFormOrder::whereIn("id", $idDelete)->update([
            "harga_jual" => 0,
            "status" => "batal"
        ]);
    }
}
