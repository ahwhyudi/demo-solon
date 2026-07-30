<?php

namespace App\Http\Controllers\Job;

use App\Models\JobDivisi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateStatusJobDivisiController extends Controller
{
    public function updateSelesai($job_divisi_id)
    {
        return 1;
        $job_divisi = JobDivisi::with("formOrder.statusJobOps")->find($job_divisi_id);
        $formOrder = $job_divisi->formOrder;

        $statusSelesai = [
            "operasional" => false,
            "notaris" => false,
            "legalisasi" => false,
            "ppat" => false,
            "pajak" => false
        ];

        foreach ($formOrder->groupBy("kategori") as $key => $value) {
            foreach ($value as $item) {
                $statusJobOps = $item->statusJobOps;

                if ($key === "operasional") {
                    $cekSelesai = $statusJobOps->where("status", "Dikembalikan")->first();
                    $statusSelesai["operasional"] = $cekSelesai ? true : false;
                } elseif ($key === "notaris") {
                    $cekSelesai = $statusJobOps->where("status", "Selesai")->first();
                    $statusSelesai["notaris"] = $cekSelesai ? true : false;
                } elseif ($key === "legalisasi") {
                    $cekSelesai = $statusJobOps->where("status", "Selesai")->first();
                    $statusSelesai["legalisasi"] = $cekSelesai ? true : false;
                } elseif ($key === "ppat") {
                    $cekSelesai = $statusJobOps->where("status", "Selesai")->first();
                    $statusSelesai["ppat"] = $cekSelesai ? true : false;
                } elseif ($key === "pajak") {
                    $cekSelesai = $statusJobOps->where("status", "Selesai")->first();
                    $statusSelesai["pajak"] = $cekSelesai ? true : false;
                }
            }
            $statusKategori = $statusSelesai[$key];
            $cekKategori = $formOrder->where("kategori", "!=", $key)->first();
            if (!$cekKategori) {
                $statusSelesai = [
                    "operasional" => true,
                    "notaris" => true,
                    "legalisasi" => true,
                    "ppat" => true,
                    "pajak" => true
                ];
                $statusSelesai[$key] = $statusKategori;
            }
        }

        if (!in_array(false, $statusSelesai)) {
            $job_divisi->update([
                "status" => "Selesai"
            ]);

            return 1;
        } else {
            return 0;
        }
    }
}
