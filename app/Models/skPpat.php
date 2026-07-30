<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class skPpat extends Model
{
    use HasFactory;
    protected $table = 'sk_ppat';
    protected $guarded = [];
    public function perusahaan()
    {
        return $this->belongsTo(Setting::class);
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }
}
