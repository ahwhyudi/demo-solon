<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_version_details', function (Blueprint $table) {

            $table->id();

            $table->foreignId('invoice_version_id');

            $table->bigInteger('job_divisi_form_order_id');

            $table->string('nama');

            $table->decimal('harga',20,2);

            $table->decimal('diskon',20,2)->default(0);

            $table->decimal('total',20,2);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_version_details');
    }
};