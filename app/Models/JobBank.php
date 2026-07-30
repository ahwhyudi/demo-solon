<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobBank extends Model
{
    protected $guarded = [];

    public function bank()
    {
        return $this->belongsTo(Bank::class, "bank_id", "id");
    }

    public function headLegalBank()
    {
        return $this->belongsTo(BankKepalaLegal::class, "head_legal", "id");
    }
    public function legalBank()
    {
        return $this->belongsTo(BankLegal::class, "legal", "id");
    }
    public function headMarketing()
    {
        return $this->belongsTo(BankKepalaMarketing::class, "head_marketing", "id");
    }
    public function marketing()
    {
        return $this->belongsTo(BankMarketing::class, "marketing", "id");
    }
    public function files()
    {
        return $this->morphMany(JobDivisiFile::class, 'fileable');
    }
}
