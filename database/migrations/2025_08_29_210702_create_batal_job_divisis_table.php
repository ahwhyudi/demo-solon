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
        Schema::create('batal_job_divisis', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_id");
            $table->bigInteger("created_by");
            $table->bigInteger("approved_by")->nullable();
            $table->string("status")->default("menunggu persetujuan");
            $table->string("keterangan")->nullable();
            $table->string("keterangan_approved")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batal_job_divisis');
    }
};
