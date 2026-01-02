<?php

namespace App\Filament\Resources\RegistPendaftarResource\Pages;

use App\Filament\Resources\RegistPendaftarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistPendaftars extends ListRecords
{
    protected static string $resource = RegistPendaftarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Akun Pendaftar')
                ->icon('heroicon-o-plus-circle'), // Optional: tambahkan icon
        ];
    }
}
