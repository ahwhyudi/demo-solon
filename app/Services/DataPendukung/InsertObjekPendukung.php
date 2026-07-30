<?php

namespace App\Services\DataPendukung;

use App\Models\JobDivisiObjek;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InsertObjekPendukung
{
    public function execute(array $dataPendukung, mixed $jobDivisi, Request $request)
    {
        $validasiRules = [
            "objek"              => "required|array|min:1",
            "objek.*.files.*"    => "nullable|file|max:10240", // Validasi per file max 10MB
            "objek.*.desa_id"    => "required",
            "objek.*.jenis_sertifikat" => "required",
            "objek.*.nama_pemilik"     => "required",
            "objek.*.no_sertifikat"    => "required",
        ];

        $dataPendukungObjek = $jobDivisi->jenisAkad->data_pendukung ? explode(",", $jobDivisi->jenisAkad->data_pendukung) : null;

        if ($dataPendukungObjek) {
            if (in_array('nilai_ht', $dataPendukungObjek)) {
                $validasiRules["objek.*.nilai_ht"] = "required";
            }
            if (in_array('nilai_transaksi', $dataPendukungObjek)) {
                $validasiRules["objek.*.nilai_transaksi"] = "required";
            }
            if (in_array('nilai_plafond', $dataPendukungObjek)) {
                $validasiRules["objek.*.nilai_plafond"] = "required";
            }
        }

        $validasi = Validator::make($request->all(), $validasiRules);

        if ($validasi->fails()) {
            throw new Exception("Data objek Belum Lengkap");
        }

        $request->validate($validasiRules);

        $this->handelInsertObjek($request, $jobDivisi);
    }

    public function handelInsertObjek(Request $request, mixed $jobDivisi)
    {
        if ($request->has('objek')) {
            foreach ($request->objek as $item) {
                $data = [
                    "job_divisi_id"    => $jobDivisi->id,
                    "desa_id"          => $item["desa_id"],
                    "jenis_sertifikat" => $item["jenis_sertifikat"],
                    "no_sertifikat"    => $item["no_sertifikat"],
                    "nama_pemilik"     => $item["nama_pemilik"],
                    "luas_tanah"       => $item["luas_tanah"] ?? null,
                    "alamat"           => $item["alamat"] ?? null,
                    "nib"              => $item["nib"] ?? null,
                    "pbb"              => $item["pbb"] ?? null,
                    "njop"             => $item["njop"] ?? null,
                    "no_sppt"          => $item["no_sppt"] ?? null,
                    "created_at"       => now(),
                    "updated_at"       => now(),
                ];

                if (isset($item["nilai_ht"])) {
                    $data["nilai_ht"] = str_replace(".", "", $item["nilai_ht"]);
                }

                if (isset($item["nilai_plafond"])) {
                    $data["nilai_plafond"] = str_replace(".", "", $item["nilai_plafond"]);
                }

                if (isset($item["nilai_transaksi"])) {
                    $data["nilai_transaksi"] = str_replace(".", "", $item["nilai_transaksi"]);
                }

                // Buat record Objek terlebih dahulu untuk mendapatkan instance model
                $objek = JobDivisiObjek::create($data);

                // Tangani multiple file upload via relasi morphMany (files())
                if (isset($item["files"]) && is_array($item["files"])) {
                    foreach ($item["files"] as $file) {
                        $path = $file->store("objek", 'public');
                        $objek->files()->create([
                            'file_path' => $path,
                            'file_name' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }
        }
    }
}