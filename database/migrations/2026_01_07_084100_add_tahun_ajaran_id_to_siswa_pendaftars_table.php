<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa_pendaftars', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('regist_pendaftar_id')->constrained('tahun_ajarans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('siswa_pendaftars', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropColumn('tahun_ajaran_id');
        });
    }
};