<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjual extends Model
{
    protected $guarded = [];

    public function jobDivisiBadanUsahaPenjual()
    {
        return $this->hasOne(JobDivisiBadanUsahaPenjual::class, 'penjual_id', 'id');
    }

    public function files()
    {
        return $this->morphMany(JobDivisiFile::class, 'fileable');
    }
}
