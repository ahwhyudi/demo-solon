<?php

namespace Database\Seeders;

use App\Models\Bank;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\DivisiSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::create([
        //     'name' => 'super admin',
        //     'email' => 'superadmin@supernotaris.com',
        //     "password" => Hash::make("TeknikHijau1"),
        // ]);

        $this->call(RolePermissionSeeder::class);
        // $this->call([
        //     ProvinsiSeeder::class,
        //     KotaSeeder::class,
        //     KecamatanSeeder::class,
        //     DesaSeeder::class
        // ]);
        // $this->call(DivisiSeeder::class);
        // $banks = [
        //     'BNI',
        //     'BRI',
        //     'BSI',
        //     'BPR',
        //     'CIMB',
        //     'Mandiri',
        //     'BTN',
        //     'Arta Graha'
        // ];

        // foreach ($banks as $bank) {
        //     Bank::firstOrCreate(
        //         ['nama' => $bank]
        //     );
        // }

    }
}
