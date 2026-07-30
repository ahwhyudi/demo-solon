<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembatalanItemDetail extends Model
{
    public function pekerjaan()
    {
        return $this->belongsTo(JobDivisiFormOrder::class, "job_form_order_id", "id");
    }

    public function pembatalanItem()
    {
        return $this->belongsTo(PembatalanItem::class, "pembatalan_item_id", "id");
    }
}
