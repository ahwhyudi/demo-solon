<?php

namespace App\Http\Controllers\Job\DataPendukung;

use App\Http\Controllers\Controller;
use App\Models\Debitur;
use App\Models\JobDivisi;
use App\Models\JobDivisiBadanUsahaDebitur;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DebiturController extends Controller
{
    public function store(Request $request)
    {
        $jobDivisi = JobDivisi::with("jenisAkad")->find($request->job_divisi_id);
        if (!$jobDivisi) {
            return redirect()->back()->with("error", "Job Divisi tidak ditemukan")->withInput();
        }

        $validasiRules = [
            "debitur" => "required|array|min:1",
            "debitur.*.nama_lengkap" => "required|string",
            "debitur.*.phone" => "required",
            // "debitur.*.file" => "required",
            "debitur.*.email" => "required",
        ];

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

        $formDataDebitur = collect($request->debitur)->map(function ($item) use ($jobDivisi) {

            $file = $item["file"] ?? null;
            $filePath = $file?->store("debitur", 'public') ?? null;

            return [
                "job_divisi_id" => $jobDivisi->id,
                "nama" => $item["nama_lengkap"],
                "nomor_telepon" => $item["phone"],
                "email" => $item["email"],
                "file" => $filePath,
                "created_at" => now(),
                "updated_at" => now(),
            ];
        })->toArray();

        DB::beginTransaction();
        try {

            $insertData = Debitur::insert($formDataDebitur);
            DB::commit();
            return redirect(
                route('job.divisi.show', $jobDivisi->id) . '#tabs-data-pendukung'
            )->with("success", "Data pendukung berhasil disimpan");
        } catch (Exception $th) {
            DB::rollBack();

            return redirect()->back()->with("error", "Gagal simpan data pendukung, silahkan coba lagi")
                ->withInput();
        }
    }

    public function editBadanUsahaDebitur($id)
    {
        $badanUsahaDebitur = JobDivisiBadanUsahaDebitur::findOrFail($id);

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
        $badanUsahaDebitur = JobDivisiBadanUsahaDebitur::findOrFail(
            $request->id
        );

        $data = [
            'nama_badan_usaha' => $request->nama_badan_usaha,
            'nama_perwakilan' => $request->nama_perwakilan,
            'no_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'updated_at' => now(),
        ];

        if ($request->hasFile('file')) {

            if (
                $badanUsahaDebitur->file &&
                file_exists(
                    storage_path('app/public/' . $badanUsahaDebitur->file)
                )
            ) {
                unlink(
                    storage_path('app/public/' . $badanUsahaDebitur->file)
                );
            }

            $data['file'] = $request
                ->file('file')
                ->store('badan_usaha_debitur', 'public');
        }

        $badanUsahaDebitur->update($data);

        return redirect(
            route(
                'job.divisi.show',
                $badanUsahaDebitur->job_divisi_id
            ) . '#tabs-data-pendukung'
        )->with(
            'success',
            'Data badan usaha debitur berhasil diupdate'
        );
    }

    public function deleteBadanUsahaDebitur($id)
    {
        $data = JobDivisiBadanUsahaDebitur::findOrFail($id);

        if (
            $data->file &&
            file_exists(storage_path('app/public/' . $data->file))
        ) {
            unlink(storage_path('app/public/' . $data->file));
        }

        $jobDivisiId = $data->job_divisi_id;

        $data->delete();

        return redirect(
            route('job.divisi.show', $jobDivisiId) . '#tabs-data-pendukung'
        )->with('success', 'Data badan usaha debitur berhasil dihapus');
    }
}
