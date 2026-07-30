<?php

namespace App\Http\Controllers\Job;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Desa;
use App\Models\Developer;
use App\Models\Divisi;
use App\Models\MasterDataFormOrder;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Session;

class Step2JobDivisiController extends Controller
{
    public function step2()
    {
        $session = Session::get("form_akad");

        if (!$session) {
            return redirect()->route("job.divisi.create");
        }

        $desa = Desa::orderBy("name")
            ->get();

        $bank = Bank::orderBy("nama")->get();

        $developer = Developer::orderBy("nama")->get();

        $masterDataFormOrder = MasterDataFormOrder::with("details")
            ->orderBy("nama", "asc")
            ->where("id", $session["group_proses"])
            ->first();

        $jenisData = explode(",", $masterDataFormOrder->jenis_data);

        return view("pages.Job.Divisi._Step2", [

            "masterDataFormOrder" => $masterDataFormOrder,
            "jenisData" => $jenisData,
            "desa" => $desa,
            "bank" => $bank,
            "developer" => $developer
        ]);
    }

    public function store(Request $request)
    {
        $session = Session::get("form_akad");

        if (!$session) {
            return redirect()->route("job.divisi.create");
        }

        try {
            $masterDataFormOrder = MasterDataFormOrder::with("details")
                ->orderBy("nama", "asc")
                ->where("id", $session["group_proses"])
                ->first();

            $jenisData = explode(",", $masterDataFormOrder->jenis_data);

            if (in_array("developer", $jenisData)) {
                $request->validate([
                    'developer' => ['required', 'array', 'min:1'],
                    "developer.*.developer" => ['required'],
                    "developer.*.pimpinan" => ['required'],
                ]);
            }

            if (in_array("badan hukum", $jenisData)) {
                $request->validate([
                    'badan_hukum' => ['required', 'array', 'min:1'],
                    "badan_hukum.*.nama_pt" => ['required'],
                    "badan_hukum.*.npwp" => ['required'],
                    "badan_hukum.*.nib" => ['required'],
                    "badan_hukum.*.nama_dirut" => ['required'],
                    "badan_hukum.*.no_telepon" => ['required'],
                    "badan_hukum.*.alamat" => ['required'],
                ]);
            }

            if (in_array("bank", $jenisData)) {
                $request->validate([
                    'bank' => ['required', 'array', 'min:1'],
                    "bank.*.bank" => ['required'],
                    "bank.*.pimpinan" => ['required'],
                ]);
            }

            if (in_array("debitur", $jenisData)) {
                $validated = $request->validate([
                    'debitur' => ['required', 'array', 'min:1'],
                    'debitur.*.nama_lengkap' => ['required', 'string', 'max:255'],
                    'debitur.*.nik' => ['required'],
                    'debitur.*.phone' => ['nullable', 'string', 'max:20'],
                ], [
                    'debitur.required' => 'Minimal tambahkan 1 debitur.',
                    'debitur.*.nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                    'debitur.*.nik.required' => 'NIK wajib diisi.',
                ]);
            }

            if (in_array("penjual", $jenisData)) {
                $validated = $request->validate([
                    'penjual' => ['required', 'array', 'min:1'],
                    'penjual.*.nama_lengkap' => ['required', 'string', 'max:255'],
                    'penjual.*.nik' => ['required'],
                    'penjual.*.phone' => ['nullable', 'string', 'max:20'],
                ], [
                    'penjual.required' => 'Minimal tambahkan 1 penjual.',
                    'penjual.*.nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                    'penjual.*.nik.required' => 'NIK wajib diisi.',
                ]);
            }

            if (in_array("pembeli", $jenisData)) {
                $validated = $request->validate([
                    'pembeli' => ['required', 'array', 'min:1'],
                    'pembeli.*.nama_lengkap' => ['required', 'string', 'max:255'],
                    'pembeli.*.nik' => ['required'],
                    'pembeli.*.phone' => ['nullable', 'string', 'max:20'],
                ], [
                    'pembeli.required' => 'Minimal tambahkan 1 pembeli.',
                    'pembeli.*.nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                    'pembeli.*.nik.required' => 'NIK wajib diisi.',
                ]);
            }

            if (in_array("objek", $jenisData)) {
                $rules = [
                    'objek'                              => ['required', 'array', 'min:1'],
                    'objek.*.desa_id'                    => ['required', 'integer', 'exists:desas,id'], // ganti 'desas' sesuai nama tabel Anda
                    'objek.*.jenis_sertifikat'           => ['required', 'string', 'max:255'],
                    'objek.*.no_sertifikat'              => ['required', 'string', 'max:255'],
                    // 'objek.*.luas_tanah'                 => ["required"],
                    // 'objek.*.nilai_ht'                   => ['required'],
                    'objek.*.nilai_transaksi'            => ['required'],
                ];

                $messages = [
                    'objek.required'                     => 'Minimal 1 objek harus diisi.',
                    'objek.array'                        => 'Format objek tidak valid.',
                    'objek.min'                          => 'Minimal 1 objek harus diisi.',

                    'objek.*.desa_id.required'           => 'Desa wajib dipilih.',
                    'objek.*.desa_id.integer'            => 'Desa tidak valid.',
                    'objek.*.desa_id.exists'             => 'Desa tidak ditemukan.',

                    'objek.*.jenis_sertifikat.required'  => 'Jenis sertifikat wajib diisi.',
                    'objek.*.no_sertifikat.required'     => 'Nomor sertifikat wajib diisi.',

                    'objek.*.nilai_ht.required'          => 'Nilai HT wajib diisi.',

                    'objek.*.nilai_transaksi.required'   => 'Nilai transaksi wajib diisi.',
                ];

                $validated = $request->validate($rules, $messages);
            }

            $data = $request->except("_token");

            $newSession = Session::put("form_data", $data);

            return redirect()->route("job.divisi-konfirmasi",);
        } catch (Exception $th) {

            return redirect()->back()->with("error", "Terjadi kesalahan server : " . $th->getMessage())->withInput();
        }
    }

