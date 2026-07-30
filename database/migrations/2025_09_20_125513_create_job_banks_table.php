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
        Schema::create('job_banks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_id");
            $table->bigInteger("bank_id");
            $table->string("nama_bank");
            $table->string("pimpinan");
            $table->string("head_legal")->nullable();
            $table->string("legal")->nullable();
            $table->string("head_marketing")->nullable();
            $table->string("marketing")->nullable();
            $table->string("file")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_banks');
    }
};
