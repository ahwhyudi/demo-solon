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
        Schema::create('job_divisi_objeks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_id");
            $table->bigInteger("desa_id");
            $table->string("jenis_sertifikat")->nullable();
            $table->string("nama_pemilik")->nullable();
            $table->string("no_sertifikat")->nullable();
            $table->string("luas_tanah")->nullable();
            $table->string("nilai_ht")->nullable();
            $table->string("nilai_transaksi")->nullable();
            $table->string("nilai_plafond")->nullable();
            $table->string("file")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_divisi_objeks');
    }
};
