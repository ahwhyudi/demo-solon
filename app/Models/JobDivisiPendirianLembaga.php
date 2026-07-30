<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobDivisiPendirianLembaga extends Model
{
    protected $guarded = [];

    public function jobDivisi()
    {
        return $this->belongsTo(JobDivisi::class, 'job_divisi_id');
    }
}