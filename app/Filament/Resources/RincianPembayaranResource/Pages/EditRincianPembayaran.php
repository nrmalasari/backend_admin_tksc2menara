<?php

namespace App\Filament\Resources\RincianPembayaranResource\Pages;

use App\Filament\Resources\RincianPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRincianPembayaran extends EditRecord
{
    protected static string $resource = RincianPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
