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
        Schema::create('form_order_luar_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_id");
            $table->string("proses");
            $table->string("harga_proses")->default(0);
            $table->string("harga_percepatan_proses")->default(0);
            $table->longText("keterangan")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_order_luar_invoices');
    }
};
