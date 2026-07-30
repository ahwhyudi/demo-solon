<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    protected $table = 'brokers';
    protected $guarded = [];

    public function marketing(){
        return $this->hasMany(BrokerMarketing::class, 'broker_id');
    }

    public static function booted(){
        static::deleting(function($broker){
            $broker->marketing()->delete();
        });
    }
}
