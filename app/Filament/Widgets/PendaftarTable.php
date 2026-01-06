<?php

namespace App\Filament\Widgets;

use App\Models\SiswaPendaftar;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class PendaftarTable extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                SiswaPendaftar::query()
                    ->whereIn('status', ['menunggu', 'diproses'])
                    ->latest()
            )
            ->columns([
                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('nik_decrypted')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('formatted_tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->sortable(['tanggal_lahir']),
                    
                TextColumn::make('usia')
                    ->label('Usia')
                    ->suffix(' tahun')
                    ->sortable(),
                    
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Laki-laki' => 'primary',
                        'Perempuan' => 'success',
                        default => 'gray',
                    }),
                    
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'menunggu',
                        'info' => 'diproses',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        default => $state,
                    }),
                    
                TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn (SiswaPendaftar $record): string => route('filament.admin.resources.siswa-pendaftars.view', $record))
                    ->openUrlInNewTab(),
                    
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (SiswaPendaftar $record): string => route('filament.admin.resources.siswa-pendaftars.edit', $record)),
            ])
            ->emptyStateHeading('Tidak ada pendaftar baru')
            ->emptyStateDescription('Semua pendaftar telah diproses')
            ->emptyStateIcon('heroicon-o-user-group')
            ->paginated([10, 25, 50]);
    }
    
    public function getHeading(): string
    {
        return 'Pendaftar Baru (Menunggu Verifikasi)';
    }
    
    public function getDescription(): ?string
    {
        return 'Daftar calon siswa yang sedang menunggu verifikasi atau diproses';
    }
}