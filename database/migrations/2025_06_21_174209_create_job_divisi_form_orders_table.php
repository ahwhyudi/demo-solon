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
        Schema::create('job_divisi_form_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("job_divisi_id");
            $table->bigInteger("pekerjaan_id")->nullable();
            $table->bigInteger("objek_id")->nullable();
            $table->bigInteger("created_by")->default(0);
            $table->string('nama');
            $table->decimal('harga_modal', 10, 2);
            $table->decimal('harga_proses', 10, 2);
            $table->decimal('harga_jual', 10, 2);
            $table->decimal("diskon", 20, 2)->default(0);
            $table->bigInteger("lama_proses")->default(1);
            $table->string('status')->default("approved");
            $table->string('kategori');
            $table->boolean("masuk_invoice")->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_divisi_form_orders');
    }
};
