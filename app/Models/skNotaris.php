<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class skNotaris extends Model
{
    use HasFactory;

    protected $table = 'sk_notaries';

    protected $guarded = [];

    public function perusahaan()
    {
        return $this->belongsTo(Setting::class, 'perusahaan_id');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }
}