<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Proses untuk tabel sk_notaries
        if (Schema::hasColumn('sk_notaries', 'file')) {
            // Jika kolom sudah ada, lakukan CHANGE
            Schema::table('sk_notaries', function (Blueprint $table) {
                $table->string('file')->nullable()->change();
            });
        } else {
            // Jika kolom belum ada, TAMBAH BARU
            Schema::table('sk_notaries', function (Blueprint $table) {
                $table->string('file')->nullable();
            });
        }

        // 2. Proses untuk tabel sk_ppat
        if (Schema::hasColumn('sk_ppat', 'file')) {
            // Jika kolom sudah ada, lakukan CHANGE
            Schema::table('sk_ppat', function (Blueprint $table) {
                $table->string('file')->nullable()->change();
            });
        } else {
            // Jika kolom belum ada, TAMBAH BARU
            Schema::table('sk_ppat', function (Blueprint $table) {
                $table->string('file')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Bagian down juga harus dicek agar tidak error saat di-rollback
        if (Schema::hasColumn('sk_notaries', 'file')) {
            Schema::table('sk_notaries', function (Blueprint $table) {
                // Kembalikan ke tidak boleh null (sesuaikan dengan struktur awalmu)
                $table->string('file')->nullable(false)->change();
            });
        }

        if (Schema::hasColumn('sk_ppat', 'file')) {
            Schema::table('sk_ppat', function (Blueprint $table) {
                $table->string('file')->nullable(false)->change();
            });
        }
    }
};
