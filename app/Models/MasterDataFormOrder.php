<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDataFormOrder extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(MasterDataFormOrderDetail::class, "master_data_form_order_id", "id");
    }
}
