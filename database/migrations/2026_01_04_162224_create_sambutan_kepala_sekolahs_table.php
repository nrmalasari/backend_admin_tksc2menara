<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sambutan_kepala_sekolahs', function (Blueprint $table) {
            $table->id();
            $table->text('sambutan')->nullable(); // Teks sambutan
            $table->string('foto')->nullable(); // Foto kepala sekolah
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sambutan_kepala_sekolahs');
    }
};