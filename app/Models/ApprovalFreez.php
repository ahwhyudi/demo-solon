<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalFreez extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function jobDivisi()
    {
        return $this->belongsTo(
            JobDivisi::class,
            "job_divisi",
            "id"
        )->withTrashed();
    }
}
