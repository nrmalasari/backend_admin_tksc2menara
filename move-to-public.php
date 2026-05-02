<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\SiswaPendaftar;

echo "=== MOVING FILES TO PUBLIC STORAGE ===\n\n";

$siswas = SiswaPendaftar::all();
$total = count($siswas);
$moved = 0;
$errors = 0;

foreach ($siswas as $index => $siswa) {
    echo "Processing " . $siswa->nama_lengkap . " (" . ($index + 1) . "/" . $total . ")\n";
    
    $fileFields = [
        'akte_kelahiran_path',
        'kartu_keluarga_path', 
        'kia_path',
        'bpjs_path'
    ];
    
    foreach ($fileFields as $field) {
        if (!empty($siswa->$field)) {
            $currentPath = $siswa->$field; // Format: siswa/dokumen/filename
            $filename = basename($currentPath);
            
            echo "  Field: $field\n";
            echo "  Current path: $currentPath\n";
            echo "  Filename: $filename\n";
            
            // Path sumber: storage/app/secure_docs/siswa/dokumen/filename
            $sourcePath = storage_path('app/secure_docs/' . $currentPath);
            
            if (file_exists($sourcePath)) {
                try {
                    // Path tujuan: storage/app/public/api/secure-files/filename
                    $publicDir = storage_path('app/public/api/secure-files');
                    if (!is_dir($publicDir)) {
                        mkdir($publicDir, 0755, true);
                    }
                    
                    $destPath = $publicDir . '/' . $filename;
                    
                    // Copy file
                    if (copy($sourcePath, $destPath)) {
                        // Update database dengan path baru
                        // Format: api/secure-files/filename
                        $newDbPath = 'api/secure-files/' . $filename;
                        $siswa->$field = $newDbPath;
                        
                        $moved++;
                        echo "  ✅ Moved to: $destPath\n";
                        echo "     New DB path: $newDbPath\n";
                    } else {
                        echo "  ❌ Failed to copy file\n";
                        $errors++;
                    }
                    
                } catch (\Exception $e) {
                    echo "  ❌ Error: " . $e->getMessage() . "\n";
                    $errors++;
                }
            } else {
                echo "  ❌ Source file not found: $sourcePath\n";
                $errors++;
            }
        }
    }
    
    // Save perubahan database
    try {
        $siswa->save();
        echo "  💾 Database updated\n";
    } catch (\Exception $e) {
        echo "  ❌ Database save error: " . $e->getMessage() . "\n";
        $errors++;
    }
    
    echo "\n";
}

echo "=== MOVE COMPLETE ===\n";
echo "Total students processed: $total\n";
echo "Total files moved: $moved\n";
echo "Total errors: $errors\n";

// Buat symbolic link jika belum ada
echo "\n=== CREATING SYMBOLIC LINK ===\n";
$linkPath = public_path('storage');
$targetPath = storage_path('app/public');

if (!is_link($linkPath) && !is_dir($linkPath)) {
    echo "Creating symbolic link: $linkPath -> $targetPath\n";
    
    // Untuk Windows
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows menggunakan junction
        exec("mklink /J \"$linkPath\" \"$targetPath\"");
    } else {
        // Linux/Mac
        symlink($targetPath, $linkPath);
    }
    
    echo "✅ Symbolic link created\n";
} else {
    echo "Symbolic link already exists\n";
}

// Test file access
echo "\n=== TESTING FILE ACCESS ===\n";
$testFiles = [
    'akte_1770349074_5CPxJsd6Y7.jpeg',
    'kk_1770349074_8xMviQaMaZ.png'
];

foreach ($testFiles as $filename) {
    $storagePath = storage_path('app/public/api/secure-files/' . $filename);
    $publicUrl = '/storage/api/secure-files/' . $filename;
    
    echo "File: $filename\n";
    echo "  Storage path: $storagePath\n";
    echo "  Public URL: $publicUrl\n";
    echo "  Exists: " . (file_exists($storagePath) ? "✅ YES" : "❌ NO") . "\n";
    if (file_exists($storagePath)) {
        echo "  Size: " . filesize($storagePath) . " bytes\n";
    }
    echo "\n";
}

echo "Test URL: https://admin.tksc2menara.dpdns.org/storage/api/secure-files/akte_1770349074_5CPxJsd6Y7.jpeg\n";