    public function konfirmasi()
    {
        $form_akad = Session::get("form_akad");
        $form_data = Session::get("form_data");

        if (!$form_akad || !$form_data) {
            return redirect()->route("job.divisi.create");
        }

        $objek = collect($form_data["objek"] ?? []);
        $desa = Desa::whereIn("id", $objek->pluck("desa_id"))
            ->with("kecamatan.kota.provinsi")
            ->get();

        $objek = $objek->map(function ($o) use ($desa) {
            return [
                ...$o,
                "desa" => $desa->where("id", $o["desa_id"])->first()
            ];
        });

        $allBank = Bank::orderBy("nama")->get();
        $allDeveloper = Developer::orderBy("nama")->get();

        $debitur = collect($form_data["debitur"] ?? []);
        $badan_hukum = collect($form_data["badan_hukum"] ?? []);
        $penjual = collect($form_data["penjual"] ?? []);
        $pembeli = collect($form_data["pembeli"] ?? []);
        $bank = collect($form_data["bank"] ?? [])->map(function ($b) use ($allBank) {
            return [
                ...$b,
                "nama_bank" => $allBank->where("id", $b["bank"])->first()->nama ?? "-"
            ];
        });
        $developer = collect($form_data["developer"] ?? [])->map(function ($d) use ($allDeveloper) {
            return [
                ...$d,
                "nama_developer" => $allDeveloper->where("id", $d["developer"])->first()->nama ?? "-"
            ];
        });

        $masterDataFormOrder = MasterDataFormOrder::with("details.pekerjaan")
            ->orderBy("nama", "asc")
            ->where("id", $form_akad["group_proses"])
            ->first();

        $divisi = Divisi::where("id", $form_akad["divisi"])
            ->first();
        $userOps = User::where("id", $form_akad["user_ops"])
            ->first();



        return view("pages.Job.Divisi.konfirmasi", [
            "form_akad" => $form_akad,
            "form_data" => $form_data,
            "objek" => $objek,
            "debitur" => $debitur,
            "penjual" => $penjual,
            "badan_hukum" => $badan_hukum,
            "pembeli" => $pembeli,
            "bank" => $bank,
            "developer" => $developer,
            "allBank" => $allBank,
            "masterDataFormOrder" => $masterDataFormOrder,
            "divisi" => $divisi,
            "userOps" => $userOps,
            "kelipatanObjek" => count($objek) ? count($objek) : 1,
        ]);
    }
}
