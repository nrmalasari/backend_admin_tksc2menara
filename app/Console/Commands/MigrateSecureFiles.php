<?php

namespace App\Console\Commands;

use App\Models\SiswaPendaftar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MigrateSecureFiles extends Command
{
    // Nama command untuk dijalankan di terminal
    protected $signature = 'secure:revert-files';
    protected $description = 'Mengembalikan file dari folder secure ke public secara paksa';

    public function handle()
    {
        $this->info('Memulai proses pengembalian file ke Public Storage...');

        $siswas = SiswaPendaftar::all();
        $count = 0;
        
        // Daftar kolom file
        $fileColumns = [
            'akte_kelahiran_path',
            'kartu_keluarga_path',
            'kia_path',
            'bpjs_path'
        ];

        // Folder tujuan di public
        $targetFolder = 'api/secure-files'; 

        // Folder asal (Brankas kita sebelumnya)
        $secureFolderPath = storage_path('app/secure-files');

        foreach ($siswas as $siswa) {
            $updated = false;

            foreach ($fileColumns as $column) {
                // Ambil nama file saja (bersihkan path jika ada)
                $oldValue = $siswa->$column;
                if (empty($oldValue)) continue;

                $filename = basename($oldValue);
                $sourcePath = $secureFolderPath . '/' . $filename;
                
                $foundContent = null;

                // 1. Cek apakah file ada di folder fisik 'secure-files'
                if (File::exists($sourcePath)) {
                    $foundContent = File::get($sourcePath);
                    $this->info("Menemukan file di Secure Folder: {$filename}");
                } 
                // 2. Cek apakah file ada di folder 'public' (mungkin sudah benar)
                elseif (Storage::disk('public')->exists("{$targetFolder}/{$filename}")) {
                    $this->comment("File sudah ada di Public: {$filename}");
                    // Kita tandai found agar DB path diperbaiki jika salah
                    $foundContent = 'exists'; 
                }

                // 3. Proses Pemindahan
                if ($foundContent) {
                    // Jika kontennya ada (berarti dari secure), kita copy ke public
                    if ($foundContent !== 'exists') {
                        Storage::disk('public')->put("{$targetFolder}/{$filename}", $foundContent);
                        
                        // Hapus file dari folder secure agar bersih
                        File::delete($sourcePath);
                        $this->comment("✓ Berhasil dipindah ke Public: {$filename}");
                    }

                    // 4. Perbaiki Database agar mengarah ke path public yang lama
                    // Format: api/secure-files/namafile.jpg
                    $newDbPath = "{$targetFolder}/{$filename}";
                    
                    if ($siswa->$column !== $newDbPath) {
                        $siswa->$column = $newDbPath;
                        $updated = true;
                        $this->info("  -> Database diperbarui.");
                    }
                } else {
                    $this->error("✗ File hilang/tidak ditemukan: {$filename}");
                }
            }

            if ($updated) {
                $siswa->save();
                $count++;
            }
        }

        $this->info("Selesai! {$count} data berhasil dikembalikan ke pengaturan awal.");
        $this->info("Sekarang file Anda sudah kembali ke folder Public.");
    }
}