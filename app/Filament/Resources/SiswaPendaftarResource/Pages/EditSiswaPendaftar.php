<?php

namespace App\Filament\Resources\SiswaPendaftarResource\Pages;

use App\Filament\Resources\SiswaPendaftarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiswaPendaftar extends EditRecord
{
    protected static string $resource = SiswaPendaftarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
