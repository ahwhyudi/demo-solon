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
        Schema::create('bank_kontraks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("bank_id");
            $table->string("nama_pimpinan");
            $table->date("start_date");
            $table->date("end_date");
            $table->string("status")->default("aktif");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_kontraks');
    }
};
