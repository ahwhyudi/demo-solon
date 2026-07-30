<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kota extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'kode_provinsi', 'kode');
    }

    public function skNotaries()
    {
        return $this->hasMany(skNotaris::class, 'kota_id');
    }

    public function skPpats()
    {
        return $this->hasMany(skPpat::class, 'kota_id');
    }
}
