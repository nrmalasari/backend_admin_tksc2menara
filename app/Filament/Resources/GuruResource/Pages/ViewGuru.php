<?php

namespace App\Filament\Resources\GuruResource\Pages;

use App\Filament\Resources\GuruResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGuru extends ViewRecord
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            
            Actions\Action::make('viewFoto')
                ->label('Lihat Foto')
                ->icon('heroicon-o-photo')
                ->color('info')
                ->modalHeading(fn () => "Foto Guru: {$this->record->nama_lengkap}")
                ->modalContent(function () {
                    return view('filament.components.guru-foto-modal', [
                        'record' => $this->record,
                        'foto_url' => $this->record->foto_url,
                        'nama_lengkap' => $this->record->nama_lengkap,
                    ]);
                })
                ->modalWidth('lg')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),
        ];
    }
}