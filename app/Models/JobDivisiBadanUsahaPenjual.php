<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobDivisiBadanUsahaPenjual extends Model
{
    protected $guarded = [];

    public function files()
    {
        return $this->morphMany(JobDivisiFile::class, 'fileable');
    }
}
