<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDivisiBadanHukum extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function files()
    {
        return $this->morphMany(JobDivisiFile::class, 'fileable');
    }
}
