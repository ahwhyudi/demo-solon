<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileJobDivisi extends Model
{
    protected $guarded = [];
    protected $appends = ['source'];

    public function getSourceAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
