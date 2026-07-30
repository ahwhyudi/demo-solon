<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Broker;
use App\Models\Debitur;
use App\Models\Desa;
use App\Models\Developer;
use App\Models\JobBank;
use App\Models\JobDeveloper;
use App\Models\JobDevisiBroker;
use App\Models\JobDivisi;
use App\Models\JobDivisiBadanHukum;
use App\Models\JobDivisiBadanUsahaDebitur;
use App\Models\JobDivisiBadanUsahaPembeli;
use App\Models\JobDivisiBadanUsahaPenjual;
use App\Models\JobDivisiBroker;
use App\Models\JobDivisiFile;
use App\Models\JobDivisiObjek;
use App\Models\JobDivisiPendirianLembaga;
use App\Models\Kecamatan;
use App\Models\Pembeli;
use App\Models\Penjual;
use App\Services\DataPendukung\InsertObjekPendukung;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DataPendukunJobDivisiController extends Controller
{
    public function __construct(protected InsertObjekPendukung $insertObjekPendukung) {}

    public function store(Request $request)
    {

        $jobDivisi = JobDivisi::with("jenisAkad")->find($request->job_divisi_id);
        if (!$jobDivisi) {
            return redirect()->back()->with("error", "Job Divisi tidak ditemukan")->withInput();
        }

        $dataPendukung = explode(",", $jobDivisi->jenisAkad->jenis_data);
        $validasiRules = [];

        if (in_array("broker", $dataPendukung) && $request->has("broker")) {
            $validasiRules = [
                ...$validasiRules,
                "broker" => "required|array|min:1",
                "broker.*.broker" => "required",
                "broker.*.nama_agent" => "required",
            ];
        }

        if ($request->has("bank") && in_array("bank", $dataPendukung)) {

            $validasiRules = [
                ...$validasiRules,
                "bank" => "required|array|min:1",
                "bank.*.bank" => "required",
                "bank.*.pimpinan" => "required",
                "bank.*.kepala_legal" => "required",
                "bank.*.legal" => "required",
                "bank.*.kepala_marketing" => "required",
                "bank.*.marketing" => "required",
                // "bank.*.file" => "required",
            ];
        }


        if (in_array("penjual", $dataPendukung) && $request->has("penjual")) {
            $validasiRules = [
                ...$validasiRules,
                "penjual" => "required|array|min:1",
                "penjual.*.nama_lengkap" => "required|string",
                "penjual.*.phone" => "required",
                // "penjual.*.file" => "required",
                "penjual.*.email" => "required",
            ];
        }
        if (in_array("pembeli", $dataPendukung) && $request->has("pembeli")) {
            $validasiRules = [
                ...$validasiRules,
                "pembeli" => "required|array|min:1",
                "pembeli.*.nama_lengkap" => "required|string",
                "pembeli.*.phone" => "required",
                // "pembeli.*.file" => "required",
                "pembeli.*.email" => "required",
            ];
        }

        if (in_array("debitur", $dataPendukung) && $request->has("debitur")) {
            $validasiRules = [
                ...$validasiRules,
                "debitur" => "required|array|min:1",
                "debitur.*.nama_lengkap" => "required|string",
                "debitur.*.phone" => "required",
                // "debitur.*.file" => "required",
                "debitur.*.email" => "required",
            ];
        }

        if (in_array("badan usaha penjual", $dataPendukung) && $request->has("badan_usaha_penjual")) {
            $validasiRules = [
                ...$validasiRules,
                "badan_usaha_penjual" => "required|array|min:1",
                "badan_usaha_penjual.*.nama_badan_usaha" => "required|string",
                "badan_usaha_penjual.*.nama_perwakilan" => "required|string",
                "badan_usaha_penjual.*.nomor_telepon" => "required",
                "badan_usaha_penjual.*.email" => "required|email",
                // "badan_usaha_penjual.*.file" => "required",
            ];
        }

        if (in_array("usaha debitur", $dataPendukung) && $request->has("badan_usaha_debitur")) {
            $validasiRules = [
                ...$validasiRules,
                "badan_usaha_debitur" => "required|array|min:1",
                "badan_usaha_debitur.*.nama_badan_usaha" => "required|string",
                "badan_usaha_debitur.*.nama_perwakilan" => "required|string",
                "badan_usaha_debitur.*.nomor_telepon" => "required",
                "badan_usaha_debitur.*.email" => "required|email",
                // "badan_usaha_debitur.*.file" => "required",
            ];
        }

        if (in_array("badan usaha pembeli", $dataPendukung) && $request->has("badan_usaha_pembeli")) {
            $validasiRules = [
                ...$validasiRules,
                "badan_usaha_pembeli" => "required|array|min:1",
                "badan_usaha_pembeli.*.nama_badan_usaha" => "required|string",
                "badan_usaha_pembeli.*.nama_perwakilan" => "required|string",
                "badan_usaha_pembeli.*.nomor_telepon" => "required",
                "badan_usaha_pembeli.*.email" => "required|email",
                // "badan_usaha_pembeli.*.file" => "required",
            ];
        }

        if (in_array("pendirian lembaga", $dataPendukung) && $request->has("pendirian_lembaga")) {
            $validasiRules = [
                ...$validasiRules,
                "pendirian_lembaga" => "required|array|min:1",
                "pendirian_lembaga.*.nama_lembaga" => "required|string",
                "pendirian_lembaga.*.bidang_usaha" => "required|string",
                "pendirian_lembaga.*.modal_dasar" => "required",
                "pendirian_lembaga.*.modal_setor" => "required",
                "pendirian_lembaga.*.pemegang_saham" => "required",
                "pendirian_lembaga.*.alamat" => "required",
            ];
        }

        $validasi = Validator::make($request->all(), $validasiRules, [
            "required" => "wajib diisi",
            "email" => "format email tidak valid",
        ]);

        if ($validasi->fails()) {

            return redirect(route("job.divisi.show", $jobDivisi->id) . "#tabs-data-pendukung")
                ->with("error", "Data pendukung tidak lengkap")
                ->withErrors($validasi)
                ->withInput();
        }

        DB::beginTransaction();
        try {

            if (in_array("objek", $dataPendukung) && $request->has("objek")) {
                $this->insertObjekPendukung->execute($dataPendukung, $jobDivisi, $request);
            }

            if (in_array("badan usaha pembeli", $dataPendukung) && $request->has("badan_usaha_pembeli")) {
                $this->handelInsertBadanUsahaPembeli($request, $jobDivisi);
            }

            if (in_array("badan usaha penjual", $dataPendukung) && $request->has("badan_usaha_penjual")) {
                $this->handelInsertBadanUsahaPenjual($request, $jobDivisi);
            }

            if (in_array("usaha debitur", $dataPendukung) && $request->has("badan_usaha_debitur")) {
                $insertDebitur =  $this->handelInsertBadanUsahaDebitur($request, $jobDivisi);
            }

            if (in_array("debitur", $dataPendukung) && $request->has("debitur")) {
                $insertDebitur =  $this->handelInsertDebitur($request, $jobDivisi);
            }

            if (in_array("penjual", $dataPendukung) && $request->has("penjual")) {
                $insertPenjual =  $this->handelInsertPenjual($request, $jobDivisi);
            }
            if (in_array("pembeli", $dataPendukung) && $request->has("pembeli")) {
                $insertPembeli =  $this->handelInsertPembeli($request, $jobDivisi);
            }


            if (in_array("developer", $dataPendukung) && $request->has("developer")) {
                $insertDeveloper = $this->handelInsertDeveloper($request, $jobDivisi);
            }
            if (in_array("bank", $dataPendukung) && $request->has("bank")) {
                $insertBank = $this->handelInsertBank($request, $jobDivisi);
            }
            if (in_array("broker", $dataPendukung) && $request->has("broker")) {
                $insertBroker = $this->handelInsertBroker($request, $jobDivisi);
            }
            if (in_array("pendirian lembaga", $dataPendukung) && $request->has("pendirian_lembaga")) {
                $insertPendirianLembaga = $this->handelInsertPendirianLembaga($request, $jobDivisi);
            }
            if (in_array("badan hukum", $dataPendukung) && $request->has("badan_hukum")) {
                $insertBadanHukum = $this->handelInsertBadanHukum($request, $jobDivisi);
            }


            DB::commit();

            return redirect(
                route('job.divisi.show', $jobDivisi->id) . '#tabs-data-pendukung'
            )->with("success", "Data pendukung berhasil disimpan");
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with("error", $th->getMessage())
                ->withInput();
        }
    }

    public function handelInsertDebitur(Request $request, $jobDivisi)
    {
        // Kita simpan debiturnya satu per satu supaya dapet ID-nya untuk relasi file
        foreach ($request->debitur as $item) {
            $debitur = Debitur::create([
                "job_divisi_id" => $jobDivisi->id,
                "nama" => $item["nama_lengkap"],
                "nomor_telepon" => $item["phone"],
                "email" => $item["email"],
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            // Cek kalau ada upload file (bisa multiple)
            if (isset($item["file"]) && is_array($item["file"])) {
                foreach ($item["file"] as $file) {
                    $path = $file->store("debitur", 'public');
                    $debitur->files()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        }
    }

    public function editDebitur($id)
    {
        // Eager load relasi files
        $debitur = Debitur::with('files')->findOrFail($id);
        $jobDivisi = JobDivisi::findOrFail($debitur->job_divisi_id);

        return view('pages.Job.Divisi.edit.edit_debitur', compact('debitur', 'jobDivisi'));
    }

    public function updateDebitur(Request $request)
    {
        $debitur = Debitur::findOrFail($request->id);

        $request->validate([
            'nama_lengkap' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'files.*' => 'nullable|file|max:10240', // Maksimal 10MB per file
        ]);

        $debitur->update([
            'nama' => $request->nama_lengkap,
            'nomor_telepon' => $request->phone,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        // Kalau ada file baru yang diupload pas edit, tambahkan ke relasi (tanpa hapus yang lama)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('debitur', 'public');
                $debitur->files()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect(
            route('job.divisi.show', $debitur->job_divisi_id) . '#tabs-data-pendukung'
        )->with('success', 'Data debitur dan lampiran berhasil diperbarui');
    }

    // Method BARU untuk menghapus 1 file spesifik (buatkan routenya di web.php)
    public function destroyFileDebitur($id)
    {
        $file = JobDivisiFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus');
    }

    public function deleteDebitur($id)
    {
        $debitur = Debitur::findOrFail($id);

        if (
            $debitur->file &&
            file_exists(storage_path('app/public/' . $debitur->file))
        ) {
            unlink(storage_path('app/public/' . $debitur->file));
        }

        $jobDivisiId = $debitur->job_divisi_id;

        $debitur->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data debitur berhasil dihapus');
    }


    public function handelInsertBank(Request $request, $jobDivisi)
    {
        $formDataBank = collect($request->bank);

        $bank = Bank::whereIn("id", $formDataBank->pluck("bank")->toArray())->get();

        // Kita simpan banknya satu per satu supaya dapet ID-nya untuk relasi file (Persis seperti Debitur)
        foreach ($formDataBank as $item) {
            $getBank = $bank->where("id", $item["bank"])->first();

            $jobBank = JobBank::create([
                "job_divisi_id" => $jobDivisi->id,
                "bank_id" => $getBank->id,
                "nama_bank" => $getBank->nama,
                "pimpinan" => $getBank->nama_pimpinan_sekarang,
                "head_legal" => $item["kepala_legal"],
                "legal" => $item["legal"],
                "head_marketing" => $item["kepala_marketing"],
                "marketing" => $item["marketing"],
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            // Cek kalau ada upload file (bisa multiple)
            if (isset($item["file"]) && is_array($item["file"])) {
                foreach ($item["file"] as $file) {
                    $path = $file->store("bank", 'public');
                    // Asumsi lu pake model relasi files() di dalam JobBank, kayak di Debitur
                    $jobBank->files()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        }

        return true; // Atau return redirect() sesuai kebutuhan sistem lu
    }

    public function editBank($id)
    {
        // Eager load relasi files (Persis seperti Debitur)
        $bankData = JobBank::with('files')->findOrFail($id);

        $jobDivisi = JobDivisi::findOrFail($bankData->job_divisi_id);

        $bank = Bank::with([
            'kepalaLegal',
            'legal',
            'kepalaMarketing',
            'marketing'
        ])->get();

        return view(
            'pages.Job.Divisi.edit.edit_bank',
            compact('bankData', 'jobDivisi', 'bank')
        );
    }

    public function updateBank(Request $request)
    {
        $jobBank = JobBank::findOrFail($request->id);

        $request->validate([
            "bank" => "required",
            "kepalaLegal" => "required",
            "legal" => "required",
            "kepalaMarketing" => "required",
            "marketing" => "required",
            'files.*' => 'nullable|file|max:10240', // Maksimal 10MB per file
        ]);

        $bank = Bank::findOrFail($request->bank);

        $jobBank->update([
            "bank_id" => $bank->id,
            "nama_bank" => $bank->nama,
            "pimpinan" => $bank->nama_pimpinan_sekarang,
            "head_legal" => $request->kepalaLegal,
            "legal" => $request->legal,
            "head_marketing" => $request->kepalaMarketing,
            "marketing" => $request->marketing,
            "updated_at" => now(),
        ]);

        // Kalau ada file baru yang diupload pas edit, tambahkan ke relasi (tanpa hapus yang lama)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('bank', 'public');
                $jobBank->files()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect(
            route('job.divisi.show', $jobBank->job_divisi_id) . '#tabs-data-pendukung'
        )->with('success', 'Data bank dan lampiran berhasil diperbarui');
    }

    // Method BARU untuk menghapus 1 file spesifik
    public function destroyFileBank($id)
    {
        // Pake model yang sama persis lu pake di Debitur (misal JobDivisiFile atau model file spesifik Bank)
        $file = JobDivisiFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus');
    }

    public function deleteBank($id)
    {
        // Load files juga biar bisa kita hapus fisiknya dari storage
        $bank = JobBank::with('files')->findOrFail($id);

        // Hapus fisik SEMUA file yang berelasi dengan Bank ini dari Storage
        if ($bank->files->count() > 0) {
            foreach ($bank->files as $file) {
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
            }
        }

        $jobDivisiId = $bank->job_divisi_id;

        $bank->delete(); // Pastikan relasi di tabel files udah diset onDelete('cascade') biar data database ikut kehapus otomatis

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data bank berhasil dihapus');
    }


    public function handelInsertBadanHukum(Request $request, $jobDivisi)
    {
        foreach ($request->badan_hukum as $idx => $item) {
            // 1. Simpan Data Badan Hukum
            $badanHukum = JobDivisiBadanHukum::create([
                "job_divisi_id" => $jobDivisi->id,
                "user_id"       => auth()->id(),
                "nama_pt"       => $item["nama_pt"],
                "npwp"          => $item["npwp"],
                "nib"           => $item["nib"],
                "nama_dirut"    => $item["nama_dirut"],
                "alamat"        => $item["alamat"],
                "phone"         => $item["phone"],
            ]);

            // 2. Simpan File Lampiran Polimorfik (Multiple Uploads)
            // Handle upload file baru (tanpa menimpa yang lama)
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('badan-hukum', 'public');

                    $badanHukum->files()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        // Hapus baris 'file_type' di sini
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data Badan Hukum beserta file berhasil disimpan');
    }

    public function editBadanHukum($id)
    {
        // Eager loading relasi files agar performanya cepat
        $badanHukum = JobDivisiBadanHukum::with('files')->findOrFail($id);
        $jobDivisi  = JobDivisi::findOrFail($badanHukum->job_divisi_id);

        return view(
            'pages.Job.Divisi.edit.edit_badan_hukum',
            compact('badanHukum', 'jobDivisi')
        );
    }

    public function updateBadanHukum(Request $request)
    {
        $request->validate([
            'nama_pt'    => 'required',
            'npwp'       => 'required',
            'nib'        => 'required',
            'nama_dirut' => 'required',
            'phone'      => 'required',
            'alamat'     => 'required',
        ]);

        $badanHukum = JobDivisiBadanHukum::findOrFail($request->id);

        $badanHukum->update([
            'nama_pt'    => $request->nama_pt,
            'npwp'       => $request->npwp,
            'nib'        => $request->nib,
            'nama_dirut' => $request->nama_dirut,
            'phone'      => $request->phone,
            'alamat'     => $request->alamat,
        ]);

        // Handle upload file baru (tanpa menimpa yang lama)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('badan-hukum', 'public');

                $badanHukum->files()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    // Hapus baris 'file_type' di sini
                ]);
            }
        }
        return redirect(
            route('job.divisi.show', $badanHukum->job_divisi_id) . '#tabs-data-pendukung'
        )->with('success', 'Data badan hukum berhasil diupdate');
    }

    public function destroyBadanHukumFile($id)
    {
        // 1. Cari file dari tabel job_divisi_files
        $file = JobDivisiFile::findOrFail($id);

        // 2. Hapus file fisik di folder storage
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        // 3. Hapus record file dari database
        $file->delete();

        return back()->with('success', 'Berkas lampiran berhasil dihapus!');
    }

    public function deleteBadanHukum($id)
    {
        $data = JobDivisiBadanHukum::findOrFail($id);

        $jobDivisiId = $data->job_divisi_id;

        $data->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data badan hukum berhasil dihapus');
    }


    public function handelInsertBroker(Request $request, $jobDivisi)
    {
        $formDataBroker = collect($request->broker);

        $getAllBroker = Broker::whereIn("id", $formDataBroker->pluck("broker")->toArray())
            ->with("marketing")
            ->get();

        $formDataBroker = $formDataBroker
            ->map(function ($item) use ($jobDivisi, $getAllBroker) {
                $getBroker = $getAllBroker->where("id", $item["broker"])->first();
                $getMarketing = $getBroker->marketing->where("id", $item["nama_agent"])->first();

                return [
                    "job_divisi_id" => $jobDivisi->id,
                    "created_at" => now(),
                    "updated_at" => now(),
                    "nama_pt" => $getBroker->nama_pt,
                    "nama_pimpinan" => $getBroker->nama_pimpinan,
                    "nama_agent" => $getMarketing->nama,
                    "nomor_agent" => $getMarketing->no_telepon,
                ];
            })->toArray();

        return JobDivisiBroker::insert($formDataBroker);
    }

    public function editBroker($id)
    {
        $jobBroker = JobDivisiBroker::findOrFail($id);

        $jobDivisi = JobDivisi::find($jobBroker->job_divisi_id);

        $broker = Broker::with('marketing')->get();

        return view(
            'pages.Job.Divisi.edit.edit_broker',
            compact(
                'jobBroker',
                'jobDivisi',
                'broker'
            )
        );
    }
    public function updateBroker(Request $request)
    {
        $jobBroker = JobDivisiBroker::findOrFail($request->id);

        $jobDivisiId = $jobBroker->job_divisi_id;

        $broker = Broker::with('marketing')
            ->findOrFail($request->broker);

        $marketing = $broker->marketing
            ->where('id', $request->nama_agent)
            ->first();

        $jobBroker->update([

            'nama_pt' => $broker->nama_pt,
            'nama_pimpinan' => $broker->nama_pimpinan,

            'nama_agent' => $marketing?->nama,
            'nomor_agent' => $marketing?->no_telepon,

            'updated_at' => now(),
        ]);

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data broker berhasil diupdate');
    }

    public function deleteBroker($id)
    {
        $broker = JobDivisiBroker::findOrFail($id);

        $jobDivisiId = $broker->job_divisi_id;

        $broker->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data broker berhasil dihapus');
    }



    public function handelInsertDeveloper(Request $request, $jobDivisi)
    {
        $formDataDeveloper = collect($request->developer);

        $developer = Developer::whereIn("id", $formDataDeveloper->pluck("developer")->toArray())
            ->with("marketing")
            ->get();

        $formDataDeveloper = $formDataDeveloper
            ->map(function ($item) use ($jobDivisi, $developer) {
                $getDeveloper = $developer->where("id", $item["developer"])->first();
                $getMarketing = $getDeveloper->marketing->where("id", $item["nama_agent"])->first();

                return [
                    "job_divisi_id" => $jobDivisi->id,
                    "developer_id" => $item["developer"],
                    "nama_perumahan" => $getDeveloper->nama_perumahan,
                    "email_perusahaan" => $getDeveloper->email_perusahaan,
                    "nama_pt" => $getDeveloper->nama_pt,
                    "nama_pimpinan" => $getDeveloper->nama_pimpinan,
                    "nama_agent" => $getMarketing->nama,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            })->toArray();


        return JobDeveloper::insert($formDataDeveloper);
    }

    public function editDeveloper($id)
    {
        $jobDeveloper = JobDeveloper::findOrFail($id);
        // dd($jobDeveloper->nama_agent, $jobDeveloper->developer_id);

        $jobDivisi = JobDivisi::findOrFail($jobDeveloper->job_divisi_id);

        $developer = Developer::with('marketing')->get();

        return view(
            'pages.Job.Divisi.edit.edit_developer',
            compact(
                'jobDeveloper',
                'jobDivisi',
                'developer'
            )
        );
    }
    public function updateDeveloper(Request $request)
    {
        $jobDeveloper = JobDeveloper::findOrFail($request->id);

        $developer = Developer::with('marketing')
            ->findOrFail($request->developer);

        $marketing = $developer->marketing
            ->where('id', $request->nama_agent)
            ->first();

        $jobDeveloper->update([

            'nama_perumahan' => $developer->nama_perumahan,
            'email_perusahaan' => $developer->email_perusahaan,
            'nama_pt' => $developer->nama_pt,
            'nama_pimpinan' => $developer->nama_pimpinan,

            'nama_agent' => $marketing?->nama,

            'updated_at' => now(),
        ]);

        return redirect(
            route('job.divisi.show', $jobDeveloper->job_divisi_id) . '#tabs-data-pendukung'
        )->with('success', 'Data developer berhasil diupdate');
    }

    public function deleteDeveloper($id)
    {
        $developer = JobDeveloper::findOrFail($id);

        $jobDivisiId = $developer->job_divisi_id;

        $developer->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data developer berhasil dihapus');
    }


    public function handelInsertPenjual(Request $request, $jobDivisi)
    {
        if ($request->has('penjual')) {
            foreach ($request->penjual as $item) {
                $penjual = Penjual::create([
                    "job_divisi_id" => $jobDivisi->id,
                    "nama"          => $item["nama_lengkap"],
                    "nomor_telepon" => $item["phone"],
                    "email"         => $item["email"],
                    "created_at"    => now(),
                    "updated_at"    => now(),
                ]);

                // Cek kalau ada upload file (multiple files)
                if (isset($item["files"]) && is_array($item["files"])) {
                    foreach ($item["files"] as $file) {
                        $path = $file->store("penjual", 'public');
                        $penjual->files()->create([
                            'file_path' => $path,
                            'file_name' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }
        }
    }

    public function editPenjual($id)
    {
        // Eager load relasi files
        $penjual = Penjual::with('files')->findOrFail($id);

        $jobDivisi = JobDivisi::findOrFail(
            $penjual->job_divisi_id
        );

        return view(
            'pages.Job.Divisi.edit.edit_penjual',
            compact(
                'penjual',
                'jobDivisi'
            )
        );
    }

    public function updatePenjual(Request $request)
    {
        $request->validate([
            'id'            => 'required',
            'nama'          => 'required',
            'nomor_telepon' => 'required',
            'email'         => 'required|email',
            'files.*'       => 'nullable|file|max:10240', // Limit per file 10MB
        ]);

        $penjual = Penjual::findOrFail(
            $request->id
        );

        $penjual->update([
            'nama'          => $request->nama,
            'nomor_telepon' => $request->nomor_telepon,
            'email'         => $request->email,
            'updated_at'    => now(),
        ]);

        // Tambahkan file baru jika ada tanpa menghapus file lama
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('penjual', 'public');
                $penjual->files()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect(
            route(
                'job.divisi.show',
                $penjual->job_divisi_id
            ) . '#tabs-data-pendukung'
        )->with(
            'success',
            'Data penjual dan lampiran berhasil diperbarui'
        );
    }

    // Method Baru: Hapus 1 file spesifik secara independen
    public function destroyFilePenjual($id)
    {
        $file = JobDivisiFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus');
    }

    public function deletePenjual($id)
    {
        $penjual = Penjual::with('files')->findOrFail($id);

        // Hapus seluruh file fisik yang berelasi di storage
        foreach ($penjual->files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }

        $jobDivisiId = $penjual->job_divisi_id;

        $penjual->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data penjual berhasil dihapus');
    }

    public function handelInsertPembeli(Request $request, $jobDivisi)
    {
        if ($request->has('pembeli')) {
            foreach ($request->pembeli as $item) {
                $pembeli = Pembeli::create([
                    "job_divisi_id" => $jobDivisi->id,
                    "nama"          => $item["nama_lengkap"],
                    "nomor_telepon" => $item["phone"],
                    "email"         => $item["email"],
                    "created_at"    => now(),
                    "updated_at"    => now(),
                ]);

                // Cek kalau ada upload file (mendukung multiple files)
                if (isset($item["files"]) && is_array($item["files"])) {
                    foreach ($item["files"] as $file) {
                        $path = $file->store("pembeli", 'public');
                        $pembeli->files()->create([
                            'file_path' => $path,
                            'file_name' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }
        }
    }

    public function editPembeli($id)
    {
        // Eager load relasi files
        $pembeli = Pembeli::with('files')->findOrFail($id);

        $jobDivisi = JobDivisi::findOrFail(
            $pembeli->job_divisi_id
        );

        return view(
            'pages.Job.Divisi.edit.edit_pembeli',
            compact(
                'pembeli',
                'jobDivisi'
            )
        );
    }

    public function updatePembeli(Request $request)
    {
        $request->validate([
            'id'            => 'required',
            'nama'          => 'required',
            'nomor_telepon' => 'required',
            'email'         => 'required|email',
            'files.*'       => 'nullable|file|max:10240', // Limit per file 10MB
        ]);

        $pembeli = Pembeli::findOrFail(
            $request->id
        );

        $pembeli->update([
            'nama'          => $request->nama,
            'nomor_telepon' => $request->nomor_telepon,
            'email'         => $request->email,
            'updated_at'    => now(),
        ]);

        // Tambahkan file baru jika ada tanpa menghapus file lama
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('pembeli', 'public');
                $pembeli->files()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect(
            route(
                'job.divisi.show',
                $pembeli->job_divisi_id
            ) . '#tabs-data-pendukung'
        )->with(
            'success',
            'Data pembeli dan lampiran berhasil diperbarui'
        );
    }

    // Method Baru: Hapus 1 file spesifik secara independen
    public function destroyFilePembeli($id)
    {
        $file = JobDivisiFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus');
    }

    public function deletePembeli($id)
    {
        $pembeli = Pembeli::with('files')->findOrFail($id);

        // Hapus seluruh file fisik yang berelasi di storage
        foreach ($pembeli->files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }

        $jobDivisiId = $pembeli->job_divisi_id;

        $pembeli->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data pembeli berhasil dihapus');
    }

    public function handelInsertBadanUsahaPenjual(Request $request, $jobDivisi)
    {
        if ($request->has('badan_usaha_penjual')) {
            foreach ($request->badan_usaha_penjual as $item) {
                $badanUsahaPenjual = JobDivisiBadanUsahaPenjual::create([
                    "job_divisi_id"    => $jobDivisi->id,
                    "nama_badan_usaha" => $item["nama_badan_usaha"],
                    "nama_perwakilan"  => $item["nama_perwakilan"],
                    "no_telepon"       => $item["nomor_telepon"],
                    "email"            => $item["email"],
                    "created_at"       => now(),
                    "updated_at"       => now(),
                ]);

                // Cek kalau ada upload file (bisa multiple)
                if (isset($item["files"]) && is_array($item["files"])) {
                    foreach ($item["files"] as $file) {
                        $path = $file->store("badan_usaha_penjual", 'public');
                        $badanUsahaPenjual->files()->create([
                            'file_path' => $path,
                            'file_name' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }
        }
    }

    public function editBadanUsahaPenjual($id)
    {
        // Eager load relasi files
        $badanUsahaPenjual = JobDivisiBadanUsahaPenjual::with('files')->findOrFail($id);
        $jobDivisi = JobDivisi::findOrFail($badanUsahaPenjual->job_divisi_id);

        return view('pages.Job.Divisi.edit.edit_badan_usaha_penjual', compact('badanUsahaPenjual', 'jobDivisi'));
    }

    public function updateBadanUsahaPenjual(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nama_badan_usaha' => 'required',
            'nama_perwakilan' => 'required',
            'nomor_telepon' => 'required',
            'email' => 'required|email',
            'files.*' => 'nullable|file|max:10240', // Maksimal 10MB per file
        ]);

        $badanUsahaPenjual = JobDivisiBadanUsahaPenjual::findOrFail($request->id);

        $badanUsahaPenjual->update([
            'nama_badan_usaha' => $request->nama_badan_usaha,
            'nama_perwakilan' => $request->nama_perwakilan,
            'no_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        // Kalau ada file baru yang diupload pas edit, tambahkan ke relasi (tanpa menghapus file lama)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('badan_usaha_penjual', 'public');
                $badanUsahaPenjual->files()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect(
            route('job.divisi.show', $badanUsahaPenjual->job_divisi_id) . '#tabs-data-pendukung'
        )->with('success', 'Data badan usaha penjual dan lampiran berhasil diperbarui');
    }

    // Method untuk menghapus 1 file spesifik
    public function destroyFileBadanUsahaPenjual($id)
    {
        $file = JobDivisiFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus');
    }

    public function deleteBadanUsahaPenjual($id)
    {
        $badanUsahaPenjual = JobDivisiBadanUsahaPenjual::with('files')->findOrFail($id);

        // Hapus semua file fisik yang berelasi
        foreach ($badanUsahaPenjual->files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }

        $jobDivisiId = $badanUsahaPenjual->job_divisi_id;
        $badanUsahaPenjual->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data badan usaha penjual berhasil dihapus');
    }
    public function handelInsertBadanUsahaPembeli(Request $request, $jobDivisi)
    {
        foreach ($request->badan_usaha_pembeli as $item) {
            $badanUsaha = JobDivisiBadanUsahaPembeli::create([
                "job_divisi_id" => $jobDivisi->id,
                "nama_badan_usaha" => $item["nama_badan_usaha"],
                "nama_perwakilan" => $item["nama_perwakilan"],
                "no_telepon" => $item["nomor_telepon"],
                "email" => $item["email"],
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            // Cek kalau ada upload file (bisa multiple)
            if (isset($item["files"]) && is_array($item["files"])) {
                foreach ($item["files"] as $file) {
                    $path = $file->store("badan_usaha_pembeli", 'public');
                    $badanUsaha->files()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        }
    }

    public function editBadanUsahaPembeli($id)
    {
        // Eager load relasi files
        $badanUsahaPembeli = JobDivisiBadanUsahaPembeli::with('files')->findOrFail($id);
        $jobDivisi = JobDivisi::findOrFail($badanUsahaPembeli->job_divisi_id);

        return view('pages.Job.Divisi.edit.edit_badan_usaha_pembeli', compact('badanUsahaPembeli', 'jobDivisi'));
    }

    public function updateBadanUsahaPembeli(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nama_badan_usaha' => 'required',
            'nama_perwakilan' => 'required',
            'nomor_telepon' => 'required',
            'email' => 'required|email',
            'files.*' => 'nullable|file|max:10240', // Maksimal 10MB per file
        ]);

        $badanUsahaPembeli = JobDivisiBadanUsahaPembeli::findOrFail($request->id);

        $badanUsahaPembeli->update([
            'nama_badan_usaha' => $request->nama_badan_usaha,
            'nama_perwakilan' => $request->nama_perwakilan,
            'no_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        // Kalau ada file baru yang diupload pas edit, tambahkan ke relasi (tanpa menghapus file lama)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('badan_usaha_pembeli', 'public');
                $badanUsahaPembeli->files()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect(
            route('job.divisi.show', $badanUsahaPembeli->job_divisi_id) . '#tabs-data-pendukung'
        )->with('success', 'Data badan usaha pembeli dan lampiran berhasil diperbarui');
    }

    // Method untuk menghapus 1 file spesifik (buatkan route dengan method DELETE)
    public function destroyFileBadanUsahaPembeli($id)
    {
        $file = JobDivisiFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus');
    }

    public function deleteBadanUsahaPembeli($id)
    {
        $badanUsahaPembeli = JobDivisiBadanUsahaPembeli::with('files')->findOrFail($id);

        // Hapus semua file fisik yang berelasi
        foreach ($badanUsahaPembeli->files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }

        $jobDivisiId = $badanUsahaPembeli->job_divisi_id;
        $badanUsahaPembeli->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data badan usaha pembeli berhasil dihapus');
    }

    public function handelInsertBadanUsahaDebitur(Request $request, $jobDivisi)
    {
        if ($request->has('badan_usaha_debitur')) {
            foreach ($request->badan_usaha_debitur as $item) {
                $badanUsahaDebitur = JobDivisiBadanUsahaDebitur::create([
                    "job_divisi_id"    => $jobDivisi->id,
                    "nama_badan_usaha" => $item["nama_badan_usaha"],
                    "nama_perwakilan"  => $item["nama_perwakilan"],
                    "no_telepon"       => $item["nomor_telepon"],
                    "email"            => $item["email"],
                    "created_at"       => now(),
                    "updated_at"       => now(),
                ]);

                // Cek kalau ada upload file (bisa multiple)
                if (isset($item["files"]) && is_array($item["files"])) {
                    foreach ($item["files"] as $file) {
                        $path = $file->store("badan_usaha_debitur", 'public');
                        $badanUsahaDebitur->files()->create([
                            'file_path' => $path,
                            'file_name' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }
        }
    }

    public function editBadanUsahaDebitur($id)
    {
        // Eager load relasi files
        $badanUsahaDebitur = JobDivisiBadanUsahaDebitur::with('files')->findOrFail($id);

        $jobDivisi = JobDivisi::findOrFail(
            $badanUsahaDebitur->job_divisi_id
        );

        return view(
            'pages.Job.Divisi.edit.edit_badan_usaha_debitur',
            compact(
                'badanUsahaDebitur',
                'jobDivisi'
            )
        );
    }

    public function updateBadanUsahaDebitur(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nama_badan_usaha' => 'required',
            'nama_perwakilan' => 'required',
            'nomor_telepon' => 'required',
            'email' => 'required|email',
            'files.*' => 'nullable|file|max:10240', // Maksimal 10MB per file
        ]);

        $badanUsahaDebitur = JobDivisiBadanUsahaDebitur::findOrFail(
            $request->id
        );

        $badanUsahaDebitur->update([
            'nama_badan_usaha' => $request->nama_badan_usaha,
            'nama_perwakilan'  => $request->nama_perwakilan,
            'no_telepon'       => $request->nomor_telepon,
            'email'            => $request->email,
            'updated_at'       => now(),
        ]);

        // Jika ada file baru yang di-upload saat edit, tambahkan ke relasi files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('badan_usaha_debitur', 'public');
                $badanUsahaDebitur->files()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect(
            route(
                'job.divisi.show',
                $badanUsahaDebitur->job_divisi_id
            ) . '#tabs-data-pendukung'
        )->with(
            'success',
            'Data badan usaha debitur dan lampiran berhasil diperbarui'
        );
    }

    // Method untuk menghapus 1 file spesifik secara independen
    public function destroyFileBadanUsahaDebitur($id)
    {
        $file = JobDivisiFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus');
    }

    public function deleteBadanUsahaDebitur($id)
    {
        $badanUsahaDebitur = JobDivisiBadanUsahaDebitur::with('files')->findOrFail($id);

        // Hapus seluruh file fisik yang berelasi di storage
        foreach ($badanUsahaDebitur->files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }

        $jobDivisiId = $badanUsahaDebitur->job_divisi_id;

        $badanUsahaDebitur->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data badan usaha debitur berhasil dihapus');
    }


    public function handelInsertPendirianLembaga(Request $request, $jobDivisi)
    {
        $formDataPendirianLembaga = collect($request->pendirian_lembaga)->map(function ($item) use ($jobDivisi) {
            return [
                "job_divisi_id" => $jobDivisi->id,
                "nama_lembaga" => $item["nama_lembaga"],
                "bidang_usaha" => $item["bidang_usaha"],
                "modal_dasar" => str_replace(".", "", $item["modal_dasar"]),
                "modal_setor" => str_replace(".", "", $item["modal_setor"]),
                "pemegang_saham" => $item["pemegang_saham"],
                "jajaran_direksi" => $item["jajaran_direksi"] ?? null,
                "jajaran_komisaris" => $item["jajaran_komisaris"] ?? null,
                "alamat" => $item["alamat"],
                "created_at" => now(),
                "updated_at" => now(),
            ];
        })->toArray();

        return JobDivisiPendirianLembaga::insert($formDataPendirianLembaga);
    }

    public function editPendirianLembaga($id)
    {
        $pendirianLembaga = JobDivisiPendirianLembaga::findOrFail($id);

        $jobDivisi = $pendirianLembaga->jobDivisi;

        return view(
            'pages.Job.Divisi.edit.edit_pendirian_lembaga',
            compact(
                'pendirianLembaga',
                'jobDivisi'
            )
        );
    }

    public function updatePendirianLembaga(Request $request)
    {
        $pendirianLembaga = JobDivisiPendirianLembaga::findOrFail($request->id);

        $pendirianLembaga->update([
            "nama_lembaga" => $request->nama_lembaga,
            "bidang_usaha" => $request->bidang_usaha,
            "modal_dasar" => str_replace(".", "", $request->modal_dasar),
            "modal_setor" => str_replace(".", "", $request->modal_setor),
            "pemegang_saham" => $request->pemegang_saham,
            "jajaran_direksi" => $request->jajaran_direksi,
            "jajaran_komisaris" => $request->jajaran_komisaris,
            "alamat" => $request->alamat,
            "updated_at" => now(),
        ]);

        return redirect(

            route('job.divisi.show', $pendirianLembaga->job_divisi_id) . '#tabs-data-pendukung'
        )
            ->with('success', 'Data pendirian lembaga berhasil diupdate');
    }
    public function deletePendirianLembaga($id)
    {
        $data = JobDivisiPendirianLembaga::findOrFail($id);

        $jobDivisiId = $data->job_divisi_id;

        $data->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data pendirian lembaga berhasil dihapus');
    }


    public function editObjek($id)
    {
        // Eager load relasi files
        $objek = JobDivisiObjek::with('files')->findOrFail($id);

        $jobDivisi = JobDivisi::find($objek->job_divisi_id);
        $kecamtan = Kecamatan::all();

        $desa = Desa::with('kecamatan')->get();

        $dataPendukungObjek = $jobDivisi->jenisAkad->data_pendukung
            ? explode(",", $jobDivisi->jenisAkad->data_pendukung)
            : null;

        return view(
            'pages.Job.Divisi.edit.edit_objek',
            compact(
                'objek',
                'kecamtan',
                'jobDivisi',
                'desa',
                'dataPendukungObjek'
            )
        );
    }

    public function updateObjek(Request $request)
    {
        $request->validate([
            'id'      => 'required',
            'files.*' => 'nullable|file|max:10240', // Limit per file max 10MB
        ]);

        $objek = JobDivisiObjek::findOrFail($request->id);

        $data = [
            "desa_id"          => $request->desa_id,
            "jenis_sertifikat" => $request->jenis_sertifikat,
            "no_sertifikat"    => $request->no_sertifikat,
            "nama_pemilik"     => $request->nama_pemilik,
            "luas_tanah"       => $request->luas_tanah,
            "updated_at"       => now(),
            "alamat"           => $request->alamat,
        ];

        if ($request->filled("nilai_ht")) {
            $data["nilai_ht"] = str_replace(".", "", $request->nilai_ht);
        }

        if ($request->filled("nilai_plafond")) {
            $data["nilai_plafond"] = str_replace(".", "", $request->nilai_plafond);
        }

        if ($request->filled("nilai_transaksi")) {
            $data["nilai_transaksi"] = str_replace(".", "", $request->nilai_transaksi);
        }

        $objek->update($data);

        // Tambahkan file baru jika ada (Multiple upload tanpa menimpa file lama)
        if ($request->hasFile("files")) {
            foreach ($request->file("files") as $file) {
                $path = $file->store("objek", "public");
                $objek->files()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect(
            route('job.divisi.show', $objek->job_divisi_id) . '#tabs-data-pendukung'
        )->with('success', 'Data objek dan lampiran berhasil diupdate');
    }

    // Method baru untuk menghapus 1 file spesifik secara independen via AJAX/SweetAlert
    public function destroyFileObjek($id)
    {
        $file = JobDivisiFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File lampiran berhasil dihapus');
    }

    public function deleteObjek($id)
    {
        $objek = JobDivisiObjek::with('files')->findOrFail($id);

        // Hapus seluruh file fisik terkait di storage
        foreach ($objek->files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }

        $jobDivisiId = $objek->job_divisi_id;

        $objek->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data objek berhasil dihapus');
    }
}
