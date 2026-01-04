<?php

namespace App\Filament\Resources\SambutanKepalaSekolahResource\Pages;

use App\Filament\Resources\SambutanKepalaSekolahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSambutanKepalaSekolahs extends ListRecords
{
    protected static string $resource = SambutanKepalaSekolahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
