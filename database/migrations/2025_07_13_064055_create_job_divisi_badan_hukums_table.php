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
        Schema::create('job_divisi_badan_hukums', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->bigInteger("job_divisi_id");
            $table->string("nama_dirut")->nullable();
            $table->string("nama_pt")->nullable();
            $table->string("npwp")->nullable();
            $table->string("nib")->nullable();
            $table->string("phone")->nullable();
            $table->longText("alamat")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_divisi_badan_hukums');
    }
};
