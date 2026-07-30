<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provinsi extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function kota()
    {
        return $this->hasMany(Kota::class, "kode_provinsi", "kode");
    }
}
