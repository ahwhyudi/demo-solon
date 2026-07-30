<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenambahanItemJobDivisi extends Model
{
    protected $guarded = [];

    protected $appends = ["tanggal"];

    public function getTanggalAttribute()
    {
        return $this->created_at->format("d M Y");
    }

    public function jobDivisi()
    {
        return $this->belongsTo(JobDivisi::class, "job_divisi_id", "id");
    }

    public function detail()
    {
        return $this->hasMany(PenambahanItemJobDivisiDetail::class, "penambahan_item_job_divisi_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function userApprove()
    {
        return $this->belongsTo(User::class, "approved_by", "id");
    }
}
