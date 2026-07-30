<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pnbp extends Model
{
    protected $guarded = [];

    public function formOrder()
    {
        return $this->belongsTo(JobDivisiFormOrder::class, "job_divisi_form_order_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
