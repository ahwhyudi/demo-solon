<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormOrderLuarInvoice extends Model
{
    protected $guarded = [];

    protected $hidden = [
        "id",
        "job_divisi_id"
    ];

    protected $appends = [
        "tanggal",
        "hash_id"
    ];

    public function jobDivisi()
    {
        return $this->belongsTo(JobDivisi::class, "job_divisi_id", "id");
    }

    public function getTanggalAttribute()
    {
        return $this->created_at->format("d M Y H:i");
    }

    public function getHashIdAttribute()
    {
        return encodeHashIds($this->id);
    }
}
