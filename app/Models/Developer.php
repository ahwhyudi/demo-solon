<?php

namespace App\Models;

use App\Models\DeveloperLegal;
use App\Models\BrokerMarketing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Developer extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function marketing(){
        return $this->hasMany(DeveloperMarketing::class, 'developer_id');
    }

    public function legal(){
        return $this->hasMany(DeveloperLegal::class, 'developer_id');
    }

    public static function booted(){
        static::deleting(function($developer){
            $developer->marketing()->delete();
            $developer->legal()->delete();
        });
    }
}
