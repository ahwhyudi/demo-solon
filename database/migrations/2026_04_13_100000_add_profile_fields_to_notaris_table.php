<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notaris', function (Blueprint $table) {
            $table->string('nama_kantor')->nullable()->after('email');
            $table->text('alamat_kantor')->nullable()->after('nama_kantor');
            $table->string('npwp')->nullable()->after('alamat_kantor');
            $table->string('nomor_sk')->nullable()->after('npwp');
            $table->string('logo')->nullable()->after('foto_ktp');
            $table->string('tanda_tangan')->nullable()->after('logo');
            $table->string('stempel')->nullable()->after('tanda_tangan');
        });
    }

    public function down(): void
    {
        Schema::table('notaris', function (Blueprint $table) {
            $table->dropColumn([
                'nama_kantor',
                'alamat_kantor',
                'npwp',
                'nomor_sk',
                'logo',
                'tanda_tangan',
                'stempel',
            ]);
        });
    }
};
