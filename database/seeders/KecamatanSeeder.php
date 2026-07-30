<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kota;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kotas = Kota::all();
        foreach ($kotas as $kota) {
            $kecamatans = Http::retry(3, 1000)->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$kota->id_kota}.json")->json();
            foreach ($kecamatans as $kecamatan) {
                Kecamatan::create([
                    'name' => $kecamatan['name'],
                    'id_kecamatan' => $kecamatan['id'],
                    'kode_kota' => $kota['id_kota']
                ]);
                sleep(1);
            }
        }
    }
}
