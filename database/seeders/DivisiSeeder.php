<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisis =[
            'MIKRO',
            'BUMN',
            'UMUM',
            'SWASTA',
            'UMUM PT',
            'RETAIL',
            'OPRASIONAL',
            'UNIT',
            'ADMIN'
        ];

        foreach($divisis as $divisi){
            Divisi::firstOrCreate(['nama'=>$divisi]);
        }
    }
}
