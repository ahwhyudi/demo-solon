<?php

namespace App\Services\PerubahanHarga;

use App\Models\PerubahanHargaJualFo;
use App\Models\PerubahanHargaJualFoDetail;
use Exception;
use Illuminate\Support\Facades\Auth;

class TambahPerubahanHarga
{
    public function execute($job_id, $formOrder)
    {
        // dd($formOrder);
        try {

            $dataLama = PerubahanHargaJualFo::create([
                "job_divisi_id" => $job_id,
                "created_by" => Auth::user()->id
            ]);

            $detailDataLama = [];

            foreach ($formOrder as $itemFormOrder) {
                $detailDataLama[] = [
                    "parent_id" => $dataLama->id,
                    "form_order_id" => $itemFormOrder->id,
                    "objek_id" => $itemFormOrder->objek_id,
                    "created_by" => $itemFormOrder->created_by,
                    "nama" => $itemFormOrder->nama,
                    "harga_modal" => $itemFormOrder->harga_modal,
                    "harga_proses" => $itemFormOrder->harga_proses,
                    "harga_jual" => $itemFormOrder->harga_jual,
                    "diskon" => $itemFormOrder->diskon,
                    "lama_proses" => $itemFormOrder->lama_proses,
                    "status" => $itemFormOrder->status,
                    "kategori" => $itemFormOrder->kategori,
                    "masuk_invoice" => $itemFormOrder->masuk_invoice,
                    "created_at" => $itemFormOrder->created_at,
                    "updated_at" => $itemFormOrder->updated_at
                ];
            }

            $insertDetailDataLama = PerubahanHargaJualFoDetail::insert($detailDataLama);

            return 1;
        } catch (Exception $th) {
            throw $th;
        }
    }
}
