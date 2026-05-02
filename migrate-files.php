<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\SiswaPendaftar;

echo "=== Migrating Files to Secure Storage ===\n";

$siswas = SiswaPendaftar::all();
$total = count($siswas);
$moved = 0;
$errors = 0;

foreach ($siswas as $index => $siswa) {
    // PERBAIKAN: Gunakan concatenation
    echo "Processing " . $siswa->nama_lengkap . " (" . ($index + 1) . "/" . $total . ")...\n";
    
    $fileFields = [
        'akte_kelahiran_path',
        'kartu_keluarga_path',
        'kia_path',
        'bpjs_path'
    ];
    
    foreach ($fileFields as $field) {
        if ($siswa->$field) {
            $oldPath = $siswa->$field;
            
            // Clean path
            $cleanPath = $oldPath;
            if (strpos($cleanPath, 'storage/') === 0) {
                $cleanPath = substr($cleanPath, 8);
            }
            if (strpos($cleanPath, 'public/') === 0) {
                $cleanPath = substr($cleanPath, 7);
            }
            
            // Cek jika file ada di public storage
            if (Storage::disk('public')->exists($cleanPath)) {
                try {
                    // Baca file
                    $fileContent = Storage::disk('public')->get($cleanPath);
                    
                    // Generate nama file baru dengan user_id
                    $filename = pathinfo($cleanPath, PATHINFO_BASENAME);
                    $newPath = 'siswa/dokumen/' . $filename;
                    
                    // Tulis ke secure storage
                    Storage::disk('secure_docs')->put($newPath, $fileContent);
                    
                    // Update path di database
                    $siswa->$field = $newPath;
                    
                    // Hapus dari public storage
                    Storage::disk('public')->delete($cleanPath);
                    
                    $moved++;
                    // PERBAIKAN: Gunakan concatenation
                    echo "  Moved: " . $filename . "\n";
                    
                } catch (\Exception $e) {
                    $errors++;
                    // PERBAIKAN: Gunakan concatenation
                    echo "  Error moving " . $filename . ": " . $e->getMessage() . "\n";
                }
            } else {
                // PERBAIKAN: Tambahkan info jika file tidak ditemukan
                echo "  File not found: " . $cleanPath . "\n";
            }
        }
    }
    
    // Simpan perubahan ke database
    try {
        $siswa->save();
        // PERBAIKAN: Tambahkan konfirmasi
        echo "  Database updated for student ID: " . $siswa->id . "\n";
    } catch (\Exception $e) {
        $errors++;
        echo "  Error saving to database: " . $e->getMessage() . "\n";
    }
}

// PERBAIKAN: Gunakan concatenation di semua echo
echo "\n=== Migration Complete ===\n";
echo "Total students processed: " . $total . "\n";
echo "Total files moved: " . $moved . "\n";
echo "Total errors: " . $errors . "\n";

// PERBAIKAN: Tambahkan summary
if ($errors === 0) {
    echo "✅ Migration completed successfully!\n";
} else {
    echo "⚠️  Migration completed with " . $errors . " error(s)\n";
}