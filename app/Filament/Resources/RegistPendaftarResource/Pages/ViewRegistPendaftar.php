<?php

namespace App\Filament\Resources\RegistPendaftarResource\Pages;

use App\Filament\Resources\RegistPendaftarResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRegistPendaftar extends ViewRecord
{
    protected static string $resource = RegistPendaftarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}