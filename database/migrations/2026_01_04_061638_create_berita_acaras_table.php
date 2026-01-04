<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique()->nullable(); // SLUG SUDAH DISINI
            $table->date('tanggal_acara');
            $table->text('deskripsi');
            $table->string('thumbnail')->nullable();
            $table->enum('publikasi', ['publik', 'draft', 'arsip'])->default('draft');
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acaras');
    }
};