<?php

namespace App\Filament\Resources\SiswaPendaftarResource\Pages;

use App\Filament\Resources\SiswaPendaftarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswaPendaftar extends CreateRecord
{
    protected static string $resource = SiswaPendaftarResource::class;
    
    // TAMBAHKAN INI untuk mengubah judul halaman create
    protected static ?string $title = 'Tambah Siswa Pendaftar';
    
    // TAMBAHKAN INI untuk mengubah breadcrumb
    public function getBreadcrumb(): string
    {
        return 'Tambah Siswa Pendaftar';
    }
    
    // TAMBAHKAN INI untuk mengubah judul di atas form
    public function getTitle(): string
    {
        return 'Tambah Siswa Pendaftar';
    }
}