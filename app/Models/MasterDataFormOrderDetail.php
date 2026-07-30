<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDataFormOrderDetail extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function pekerjaan()
    {
        return  $this->belongsTo(Pekerjaan::class, "pekerjaan_id", "id");
    }
}
