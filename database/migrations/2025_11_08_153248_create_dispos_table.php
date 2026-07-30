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
        Schema::create('dispos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_form_order_id");
            $table->bigInteger("created_by");
            $table->bigInteger("user_id")->nullable();
            $table->date("start_date");
            $table->date("end_date")->nullable();
            $table->longText("keterangan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispos');
    }
};
