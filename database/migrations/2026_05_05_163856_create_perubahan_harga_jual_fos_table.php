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
        Schema::create('perubahan_harga_jual_fos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("created_by");
            $table->bigInteger("user_id")->nullable();
            $table->bigInteger("job_divisi_id");
            $table->string("status")->default("menunggu persetujuan");
            $table->longText("keterangan")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perubahan_harga_jual_fos');
    }
};
