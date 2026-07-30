<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaPekerjaan extends Model
{
    protected $guarded =[];
    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, "pekerjaan_id", "id");
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, "kota_id", "id");
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, "provinsi_id", "id");
    }
}
