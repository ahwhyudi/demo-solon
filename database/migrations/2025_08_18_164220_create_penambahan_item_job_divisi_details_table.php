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
        Schema::create('penambahan_item_job_divisi_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('penambahan_item_job_divisi_id');
            $table->bigInteger('pekerjaan_id');
            $table->decimal('harga_jual', 20, 2);
            $table->decimal('harga_modal', 20, 2);
            $table->decimal('harga_proses', 20, 2);
            $table->boolean('masuk_invoice')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penambahan_item_job_divisi_details');
    }
};
