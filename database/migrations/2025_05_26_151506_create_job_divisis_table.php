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
        Schema::create('job_divisis', function (Blueprint $table) {
            $table->id();
            $table->string("kode")->nullable();
            $table->bigInteger("user_id");
            $table->bigInteger("user_ops")->nullable();
            $table->bigInteger("divisi_yang_dituju")->nullable();
            $table->bigInteger("created_by");
            $table->bigInteger("jenis_akad")->nullable();
            $table->date("tanggal_rencana_akad")->nullable();
            $table->date("tanggal_akad")->nullable();
            $table->date("tanggal_estimasi_selesai")->nullable();
            $table->date("tanggal_estimasi_selesai_eksternal")->nullable();
            $table->date("tanggal_kirim_berkas")->nullable();
            $table->longText("tempat_akad")->nullable();
            $table->string("tipe_servis")->default("regular");
            $table->string("status")->default("Pra Akad");
            $table->longText("keterangan")->nullable();
            $table->longText("keterangan_akad")->nullable();
            $table->date("berkas_selesai_dikirim")->nullable();
            $table->integer("is_pending")->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_divisis');
    }
};
