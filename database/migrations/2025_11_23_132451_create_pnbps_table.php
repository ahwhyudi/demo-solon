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
        Schema::create('pnbps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_form_order_id");
            $table->bigInteger("created_by");
            $table->bigInteger("user_id")->nullable();
            $table->decimal("nominal", 20, 2)->nullable();
            $table->string('file')->nullable();
            $table->string('status')->default("Menunggu Pembayaran");
            $table->dateTime("tanggal")->nullable();
            $table->longText('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pnbps');
    }
};
