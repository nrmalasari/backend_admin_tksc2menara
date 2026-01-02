<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rincian_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rincian_pembayaran');
            $table->decimal('total_harga', 15, 2);
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['formulir', 'uang_bangunan', 'seragam', 'buku', 'lainnya'])->default('lainnya');
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rincian_pembayarans');
    }
};