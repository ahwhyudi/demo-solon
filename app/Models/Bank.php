<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes, HasFactory;
    protected $guarded = [];

    public function kepalaLegal()
    {
        return $this->hasMany(BankKepalaLegal::class, "bank_id", "id");
    }
    public function legal()
    {
        return $this->hasMany(BankLegal::class, "bank_id", "id");
    }
    public function kepalaMarketing()
    {
        return $this->hasMany(BankKepalaMarketing::class, "bank_id", "id");
    }
    public function marketing()
    {
        return $this->hasMany(BankMarketing::class, "bank_id", "id");
    }
    public function files()
    {
        return $this->morphMany(JobDivisiFile::class, 'fileable');
    }
}
