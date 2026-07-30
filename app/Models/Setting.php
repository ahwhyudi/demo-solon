<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function skNotaris()
    {
        return $this->hasOne(skNotaris::class,'perusahaan_id');
    }

    public function skPpat()
    {
        return $this->hasOne(skPpat::class, 'perusahaan_id');
    }
}
