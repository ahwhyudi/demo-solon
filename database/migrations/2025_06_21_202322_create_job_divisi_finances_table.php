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
        Schema::create('job_divisi_finances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("created_by");
            $table->bigInteger("user_id")->nullable();
            $table->bigInteger("job_divisi_id");
            $table->bigInteger("job_divisi_form_order_id")->nullable();
            $table->decimal("total", 20, 2);
            $table->decimal("limit_ops", 20, 2)->nullable();
            $table->dateTime("tanggal");
            $table->string("metode_pembayaran")->nullable();
            $table->string("status")->default("pending");
            $table->string("keterangan")->nullable();
            $table->string("tipe")->default("in");
            $table->string("peruntukan")->nullable();
            $table->string("atas_nama")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_divisi_finances');
    }
};
