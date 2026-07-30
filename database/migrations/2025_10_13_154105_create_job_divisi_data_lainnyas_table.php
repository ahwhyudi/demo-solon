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
        Schema::create('job_divisi_data_lainnyas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_id");
            $table->string("nama_badan_usaha")->nullable();
            $table->string("nama_perwakilan")->nullable();
            $table->string("no_telepon")->nullable();
            $table->string("email")->nullable();
            $table->string("file")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_divisi_data_lainnyas');
    }
};
