<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('regist_pendaftars', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->text('password'); // akan dienkripsi AES-256-CBC
            $table->text('encrypted_password'); // backup enkripsi jika perlu
            $table->text('encrypted_data')->nullable(); // untuk data sensitif lainnya
            $table->string('registration_ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('regist_pendaftars');
    }
};