<?php

namespace App\Filament\Resources\RegistPendaftarResource\Pages;

use App\Filament\Resources\RegistPendaftarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistPendaftar extends EditRecord
{
    protected static string $resource = RegistPendaftarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
