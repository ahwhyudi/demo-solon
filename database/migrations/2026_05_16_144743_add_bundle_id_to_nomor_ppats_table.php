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
        Schema::table('nomor_ppats', function (Blueprint $table) {
            $table->string("kategori")->after("id")->nullable();
            $table->bigInteger("bundle_id")->after("id")->nullable();
            $table->bigInteger("form_order_id")->after("id")->nullable();
        });
        Schema::table('job_divisis', function (Blueprint $table) {
            $table->bigInteger("user_perwakilan_akad")->after("user_id")->nullable();
            $table->bigInteger("user_perwakilan_akad_2")->after("user_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomor_ppats', function (Blueprint $table) {
            $table->dropColumn("bundle_id");
            $table->dropColumn("kategori");
            $table->dropColumn("form_order_id");
        });

        Schema::table('job_divisis', function (Blueprint $table) {
            $table->dropColumn("user_perwakilan_akad");
            $table->dropColumn("user_perwakilan_akad_2");
        });
    }
};