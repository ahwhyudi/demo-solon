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
        Schema::create('master_data_form_orders', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis_data');
            $table->integer('sla_internal')->default(1);
            $table->integer('sla_eksternal')->default(1);
            $table->string('data_pendukung')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_data_form_orders');
    }
};
