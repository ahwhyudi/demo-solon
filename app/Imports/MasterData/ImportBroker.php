<?php

namespace App\Imports\MasterData;

use App\Models\Broker;
use App\Models\BrokerMarketing;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportBroker implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $formDataBrokerMarketing = [];

        foreach ($collection as $row) {
            // Jaga-jaga jika ada baris kosong di Excel agar tidak ke-import
            if (empty($row['nama_perumahan'])) {
                continue;
            }

            // 1. Simpan Data Master Broker
            $broker = Broker::create([
                "nama_perumahan"   => $row['nama_perumahan'],
                "nama_pt"          => $row['nama_pt'],
                "nama_pimpinan"    => $row['nama_pimpinan'],
                "email_perusahaan" => $row['email_perusahaan'],
                "is_active"        => 1
            ]);

            // 2. Tampung data Relasi Broker Marketing (Pecah dengan explode jika multiple)
            if (!empty($row['broker_marketing'])) {
                $broker_marketings = explode(';', $row['broker_marketing']);
                foreach ($broker_marketings as $nama) {
                    if (trim($nama) != '') {
                        $formDataBrokerMarketing[] = [
                            'broker_id'  => $broker->id,
                            'nama'       => trim($nama),
                            'no_telepon' => '-', // Default '-' karena di DB statusnya NOT NULL
                            "created_at" => now(),
                            "updated_at" => now()
                        ];
                    }
                }
            }
        }

        // 3. Eksekusi Bulk Insert di luar loop (Gaya khas kodingan kamu untuk performa tinggi)
        if (!empty($formDataBrokerMarketing)) {
            BrokerMarketing::insert($formDataBrokerMarketing);
        }
    }
}
