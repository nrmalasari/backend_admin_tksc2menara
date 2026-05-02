<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SiswaPendaftar;

echo "=== MIGRATING DATABASE FILE PATHS ===\n\n";

// Method 1: Gunakan method di model
$updated = SiswaPendaftar::migrateDatabasePaths();
echo "✅ Updated {$updated} records using model method\n\n";

// Method 2: Manual update untuk pastikan
use Illuminate\Support\Facades\DB;

$tables = [
    'siswa_pendaftars' => ['akte_kelahiran_path', 'kartu_keluarga_path', 'kia_path', 'bpjs_path']
];

foreach ($tables as $table => $fields) {
    foreach ($fields as $field) {
        $result = DB::table($table)
            ->whereNotNull($field)
            ->where($field, 'LIKE', 'siswa/dokumen/%')
            ->update([
                $field => DB::raw("CONCAT('api/secure-files/', SUBSTRING($field, 15))")
            ]);
        
        echo "Updated {$result} rows in {$table}.{$field}\n";
    }
}

echo "\n=== VERIFYING MIGRATION ===\n";
$sample = SiswaPendaftar::first();
if ($sample) {
    echo "Sample student: " . $sample->nama_lengkap . "\n";
    echo "Akte path: " . $sample->akte_kelahiran_path . "\n";
    echo "Akte URL: " . $sample->akte_kelahiran_url . "\n";
    echo "Akte exists: " . ($sample->akte_kelahiran_exists ? '✅ YES' : '❌ NO') . "\n";
}

echo "\n=== CLEARING CACHE ===\n";
$kernel->call('route:clear');
$kernel->call('config:clear');
$kernel->call('cache:clear');
$kernel->call('view:clear');
echo "✅ Cache cleared!\n";

echo "\n=== TEST INSTRUCTIONS ===\n";
echo "1. Login sebagai admin → Buka halaman Siswa Pendaftar\n";
echo "2. Cek gambar dokumen → Harus muncul\n";
echo "3. Logout → Refresh halaman → Gambar harus TIDAK muncul (403)\n";
echo "4. Test URL langsung: https://admin.tksc2menara.dpdns.org/storage/api/secure-files/akte_xxxxx.jpg\n";