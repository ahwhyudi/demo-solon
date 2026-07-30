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
        Schema::create('pembatalan_items', function (Blueprint $table) {
            $table->id();
            $table->string("kode");
            $table->bigInteger("job_divisi_id");
            $table->bigInteger("created_by");
            $table->bigInteger("approved_by")->nullable();
            $table->string("status")->default("menunggu persetujuan");
            $table->longText("keterangan")->nullable();
            $table->longText("keterangan_tolak")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembatalan_items');
    }
};
