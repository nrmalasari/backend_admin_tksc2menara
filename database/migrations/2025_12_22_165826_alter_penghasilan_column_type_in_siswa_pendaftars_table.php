<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Tidak perlu melakukan apa-apa karena kolom sudah dibuat sebagai TEXT
        // di migrasi create_siswa_pendaftars_table
    }

    public function down()
    {
        // Tidak perlu melakukan apa-apa
    }
};