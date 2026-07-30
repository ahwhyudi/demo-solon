<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany(StatusDetail::class);
    }

    // Fungsi untuk mendapatkan kode increment terakhir + 1
    public static function getNextKode()
    {
        $last = static::orderBy('kode', 'desc')->first();
        return $last ? $last->kode++ : "S-00001";
    }

    // Event creating untuk set kode otomatis
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kode)) {
                $model->kode = static::getNextKode();
            }
        });
    }
}
