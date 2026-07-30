<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingStepOps extends Model
{
    use HasFactory;

    protected $table = 'setting_step_ops';

    protected $guarded = [];

    protected $casts = [
        'data_objek' => 'boolean',
        'penugasan_staff' => 'boolean',
        'konfirmasi' => 'boolean',
    ];
}
