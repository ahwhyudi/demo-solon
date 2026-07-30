<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeveloperLegal extends Model
{
    protected $guarded = [];

    public function developer(){
        return $this->belongsTo(Developer::class, 'developer_id');
    }
}
