<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nomor_ppats', function (Blueprint $table) {
            $table->string("file_notaris_pengambil")->nullable()->after("rekanan");
            $table->longText("nama_debitur_notaris_pengambil")->nullable()->after("rekanan");
            $table->longText("objek_notaris_pengambil")->nullable()->after("rekanan");
            $table->string("notaris_pengambil")->nullable()->after("rekanan");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomor_ppats', function (Blueprint $table) {
            $table->dropColumn("file_notaris_pengambil");
            $table->dropColumn("notaris_pengambil");
            $table->dropColumn("nama_debitur_notaris_pengambil");
            $table->dropColumn("objek_notaris_pengambil");
        });
    }
};
