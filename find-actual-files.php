<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\SiswaPendaftar;

echo "=== FINDING ACTUAL FILE LOCATIONS ===\n\n";

$siswas = SiswaPendaftar::all();
$foundFiles = 0;
$missingFiles = 0;

foreach ($siswas as $siswa) {
    echo "Student: " . $siswa->nama_lengkap . "\n";
    
    $fileFields = [
        'akte_kelahiran_path',
        'kartu_keluarga_path',
        'kia_path',
        'bpjs_path'
    ];
    
    foreach ($fileFields as $field) {
        if ($siswa->$field) {
            $dbPath = $siswa->$field;
            $filename = basename($dbPath);
            
            echo "  $field: $filename\n";
            
            // Cari di beberapa lokasi yang mungkin
            $locations = [
                // 1. Di public folder (lokasi asli sebelum migrate)
                public_path('api/secure-files/' . $filename),
                public_path('secure-files/' . $filename),
                
                // 2. Di storage/app/ (lokasi setelah migrate)
                storage_path('app/' . $dbPath), // siswa/dokumen/filename
                storage_path('app/secure-files/' . $filename),
                storage_path('app/public/secure-files/' . $filename),
                
                // 3. Di root storage
                storage_path($dbPath),
                storage_path('secure-files/' . $filename),
            ];
            
            $found = false;
            foreach ($locations as $location) {
                if (file_exists($location)) {
                    echo "    ✅ FOUND at: " . str_replace(base_path(), '', $location) . "\n";
                    echo "       Size: " . filesize($location) . " bytes\n";
                    $found = true;
                    $foundFiles++;
                    break;
                }
            }
            
            if (!$found) {
                echo "    ❌ NOT FOUND\n";
                $missingFiles++;
                
                // Coba cari dengan Storage facade
                try {
                    // Coba disk 'local'
                    if (Storage::disk('local')->exists($dbPath)) {
                        echo "    ✅ FOUND via Storage::disk('local')\n";
                        $foundFiles++;
                        $found = true;
                    }
                } catch (\Exception $e) {
                    // Ignore error
                }
            }
        }
    }
    
    echo "\n";
}

echo "=== SUMMARY ===\n";
echo "Total files found: $foundFiles\n";
echo "Total files missing: $missingFiles\n";
echo "\n";

// Cek isi storage/app/
echo "=== CHECKING storage/app/ DIRECTORY ===\n";
$storagePath = storage_path('app');
if (is_dir($storagePath)) {
    $items = scandir($storagePath);
    echo "Items in storage/app/:\n";
    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..') {
            $path = $storagePath . '/' . $item;
            echo "  $item - " . (is_dir($path) ? '📁' : '📄') . "\n";
            
            // Jika folder, cek isinya
            if (is_dir($path) && ($item === 'siswa' || $item === 'secure-files')) {
                $subItems = scandir($path);
                foreach ($subItems as $subItem) {
                    if ($subItem !== '.' && $subItem !== '..') {
                        $subPath = $path . '/' . $subItem;
                        echo "    └─ $subItem - " . (is_dir($subPath) ? '📁' : '📄') . "\n";
                    }
                }
            }
        }
    }
}

// Cek isi public/api/secure-files/
echo "\n=== CHECKING public/api/secure-files/ DIRECTORY ===\n";
$publicPath = public_path('api/secure-files');
if (is_dir($publicPath)) {
    $items = scandir($publicPath);
    $files = array_filter($items, function($item) {
        return $item !== '.' && $item !== '..';
    });
    
    echo "Files in public/api/secure-files/: " . count($files) . "\n";
    foreach ($files as $file) {
        $path = $publicPath . '/' . $file;
        echo "  $file - " . filesize($path) . " bytes\n";
    }
} else {
    echo "Directory not exists: $publicPath\n";
}