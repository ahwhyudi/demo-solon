<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerubahanHargaJualFo extends Model
{
    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany(PerubahanHargaJualFoDetail::class, "parent_id", "id");
    }
}
