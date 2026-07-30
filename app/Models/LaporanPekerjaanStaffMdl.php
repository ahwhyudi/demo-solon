<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPekerjaanStaffMdl extends Model
{
    protected $guarded = [];

    public function formOrder()
    {
        return $this->belongsTo(JobDivisiFormOrder::class, "job_divisi_form_order_id", "id");
    }


    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, "form_order_id", "id");
    }


    public function namaStaff()
    {
        return  $this->belongsTo(User::class, "created_by", "id");
    }

    


}
