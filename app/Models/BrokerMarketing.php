<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrokerMarketing extends Model
{
    protected $guarded = [];
    public function broker(){
        return $this->belongsTo(Broker::class, 'broker_id');
    }
}
