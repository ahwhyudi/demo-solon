<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusJobOps extends Model
{
    protected $guarded = [];

    public function createdBy()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function nextUser()
    {
        return $this->belongsTo(User::class, "next_user", "id");
    }
    public function statusJobOps()
    {
        return $this->hasMany(StatusJobOps::class)
            ->orderBy('id', 'asc');
    }
}
