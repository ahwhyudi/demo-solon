<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobDivisiFinance extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, "invoice_id", "id");
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function jobDivisi()
    {
        return $this->belongsTo(JobDivisi::class, "job_divisi_id", "id");
    }

    public function formOrder()
    {
        return $this->belongsTo(JobDivisiFormOrder::class, "job_divisi_form_order_id", "id");
    }

    public function pnbp()
    {
        return $this->hasOne(
            Pnbp::class,
            'job_divisi_form_order_id',
            'job_divisi_form_order_id'
        );
    }
}
