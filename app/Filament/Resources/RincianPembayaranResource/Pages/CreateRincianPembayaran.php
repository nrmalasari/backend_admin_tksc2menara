<?php

namespace App\Filament\Resources\RincianPembayaranResource\Pages;

use App\Filament\Resources\RincianPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRincianPembayaran extends CreateRecord
{
    protected static string $resource = RincianPembayaranResource::class;
    public function getTitle(): string
    {
        return 'Tambah Rincian Pembayaran';
    }
}
