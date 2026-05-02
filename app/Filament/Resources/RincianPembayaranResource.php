<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RincianPembayaranResource\Pages;
use App\Models\RincianPembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RincianPembayaranResource extends Resource
{
    protected static ?string $model = RincianPembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Manajemen Media';
    protected static ?string $navigationLabel = 'Informasi Pembayaran';
    protected static ?string $pluralModelLabel = 'Data Rincian Pembayaran';
    protected static ?string $modelLabel = 'Rincian Pembayaran';

    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Rincian Pembayaran')
                    ->schema([
                        Forms\Components\TextInput::make('nama_rincian_pembayaran')
                            ->label('Nama Rincian')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Biaya Pendaftaran, Uang Pangkal, dll'),
                        
                        Forms\Components\TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->numeric()
                            ->required()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->placeholder('Masukkan jumlah harga'),
                        
                        Forms\Components\Select::make('jenis')
                            ->label('Jenis Pembayaran')
                            ->options([
                                'formulir' => 'Formulir Pendaftaran',
                                'uang_bangunan' => 'Uang Bangunan',
                                'seragam' => 'Seragam Sekolah',
                                'buku' => 'Buku Paket',
                                'lainnya' => 'Lainnya',
                            ])
                            ->required()
                            ->native(true),
                        
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Deskripsi singkat tentang rincian pembayaran'),
                    ])->columns(1),
                
                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Non-aktifkan jika tidak ingin ditampilkan'),
                        
                        Forms\Components\TextInput::make('urutan')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->helperText('Angka kecil akan ditampilkan lebih dulu'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('nama_rincian_pembayaran')
                    ->label('Nama Rincian')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('jenis_label')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'Formulir Pendaftaran' => 'primary',
                        'Uang Bangunan' => 'success',
                        'Seragam Sekolah' => 'warning',
                        'Buku Paket' => 'info',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('formatted_total_harga')
                    ->label('Total Harga')
                    ->sortable()
                    ->alignRight()
                    ->color('danger'),
                
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->deskripsi)
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')
                    ->label('Filter Jenis')
                    ->options([
                        'formulir' => 'Formulir Pendaftaran',
                        'uang_bangunan' => 'Uang Bangunan',
                        'seragam' => 'Seragam Sekolah',
                        'buku' => 'Buku Paket',
                        'lainnya' => 'Lainnya',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Non-Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('activate')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (RincianPembayaran $record) => $record->update(['is_active' => true]))
                    ->visible(fn (RincianPembayaran $record) => !$record->is_active),
                
                Tables\Actions\Action::make('deactivate')
                    ->label('Non-Aktifkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (RincianPembayaran $record) => $record->update(['is_active' => false]))
                    ->visible(fn (RincianPembayaran $record) => $record->is_active),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activateSelected')
                        ->label('Aktifkan Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    
                    Tables\Actions\BulkAction::make('deactivateSelected')
                        ->label('Non-Aktifkan Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRincianPembayarans::route('/'),
            'create' => Pages\CreateRincianPembayaran::route('/create'),
            'edit' => Pages\EditRincianPembayaran::route('/{record}/edit'),
        ];
    }
}