<?php

namespace App\Filament\Resources\RincianPembayaranResource\Pages;

use App\Filament\Resources\RincianPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRincianPembayarans extends ListRecords
{
    protected static string $resource = RincianPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Rincian Pembayaran')
                ->icon('heroicon-o-plus-circle'), // Optional: tambahkan icon
        ];
    }
}
