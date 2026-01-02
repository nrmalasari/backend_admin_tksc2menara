<?php

namespace App\Filament\Resources\SiswaPendaftarResource\Pages;

use App\Filament\Resources\SiswaPendaftarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiswaPendaftars extends ListRecords
{
    protected static string $resource = SiswaPendaftarResource::class;
    
    protected static ?string $title = 'Data Siswa Pendaftar';
    
    public function getBreadcrumb(): string
    {
        return 'Data Siswa Pendaftar';
    }
    
    public function getTitle(): string
    {
        return 'Data Siswa Pendaftar';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Siswa Pendaftar')
                ->icon('heroicon-o-plus-circle'), // Optional: tambahkan icon
        ];
    }
}