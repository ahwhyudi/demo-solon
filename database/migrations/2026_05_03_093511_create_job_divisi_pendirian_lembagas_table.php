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
        Schema::create('job_divisi_pendirian_lembagas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_id");
            $table->string("nama_lembaga");
            $table->string("bidang_usaha");
            $table->decimal("modal_dasar", 25, 2);
            $table->decimal("modal_setor", 25, 2);
            $table->longText("pemegang_saham");
            $table->longText("jajaran_direksi")->nullable();
            $table->longText("jajaran_komisaris")->nullable();
            $table->longText("alamat");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_divisi_pendirian_lembagas');
    }
};