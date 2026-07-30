<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function jobDivisi()
    {
        return $this->belongsTo(JobDivisi::class);
    }
    public function formOrder()
    {
        return $this->belongsTo(
            JobDivisiFormOrder::class,
            'job_divisi_form_order_id'
        );
    }
}
