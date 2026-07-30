<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatalJobDivisi extends Model
{
    protected $guarded = [];

    public function jobDivisi()
    {
        return $this->belongsTo(JobDivisi::class, "job_divisi_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
    public function user2()
    {
        return $this->belongsTo(User::class, "approved_by", "id");
    }
}
