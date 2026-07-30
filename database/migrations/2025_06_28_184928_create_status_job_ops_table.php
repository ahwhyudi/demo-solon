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
        Schema::create('status_job_ops', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_form_order_id");
            $table->bigInteger("created_by");
            $table->bigInteger("user_id")->nullable();
            $table->string('status')->nullable();
            $table->string('file')->nullable();
            $table->longText('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_job_ops');
    }
};
