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
        Schema::table('job_divisi_objeks', function (Blueprint $table) {
            $table->longText("alamat")->nullable()->after("file");
            $table->string("no_sppt")->nullable()->after("file");
            $table->string("njop")->nullable()->after("file");
            $table->string("pbb")->nullable()->after("file");
            $table->string("nib")->nullable()->after("file");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_divisi_objeks', function (Blueprint $table) {
            $table->dropColumn("alamat");
            $table->dropColumn("no_sppt");
            $table->dropColumn("njop");
            $table->dropColumn("pbb");
            $table->dropColumn("nib");
        });
    }
};