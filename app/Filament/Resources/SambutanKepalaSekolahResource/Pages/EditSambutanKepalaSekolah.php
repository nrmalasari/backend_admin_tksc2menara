<?php

namespace App\Filament\Resources\SambutanKepalaSekolahResource\Pages;

use App\Filament\Resources\SambutanKepalaSekolahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSambutanKepalaSekolah extends EditRecord
{
    protected static string $resource = SambutanKepalaSekolahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
