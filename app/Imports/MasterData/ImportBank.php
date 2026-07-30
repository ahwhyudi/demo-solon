<?php

namespace App\Imports\MasterData;

use App\Models\Bank;
use App\Models\BankKepalaLegal;
use App\Models\BankLegal;
use App\Models\BankKepalaMarketing;
use App\Models\BankMarketing;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date; // <--- INI DIA YANG HILANG!

class ImportBank implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $formDataKepalaLegal = [];
        $formDataBankLegal = [];
        $formDataKepalaMarketing = [];
        $formDataBankMarketing = [];

        foreach ($collection as $row) {
            // Jaga-jaga jika ada baris kosong di Excel agar tidak ke-import
            if (empty($row['nama_bank'])) {
                continue;
            }

            $start_kemitraan = null;
            $end_kemitraan = null;

            // 1. Konversi Tanggal Start Kemitraan
            if (!empty($row['start_kemitraan'])) {
                if (is_numeric($row['start_kemitraan'])) {
                    $start_kemitraan = Carbon::instance(Date::excelToDateTimeObject($row['start_kemitraan']))->format('Y-m-d H:i:s');
                } else {
                    $start_kemitraan = Carbon::parse($row['start_kemitraan'])->format('Y-m-d H:i:s');
                }
            }

            // 2. Konversi Tanggal End Kemitraan
            if (!empty($row['end_kemitraan'])) {
                if (is_numeric($row['end_kemitraan'])) {
                    $end_kemitraan = Carbon::instance(Date::excelToDateTimeObject($row['end_kemitraan']))->format('Y-m-d H:i:s');
                } else {
                    $end_kemitraan = Carbon::parse($row['end_kemitraan'])->format('Y-m-d H:i:s');
                }
            }

            // 3. Simpan Data Master Bank (Gunakan key sesuai heading excel baru)
            $bank = Bank::create([
                "nama"                   => $row['nama_bank'],
                "nama_pimpinan_sekarang" => $row['pimpinan_sekarang'],
                "start_kemitraan"        => $start_kemitraan,
                "end_kemitraan"          => $end_kemitraan,
                "is_active"              => 1
            ]);

            // 4. Simpan Relasi Kepala Legal (Pecah dengan explode jika multiple)
            if (!empty($row['kepala_legal'])) {
                $kepala_legals = explode(';', $row['kepala_legal']);
                foreach ($kepala_legals as $index => $nama) {
                    if (trim($nama) != '') {
                        $formDataKepalaLegal[] = [
                            'bank_id' => $bank->id,
                            'nama'    => trim($nama),
                            "created_at" => now(),
                            "updated_at" => now()
                        ];
                    }
                }
            }

            // 5. Simpan Relasi Staff Legal
            if (!empty($row['staff_legal'])) {
                $staff_legals = explode(';', $row['staff_legal']);
                foreach ($staff_legals as $nama) {
                    if (trim($nama) != '') {
                        $formDataBankLegal[] = [
                            'bank_id' => $bank->id,
                            'nama'    => trim($nama),
                            "created_at" => now(),
                            "updated_at" => now()
                        ];
                    }
                }
            }

            // 6. Simpan Relasi Kepala Marketing
            if (!empty($row['kepala_marketing'])) {
                $kepala_marketings = explode(';', $row['kepala_marketing']);
                foreach ($kepala_marketings as $nama) {
                    if (trim($nama) != '') {
                        $formDataKepalaMarketing[] = [
                            'bank_id' => $bank->id,
                            'nama'    => trim($nama),
                            "created_at" => now(),
                            "updated_at" => now()
                        ];
                    }
                }
            }
            // 7. Simpan Relasi Staff Marketing
            if (!empty($row['staff_marketing'])) {
                $staff_marketings = explode(';', $row['staff_marketing']);
                foreach ($staff_marketings as $nama) {
                    if (trim($nama) != '') {
                        $formDataBankMarketing[] = [
                            'bank_id' => $bank->id,
                            'nama'    => trim($nama),
                            "created_at" => now(),
                            "updated_at" => now()
                        ];
                    }
                }
            }
        }

        if (!empty($formDataKepalaLegal)) {
            BankKepalaLegal::insert($formDataKepalaLegal);
        }
        if (!empty($formDataBankLegal)) {
            BankLegal::insert($formDataBankLegal);
        }
        if (!empty($formDataKepalaMarketing)) {
            BankKepalaMarketing::insert($formDataKepalaMarketing);
        }
        if (!empty($formDataBankMarketing)) {
            BankMarketing::insert($formDataBankMarketing);
        }
    }
}
