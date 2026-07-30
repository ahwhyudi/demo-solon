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
        Schema::table('status_job_ops', function (Blueprint $table) {
            $table->bigInteger("next_user")->nullable()->after("user_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_job_ops', function (Blueprint $table) {
            $table->dropColumn("next_user");
        });
    }
};
