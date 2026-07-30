<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPending extends Model
{
    protected $guarded = [];

    public function jobDivisi()
    {
        return $this->belongsTo(JobDivisi::class, "job_divisi_id", "id")->withTrashed();
    }
}
