<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuti extends Model
{
    protected $guarded = [];

    protected $appends = ["lama"];

    public function getLamaAttribute()
    {
        if ($this->start_date == null || $this->end_date == null) {
            return "0 Hari";
        }

        $start_date = Carbon::parse($this->start_date)->startOfDay(); // Mulai dari awal hari
        $end_date   = Carbon::parse($this->end_date)->startOfDay();   // Sampai awal hari terakhir

        // Pastikan tanggal berakhir tidak mendahului tanggal mulai
        if ($end_date->lt($start_date)) {
            return "Waktu tidak valid";
        }

        // Hitung selisih hari. Kita tambahkan 1 hari agar tanggal mulai dan tanggal berakhir ikut terhitung.
        $days = $start_date->diffInDays($end_date) + 1;

        return $days . ' Hari';
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
