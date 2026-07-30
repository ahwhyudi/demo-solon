<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Desa extends Model
{
    use SoftDeletes;
    protected $guarded=[];
    public function kecamatan(){
        return $this->belongsTo(Kecamatan::class, 'kode_kecamatan', 'id_kecamatan');
    }
   
    
}
