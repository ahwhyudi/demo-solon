<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatans = Kecamatan::all();
        foreach ($kecamatans as $kecamatan) {
            $desas = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$kecamatan->id_kecamatan}.json")->json();
            foreach ($desas as $desa) {
                Desa::create([
                    'name' => $desa['name'],
                    'id_desa' => $desa['id'],
                    'kode_kecamatan' => $kecamatan['id_kecamatan']
                ]);
            }
        }
    }
}
