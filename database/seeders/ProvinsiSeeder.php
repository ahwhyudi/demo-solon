<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinsis = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")->json();
        $filtered = collect($provinsis)->whereIn('id', ['31', '36', '32']);
        foreach ($filtered as $provinsi) {
            Provinsi::create([
                'name' => $provinsi['name'],
                'kode' => $provinsi['id']
            ]);
            sleep(1);
        }
    }
}
