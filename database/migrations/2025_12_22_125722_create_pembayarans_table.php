<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regist_pendaftar_id')
                  ->constrained('regist_pendaftars')
                  ->onDelete('cascade');
            $table->date('tanggal_pembayaran');
            $table->string('nama');
            $table->string('nama_bank')->nullable();
            $table->text('no_rek')->nullable(); // akan dienkripsi AES-256-CBC
            $table->string('metode_pembayaran')->nullable();
            $table->decimal('jumlah_pembayaran', 15, 2)->default(0);
            $table->string('bukti_pembayaran')->nullable();
            $table->string('catatan_admin')->nullable(); // Hapus AFTER clause
            $table->enum('status_pembayaran', ['menunggu', 'diproses', 'diverifikasi', 'ditolak'])
                  ->default('menunggu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
};