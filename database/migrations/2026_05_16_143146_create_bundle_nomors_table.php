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
        Schema::create('bundle_nomors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->string("nomor");
            $table->string("kategori");
            $table->integer("tahun");
            $table->integer("bulan")->nullable();
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
        Schema::dropIfExists('bundle_nomors');
    }
};
