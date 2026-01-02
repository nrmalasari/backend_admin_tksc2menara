<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke siswa pendaftar yang diverifikasi
            $table->foreignId('siswa_pendaftar_id')
                  ->nullable()
                  ->constrained('siswa_pendaftars')
                  ->onDelete('set null');
            
            // Relasi ke tahun ajaran
            $table->foreignId('tahun_ajaran_id')
                  ->constrained('tahun_ajarans')
                  ->onDelete('cascade');
            
            // Relasi ke kelas - spesifikasikan kolom yang benar
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->foreign('kelas_id')
                  ->references('id_kelas')
                  ->on('kelas')
                  ->onDelete('set null');
            
            // Data siswa
            $table->string('nama_lengkap');
            $table->string('nis')->unique()->nullable();
            $table->string('nsin')->unique()->nullable();
            
            // Status: aktif, pindah, lulus
            $table->enum('status', ['aktif', 'pindah', 'lulus'])->default('aktif');
            
            // Foto siswa (jika ada)
            $table->string('foto_path')->nullable();
            
            // Formulir PDF (dari siswa pendaftar atau upload manual)
            $table->string('formulir_path')->nullable();
            
            // Data tambahan (opsional)
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->text('alasan_keluar')->nullable();
            $table->string('asal_sekolah')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswas');
    }
};