<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSiswa extends ViewRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            
            Actions\Action::make('generateNIS')
                ->label('Generate NIS')
                ->icon('heroicon-o-identification')
                ->color('warning')
                ->action(function () {
                    if (empty($this->record->nis)) {
                        // Format NIS: urutan pendaftaran + bulan + tahun
                        $year = date('Y');
                        $month = date('m');
                        $totalSiswa = \App\Models\Siswa::count();
                        $urutan = $totalSiswa + 1;
                        $urutanFormatted = str_pad($urutan, 3, '0', STR_PAD_LEFT);
                        
                        $this->record->nis = $urutanFormatted . $month . $year;
                        $this->record->save();
                        
                        $this->notify('success', 'NIS berhasil digenerate: ' . $this->record->nis);
                    } else {
                        $this->notify('warning', 'Siswa sudah memiliki NIS');
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Generate NIS')
                ->modalDescription('Apakah Anda yakin ingin generate NIS untuk siswa ini?')
                ->visible(fn () => empty($this->record->nis)),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Anda bisa menambahkan widgets di sini jika diperlukan
        ];
    }

    public function getHeading(): string
    {
        return $this->record->nama_lengkap;
    }

    public function getSubheading(): string
    {
        return "NIS: " . ($this->record->nis ?? 'Belum ada') . " | Status: " . $this->record->status_text;
    }
}