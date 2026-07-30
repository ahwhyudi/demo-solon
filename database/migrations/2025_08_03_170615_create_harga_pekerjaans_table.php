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
        Schema::create('harga_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pekerjaan_id');
            $table->bigInteger('provinsi_id')->nullable();
            $table->bigInteger('kota_id')->nullable();
            $table->decimal('harga_limit', 20, 2)->default(0);
            $table->decimal('harga_jual', 20, 2)->default(0);
            $table->decimal('harga_proses', 20, 2)->default(0);
            $table->bigInteger('lama_proses')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_pekerjaans');
    }
};
