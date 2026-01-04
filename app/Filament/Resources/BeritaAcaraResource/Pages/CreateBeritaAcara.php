<?php

namespace App\Filament\Resources\BeritaAcaraResource\Pages;

use App\Filament\Resources\BeritaAcaraResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBeritaAcara extends CreateRecord
{
    protected static string $resource = BeritaAcaraResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-generate slug jika kosong
        if (empty($data['slug']) && !empty($data['judul'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['judul']);
        }

        return $data;
    }
}