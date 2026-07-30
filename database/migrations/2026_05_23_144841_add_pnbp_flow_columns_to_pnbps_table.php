<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pnbps', function (Blueprint $table) {

            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('va_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('pnbps', function (Blueprint $table) {

            $table->dropColumn([
                'assigned_at',
                'va_at',
                'paid_at',
                'approved_by'
            ]);

        });
    }
};
