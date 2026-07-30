<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispo extends Model
{
    protected $guarded = [];

    public function jobDivisiFormOrder()
    {
        return $this->belongsTo(JobDivisiFormOrder::class, "job_divisi_form_order_id", "id");
    }

    public function dibuat()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
}
