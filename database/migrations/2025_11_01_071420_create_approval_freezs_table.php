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
        Schema::create('approval_freezs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi");
            $table->bigInteger("created_by");
            $table->bigInteger("approval_1")->nullable();
            $table->bigInteger("approval_2")->nullable();
            $table->bigInteger("penambahan_sla")->nullable();
            $table->string("status")->default("menunggu persetujuan");
            $table->longText("keterangan")->nullable();
            $table->dateTime("start_date")->nullable();
            $table->dateTime("end_date")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_freezs');
    }
};
