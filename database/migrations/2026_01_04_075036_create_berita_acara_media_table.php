<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('berita_acara_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_acara_id')
                ->constrained('berita_acaras')
                ->onDelete('cascade');
            $table->string('path');
            $table->string('nama_file')->nullable(); // Bisa null atau default
            $table->integer('urutan')->default(0);
            $table->text('keterangan')->nullable();
            $table->boolean('is_thumbnail')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('berita_acara_media');
    }
};