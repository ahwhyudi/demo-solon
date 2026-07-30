<?php

namespace App\Services\PerubahanHarga;

use App\Models\JobDivisiFormOrder;
use Exception;

class TolakPerubahanHarga
{
    public function execute($jobDivisi, $perubahanHargaFo)
    {


        try {
            $perubahanHargaFo->status = "tolak";
            $perubahanHargaFo->save();

            // hapus form order
            $HapusformOrder = JobDivisiFormOrder::query()->where("job_divisi_id", $perubahanHargaFo->job_divisi_id)->delete();

            // balikan form order sebelumnya
            $dataFormOrder = [];

            foreach ($perubahanHargaFo->detail as $key => $detail) {
                $arrayData = $detail->toArray();
                unset($arrayData["id"], $arrayData["parent_id"], $arrayData["form_order_id"]);
                $arrayData["job_divisi_id"] = $jobDivisi->id;
                $arrayData["created_at"] = now();
                $arrayData["updated_at"] = now();

                $dataFormOrder[] = $arrayData;
            }

            $insertFormOrder = JobDivisiFormOrder::insert($dataFormOrder);

            return 1;
        } catch (Exception $th) {
            throw $th;
        }
    }
}
