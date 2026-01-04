<?php

namespace App\Filament\Resources\BeritaAcaraResource\Pages;

use App\Filament\Resources\BeritaAcaraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBeritaAcara extends EditRecord
{
    protected static string $resource = BeritaAcaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->url(fn () => url('/berita/' . $this->record->slug), shouldOpenInNewTab: true)
                ->visible(fn () => $this->record->publikasi === 'publik'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Auto-generate slug jika kosong
        if (empty($data['slug']) && !empty($data['judul'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['judul']);
        }

        return $data;
    }
}