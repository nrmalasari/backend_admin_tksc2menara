<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsDashboard;
use App\Filament\Widgets\PendaftarTable;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.pages.dashboard';
    
    public function getWidgets(): array
    {
        return [
            // Urutan widget di dashboard
            StatsDashboard::class,
            PendaftarTable::class,
            
            // Widget default Filament (opsional)
            // \Filament\Widgets\AccountWidget::class,
            // \Filament\Widgets\FilamentInfoWidget::class,
        ];
    }
    
    public function getColumns(): int | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 4,
        ];
    }
}