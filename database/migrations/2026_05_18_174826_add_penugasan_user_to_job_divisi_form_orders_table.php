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
        Schema::table('job_divisi_form_orders', function (Blueprint $table) {
            $table->bigInteger("penugasan_user")->nullable()->after("created_by");   // ini penugasan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_divisi_form_orders', function (Blueprint $table) {
            $table->dropColumn("penugasan_user");
        });
    }
};
