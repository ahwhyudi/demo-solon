<?php

namespace App\Imports\MasterData;

use App\Models\Pekerjaan;
use App\Models\HargaPekerjaan;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportHargaPekerjaan implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     * @throws ValidationException
     */
    public function collection(Collection $collection)
    {

        $formDataHarga = [];

        foreach ($collection as $row) {
            // Jaga-jaga jika nama pekerjaan kosong di baris Excel agar tidak ke-import
            if (empty($row['nama_pekerjaan'])) {
                continue;
            }

            $kategori = !empty($row['kategori']) ? trim(strtolower($row['kategori'])) : 'operasional';

            // 1. Cek atau Buat Data Master Pekerjaan (FirstOrCreate)
            $pekerjaan = Pekerjaan::firstOrCreate(
                [
                    'nama' => trim($row['nama_pekerjaan'])
                ],
                [
                    'kategori'    => $kategori,
                    'harga_modal' => $row['harga_modal'] ?? 0,
                    'harga_jual'  => $row['harga_jual_default'] ?? 0,
                ]
            );

            //  Aturan Proteksi Kategori: Harga limit hanya boleh ada di kategori 'operasional'
            $hargaLimit = 0;
            if ($kategori === 'operasional') {
                $hargaLimit = $row['harga_limit_wilayah'] ?? 0;
                $provinsiId = null;
                $kotaId = null;

                // Validasi Kolom Kota (Optional, tapi jika diisi wajib valid)
                if (!empty($row['kota'])) {
                    $namaKotaInput = trim($row['kota']);

                    $kotaId = DB::table('kotas')
                        ->where('name', 'LIKE', '%' . $namaKotaInput . '%')
                        ->value('id');

                    // JIKA DIISI TAPI TIDAK DITEMUKAN: Lempar error untuk try-catch di controller
                    if (!$kotaId) {
                        // throw ValidationException::withMessages([
                        //     'import_error' => "Nama kota '{$namaKotaInput}' tidak ditemukan di database."
                        // ]);

                        throw new Exception("Nama kota {$namaKotaInput} tidak ditemukan di database.");
                    }
                }

                //  Tampung data ke Array (Bulk Insert Style)
                $formDataHarga[] = [
                    'pekerjaan_id' => $pekerjaan->id,
                    'provinsi_id'  => $provinsiId, // Bisa bernilai null jika kosong
                    'kota_id'      => $kotaId,     // Bisa bernilai null jika kosong
                    'harga_limit'  => $hargaLimit,  // Otomatis 0 jika bukan operasional
                    'harga_jual'   => $row['harga_jual_wilayah'] ?? 0,
                    'harga_proses' => $row['harga_proses_wilayah'] ?? 0,
                    'lama_proses'  => $row['lama_proses_hari'] ?? 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
        }

        // 6. Bulk Insert Sekaligus di luar loop (Gaya performa tinggi khas kamu)
        if (!empty($formDataHarga)) {
            HargaPekerjaan::insert($formDataHarga);
        }
    }
}
