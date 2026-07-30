<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinsis = Provinsi::all();
        foreach ($provinsis as $provinsi) {
            $kotas = Http::retry(3, 1000)->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinsi->kode}.json")->json();
            // dd($kotas);
            foreach ($kotas as $kota) {
                Kota::create([
                    'name' => $kota['name'],
                    'id_kota' => $kota['id'],
                    'kode_provinsi' => $provinsi['kode']
                ]);
                sleep(1);
            }
        }
    }
}
