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
        Schema::create('penambahan_item_job_divisis', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable();
            $table->bigInteger('job_divisi_id');
            $table->bigInteger('created_by');
            $table->bigInteger('approved_by')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('status')->default("menunggu approval");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penambahan_item_job_divisis');
    }
};
