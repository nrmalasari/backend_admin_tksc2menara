<?php

namespace App\Filament\Resources\BeritaAcaraResource\Pages;

use App\Filament\Resources\BeritaAcaraResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBeritaAcara extends ViewRecord
{
    protected static string $resource = BeritaAcaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}