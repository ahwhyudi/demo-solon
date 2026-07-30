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
        Schema::create('job_divisi_brokers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_divisi_id');
            $table->string('nama_pt');
            $table->string('nama_pimpinan');
            $table->string('nama_agent');
            $table->string('nomor_agent');
            $table->string('file')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_divisi_brokers');
    }
};
