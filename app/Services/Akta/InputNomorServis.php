<?php

namespace App\Services\Akta;

use App\Models\NomorPpat;
use Carbon\Carbon;
use Exception;

class InputNomorServis
{
    public function execute(string $kategori, string $tanggal_nomor): int|string
    {

        try {
            $nomor = 0;
            $tanggalNomor = Carbon::parse($tanggal_nomor);
            $isBackDate = $tanggalNomor->copy()->startOfDay()->isBefore(Carbon::now()->startOfDay());

            $getCancelNomor = NomorPpat::query()
                ->whereDate("tanggal", "=",  $tanggalNomor)
                // ->orderBy("nomor", "desc")
                ->orderByRaw('CAST(nomor AS UNSIGNED) DESC')
                ->where("rekanan", 0)
                ->with("formOrder")
                ->where("kategori", $kategori)
                ->whereHas("formOrder", function ($query) use ($kategori) {
                    return $query->where("status", "Dibatalkan")
                        ->where("kategori", $kategori);
                })
                ->first();

            $month = $tanggalNomor->month;
            $year = $tanggalNomor->year;

            if ($kategori === "notaris" || $kategori === "wasiat") {
                $getLastNomorMonth = NomorPpat::query()
                    ->whereMonth("tanggal", $month)
                    ->whereYear("tanggal", $year)
                    ->where("kategori", $kategori)
                    ->where("rekanan", 0)
                    // ->orderBy("nomor", "desc")
                    ->orderByRaw('CAST(nomor AS UNSIGNED) DESC')
                    ->first();


                if (!$getLastNomorMonth) {
                    $nomor = 1;
                } elseif ($getCancelNomor) {
                    $nomor = $getCancelNomor->nomor;
                } else {
                    $getLastNomor = NomorPpat::query()
                        ->whereDate("tanggal", $tanggalNomor)
                        ->where("kategori", $kategori)
                        // ->orderBy("nomor", "desc")
                        ->orderByRaw('CAST(nomor AS UNSIGNED) DESC')
                        ->where("rekanan", 0)
                        ->first()?->nomor ?? 0;



                    // dd($getLastNomor, $isBackDate, $nomor, $getLastNomorMonth);
                    if (!ctype_digit((string) $getLastNomor) && $isBackDate && $getLastNomor > 0) {
                        $getLastNomor++;
                        $nomor = $getLastNomor;
                    } else {
                        if ($getLastNomor > 0 && $isBackDate) {
                            $nomor = $getLastNomor . "A";
                        } else {
                            $nomor = $getLastNomorMonth->nomor + 1;
                        }
                    }
                }
            } elseif (in_array($kategori, ['waarmerking', 'legalisasi', 'surat-keluar'])) {

                $getLastNomorAll = NomorPpat::query()
                    ->where("rekanan", 0)
                    ->where("kategori", $kategori)
                    // ->orderBy("nomor", "desc")
                    ->orderByRaw('CAST(nomor AS UNSIGNED) DESC')
                    ->first();

                if (!$getLastNomorAll) {
                    $nomor = 1;
                } elseif ($getCancelNomor) {
                    $nomor = $getCancelNomor->nomor;
                } else {
                    $getLastNomor = NomorPpat::query()
                        ->whereDate("tanggal", $tanggalNomor)
                        ->where("kategori", $kategori)
                        // ->orderBy("nomor", "desc")
                        ->orderByRaw('CAST(nomor AS UNSIGNED) DESC')
                        ->first()?->nomor ?? 0;

                    if (!ctype_digit((string) $getLastNomor) && $isBackDate && $getLastNomor > 0) {
                        $getLastNomor++;
                        $nomor = $getLastNomor;
                    } else {
                        if ($getLastNomor > 0 && $isBackDate) {
                            $nomor = $getLastNomor . "A";
                        } else {
                            $nomor = $getLastNomorAll->nomor + 1;
                        }
                    }
                }
                if ($nomor == 0) {
                    $nomor = 1;
                }
            } else {
                // LOGIC TAHUNAN (yang sekarang)

                $getLastNomoryears = NomorPpat::query()
                    // ->orderBy("nomor", "desc")
                    ->orderByRaw('CAST(nomor AS UNSIGNED) DESC')
                    ->where("rekanan", 0)
                    ->where("kategori", $kategori)
                    ->whereYear("tanggal", $year)
                    ->first();

                if (!$getLastNomoryears) {
                    $nomor = 1;
                } elseif ($getCancelNomor) {
                    $nomor = $getCancelNomor->nomor;
                } else {
                    $getLastNomor = NomorPpat::query()
                        ->whereDate("tanggal", $tanggalNomor)
                        ->where("kategori", $kategori)
                        // ->orderBy("nomor", "desc")
                        ->orderByRaw('CAST(nomor AS UNSIGNED) DESC')
                        ->first()?->nomor ?? 0;

                    // dd($getLastNomor, $isBackDate, $nomor, $getLastNomoryears);
                    if (!ctype_digit((string) $getLastNomor) && $isBackDate && $getLastNomor > 0) {
                        $getLastNomor++;
                        $nomor = $getLastNomor;
                    } else {
                        if ($getLastNomor > 0 && $isBackDate) {
                            $nomor = $getLastNomor . "A";
                        } else {
                            $nomor = $getLastNomoryears->nomor + 1;
                        }
                    }
                }
            }

            return $nomor;
        } catch (Exception $th) {
            throw new Exception($th);
        }
    }
}
