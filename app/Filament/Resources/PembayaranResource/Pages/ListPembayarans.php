<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembayarans extends ListRecords
{
    protected static string $resource = PembayaranResource::class;
    
    // TAMBAHKAN INI:
    protected static ?string $title = 'Data Pembayaran';
    
    // TAMBAHKAN INI untuk breadcrumb
    public function getBreadcrumb(): string
    {
        return 'Data Pembayaran';
    }
    
    // TAMBAHKAN INI untuk judul halaman
    public function getTitle(): string
    {
        return 'Data Pembayaran';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pembayaran'), // TAMBAHKAN INI
        ];
    }
}