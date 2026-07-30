<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDivisiObjek extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function desa()
    {
        return $this->belongsTo(Desa::class, "desa_id", "id");
    }

    public function files()
    {
        return $this->morphMany(JobDivisiFile::class, 'fileable');
    }
}
