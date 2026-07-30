<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PembatalanItem extends Model
{
    protected $guarded = [];

    public function jobDivisi()
    {
        return $this->belongsTo(JobDivisi::class, "job_divisi_id", "id");
    }

    public function detail()
    {
        return $this->hasMany(PembatalanItemDetail::class, "pembatalan_item_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function userApprove()
    {
        return $this->belongsTo(User::class, "approved_by", "id");
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $month = Carbon::now()->month;
            $year = Carbon::now()->year;

            $last_item = DB::table("pembatalan_items")
                ->whereMonth("created_at", Carbon::now()->month)
                ->whereYear("created_at", Carbon::now()->year)
                ->orderBy("id", "desc")
                ->first();

            $kode = $last_item->kode ?? "PEMBATALAN/" . $year  . $month . "/0000";

            $model->kode = ++$kode;
        });
    }
}
