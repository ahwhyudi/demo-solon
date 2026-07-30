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
        Schema::create('setting_step_ops', function (Blueprint $table) {
            $table->id();
            $table->integer("urutan");
            $table->string("nama_step")->nullable();
            $table->boolean("data_objek")->nullable();
            $table->boolean("penugasan_staff")->nullable();
            $table->boolean("konfirmasi")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_step_ops');
    }
};
