<?php

namespace App\Imports\MasterData;

use App\Models\Developer;
use App\Models\DeveloperLegal;
use App\Models\DeveloperMarketing;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportDeveloper implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $formDataDeveloperLegal = [];
        $formDataDeveloperMarketing = [];

        foreach ($collection as $row) {
            // Jaga-jaga jika ada baris kosong di Excel agar tidak ke-import
            if (empty($row['nama_perumahan'])) {
                continue;
            }

            // 1. Simpan Data Master Developer (Sesuai kolom table developers)
            $developer = Developer::create([
                "nama_perumahan"   => $row['nama_perumahan'],
                "nama_pt"          => $row['nama_pt'],
                "nama_pimpinan"    => $row['nama_pimpinan'],
                "email_perusahaan" => $row['email_perusahaan'],
                "is_active"        => 1
            ]);


            // 2. Tampung data Relasi Developer Legal (Pecah dengan explode jika multiple)
            if (!empty($row['developer_legal'])) {
                $developer_legals = explode(';', $row['developer_legal']);
                foreach ($developer_legals as $nama) {
                    if (trim($nama) != '') {
                        $formDataDeveloperLegal[] = [
                            'developer_id' => $developer->id,
                            'nama'         => trim($nama),
                            "created_at"   => now(),
                            "updated_at"   => now()
                        ];
                    }
                }
            }

            // 3. Tampung data Relasi Developer Marketing (Pecah dengan explode jika multiple)
            if (!empty($row['developer_marketing'])) {
                $developer_marketings = explode(';', $row['developer_marketing']);
                foreach ($developer_marketings as $nama) {
                    if (trim($nama) != '') {
                        $formDataDeveloperMarketing[] = [
                            'developer_id' => $developer->id,
                            'nama'         => trim($nama),
                            'no_telepon'   => '-', // Default '-' karena di DB statusnya NOT NULL
                            "created_at"   => now(),
                            "updated_at"   => now()
                        ];
                    }
                }
            }
        }

        // 4. Eksekusi Bulk Insert di luar loop (Gaya khas kodingan kamu untuk performa tinggi)
        if (!empty($formDataDeveloperLegal)) {
            DeveloperLegal::insert($formDataDeveloperLegal);
        }

        if (!empty($formDataDeveloperMarketing)) {
            DeveloperMarketing::insert($formDataDeveloperMarketing);
        }
    }
}
