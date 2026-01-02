<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id('id_guru');
            
            // Data pribadi guru
            $table->string('nama_lengkap');
            $table->string('jabatan');
            $table->bigInteger('nuptk')->unique()->nullable();
            $table->string('foto_path')->nullable();
            $table->string('guru_kelas')->nullable(); // Untuk display di tabel
            
            // Relasi ke kelas
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->foreign('kelas_id')
                  ->references('id_kelas')
                  ->on('kelas')
                  ->onDelete('set null');
            
            // Status guru
            $table->enum('status', ['aktif', 'nonaktif', 'pensiun'])->default('aktif');
            
            // Informasi kontak
            $table->string('email')->unique()->nullable();
            $table->string('telepon')->nullable();
            $table->string('alamat')->nullable();
            
            // Informasi pendidikan
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('bidang_studi')->nullable();
            
            // Informasi kepegawaian
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gurus');
    }
};