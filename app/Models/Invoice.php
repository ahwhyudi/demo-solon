<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes; // Pastikan SoftDeletes aktif karena di migration ada $table->softDeletes()

    protected $guarded = [];

    public function jobDivisi()
    {
        return $this->belongsTo(JobDivisi::class, "job_divisi_id", "id");
    }

    public function detail()
    {
        return $this->hasMany(InvoiceDetail::class, "invoice_id", "id");
    }

    public function finance()
    {
        return $this->hasMany(JobDivisiFinance::class, "invoice_id", "id");
    }
}