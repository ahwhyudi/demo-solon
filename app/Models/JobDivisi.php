<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDivisi extends Model
{
    use SoftDeletes;
    protected $casts = [
        'tanggal_akad' => 'datetime',
        'tanggal_estimasi_selesai' => 'datetime',
        'tanggal_estimasi_selesai_eksternal' => 'datetime',
    ];
    protected $guarded = [];

    protected $hidden = ["id"];

    public function invoice()
    {
        return $this->hasMany(Invoice::class, "job_divisi_id", "id");
    }

    public function pembatalanItem()
    {
        return $this->hasMany(PembatalanItem::class, "job_divisi_id", "id");
    }

    public function freeze()
    {
        return $this->hasOne(ApprovalFreez::class, "job_divisi", "id");
    }

    public function badanHukum()
{
    return $this->hasMany(JobDivisiBadanHukum::class, 'job_divisi_id');
}

    public function developer()
    {
        return $this->hasMany(JobDeveloper::class, "job_divisi_id", "id");
    }

    public function listDebitur()
    {
        return $this->hasMany(Debitur::class, "job_divisi_id", "id");
    }

    public function listBadanUsahaDebitur()
    {
        return $this->hasMany(JobDivisiBadanUsahaDebitur::class, "job_divisi_id", "id");
    }

    public function listBadanHukum()
    {
        return $this->hasMany(JobDivisiBadanHukum::class, "job_divisi_id", "id");
    }

    public function pendirianLembaga()
    {
        return $this->hasMany(JobDivisiPendirianLembaga::class, "job_divisi_id", "id");
    }

    public function listDeveloper()
    {
        return $this->hasMany(JobDeveloper::class, "job_divisi_id", "id");
    }

    public function listBadanUsahaPenjual()
    {
        return $this->hasMany(JobDivisiBadanUsahaPenjual::class, "job_divisi_id", "id");
    }

    public function listBadanUsahaPembeli()
    {
        return $this->hasMany(JobDivisiBadanUsahaPembeli::class, "job_divisi_id", "id");
    }

    public function listPembeli()
    {
        return $this->hasMany(Pembeli::class, "job_divisi_id", "id");
    }

    public function listPenjual()
    {
        return $this->hasMany(Penjual::class, "job_divisi_id", "id");
    }

    public function listBroker()
    {
        return $this->hasMany(JobDivisiBroker::class, "job_divisi_id", "id");
    }

    public function listBank()
    {
        return $this->hasMany(JobBank::class, "job_divisi_id", "id");
    }

    public function penjual()
    {
        return $this->hasMany(Penjual::class, "job_divisi_id", "id");
    }
    public function pembeli()
    {
        return $this->hasMany(Pembeli::class, "job_divisi_id", "id");
    }


    public function pembatalan()
    {
        return $this->hasOne(BatalJobDivisi::class, "job_divisi_id", "id");
    }

    public function penambahanItem()
    {
        return $this->hasMany(PenambahanItemJobDivisi::class, "job_divisi_id", "id");
    }

    public function objek()
    {
        return $this->hasMany(JobDivisiObjek::class, "job_divisi_id", "id");
    }

    public function debitur()
    {
        return $this->hasMany(Debitur::class, "job_divisi_id", "id");
    }

    public function jenisAkad()
    {
        return $this->belongsTo(MasterDataFormOrder::class, "jenis_akad", "id")->withTrashed();
    }


    public function formOrder()
    {
        return $this->hasMany(JobDivisiFormOrder::class, "job_divisi_id", "id");
    }

    public function finance()
    {
        return $this->hasMany(JobDivisiFinance::class, "job_divisi_id", "id");
    }

    public function fileJob()
    {
        return $this->hasMany(FileJobDivisi::class, "job_divisi_id", "id");
    }

    public function divisiYangDituju()
    {
        return $this->belongsTo(Divisi::class, "divisi_yang_dituju", "id");
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, "bank_id", "id");
    }



    public function pembuat()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function userOps()
    {
        return $this->belongsTo(User::class, "user_operasional", "id");
    }

    public function perubahanHarga()
    {
        return $this->hasOne(PerubahanHargaJualFo::class, "job_divisi_id", "id")->orderBy("id", "desc");
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->status === "Selesai") {
                $model->is_pending = 0;
            }
        });
    }

    public function pnbps()
    {
        return $this->hasManyThrough(
            \App\Models\Pnbp::class,
            \App\Models\JobDivisiFormOrder::class,
            'job_divisi_id', // FK di job_divisi_form_orders
            'job_divisi_form_order_id', // FK di pnbps
            'id', // PK job_divisis
            'id' // PK job_divisi_form_orders
        );
    }

    public function perwakilanAkad()
    {
        return $this->belongsTo(User::class, 'user_perwakilan_akad');
    }
    public function perwakilanAkad2()
    {
        return $this->belongsTo(User::class, 'user_perwakilan_akad');
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'user_ops');
    }

    
}
