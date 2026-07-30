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
        Schema::create('job_opersaionals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_id");
            $table->bigInteger("job_divisi_form_order_id");
            $table->bigInteger("ops_leader_id");
            $table->bigInteger("ops_id");
            $table->bigInteger("jumlah_buku_sertifikat")->default(0);
            $table->string("status")->nullable();
            $table->string("status_proses")->nullable();
            $table->string("objek")->nullable();
            $table->string("no_sertifikat")->nullable();
            $table->string("no_kode_akta")->nullable();
            $table->string("no_kode_berkas")->nullable();
            $table->string("tipe_proses")->nullable();
            $table->date("tanggal_penyerahan")->nullable();
            $table->date("tanggal_kelengkapan")->nullable();
            $table->date("tanggal_penarikan_uang")->nullable();
            $table->string("file_tanda_terima_bpn")->nullable();
            $table->longText("keterangan")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_opersaionals');
    }
};
