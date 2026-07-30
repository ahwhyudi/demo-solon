<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lembur extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = ["lama"];

    /**
     * Accessor untuk menghitung Lama Lembur dalam Jam dan Menit.
     * @return string
     */
    public function getLamaAttribute()
    {
        if ($this->start_date == null || $this->end_date == null) {
            return "0 Jam";
        }

        $start_date = Carbon::parse($this->start_date);
        $end_date   = Carbon::parse($this->end_date);

        // Pastikan tanggal berakhir tidak mendahului tanggal mulai
        if ($end_date->lt($start_date)) {
            return "Waktu tidak valid";
        }

        // Hitung selisih waktu (DateInterval object)
        $duration = $start_date->diff($end_date);

        // Ambil komponen Jam dan Menit (diff hanya mengambil jam/menit yang tersisa setelah hari penuh)
        $days    = $duration->days;
        $hours   = $duration->h;
        $minutes = $duration->i;

        // Catatan: Jika lembur melintasi hari, kita harus menambahkan total jam dari hari penuh
        if ($days > 0) {
            $hours += $days * 24; // Tambahkan jam dari hari penuh
        }

        $output = '';

        if ($hours > 0) {
            $output .= $hours . ' Jam';
        }
        if ($minutes > 0) {
            if ($hours > 0) {
                $output .= ' ';
            }
            $output .= $minutes . ' Menit';
        }

        return $output ?: "0 Jam"; // Jika hasilnya kosong (kurang dari 1 menit), kembalikan "0 Jam"
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
