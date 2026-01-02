<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siswa_pendaftars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regist_pendaftar_id')
                  ->constrained('regist_pendaftars')
                  ->onDelete('cascade');
            
            // Data Anak (akan dienkripsi yang sensitif)
            $table->string('nama_lengkap');
            $table->text('nik'); // dienkripsi
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('jenis_kelamin');
            $table->integer('usia');
            $table->text('alamat_jalan');
            $table->string('rt');
            $table->string('rw');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kota');
            $table->string('kode_pos');
            $table->integer('tinggi_badan');
            $table->integer('berat_badan');
            $table->integer('jumlah_saudara');
            $table->decimal('jarak_sekolah', 5, 2);
            $table->integer('waktu_tempuh');
            
            // Data Orang Tua (akan dienkripsi yang sensitif)
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->text('nik_ayah'); // dienkripsi
            $table->text('nik_ibu'); // dienkripsi
            $table->string('tempat_lahir_ayah');
            $table->string('tempat_lahir_ibu');
            $table->date('tanggal_lahir_ayah');
            $table->date('tanggal_lahir_ibu');
            $table->string('pendidikan_ayah');
            $table->string('pendidikan_ibu');
            $table->string('pekerjaan_ayah');
            $table->string('pekerjaan_ibu');
            $table->text('alamat_ayah');
            $table->text('alamat_ibu');
            $table->text('no_telp'); // dienkripsi
            $table->text('penghasilan'); // dienkripsi - UBAH INI menjadi TEXT
            
            // File Upload (path file, tidak dienkripsi)
            $table->string('akte_kelahiran_path')->nullable();
            $table->string('kartu_keluarga_path')->nullable();
            $table->string('kia_path')->nullable();
            $table->string('bpjs_path')->nullable();
            
            // Status
            $table->enum('status', ['menunggu', 'diproses', 'diverifikasi', 'ditolak'])
                  ->default('menunggu');
                  
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswa_pendaftars');
    }
};