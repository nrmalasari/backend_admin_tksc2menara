<?php

namespace App\Filament\Resources\SiswaPendaftarResource\Pages;

use App\Filament\Resources\SiswaPendaftarResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSiswaPendaftar extends ViewRecord
{
    protected static string $resource = SiswaPendaftarResource::class;
    
    // TAMBAHKAN INI untuk mengubah judul halaman view
    protected static ?string $title = 'Detail Siswa Pendaftar';
    
    // TAMBAHKAN INI untuk mengubah breadcrumb
    public function getBreadcrumb(): string
    {
        return 'Detail Siswa Pendaftar';
    }
    
    // TAMBAHKAN INI untuk mengubah judul di atas form
    public function getTitle(): string
    {
        return 'Detail Siswa Pendaftar';
    }
}