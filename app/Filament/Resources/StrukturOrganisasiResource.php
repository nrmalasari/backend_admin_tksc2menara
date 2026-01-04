<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrukturOrganisasiResource\Pages;
use App\Models\StrukturOrganisasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class StrukturOrganisasiResource extends Resource
{
    protected static ?string $model = StrukturOrganisasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Manajemen Media';
    protected static ?string $navigationLabel = 'Struktur Organisasi';
    protected static ?string $pluralModelLabel = 'Struktur Organisasi';
    protected static ?string $modelLabel = 'Struktur Organisasi';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Struktur Organisasi')
                    ->schema([
                        Forms\Components\TextInput::make('nama_struktur')
                            ->label('Nama Struktur')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Struktur Organisasi TK SC2 Menara Parepare'),
                        
                        Forms\Components\FileUpload::make('gambar_struktur')
                            ->label('Gambar Struktur')
                            ->image()
                            ->directory('struktur-organisasi')
                            ->disk('public')
                            ->preserveFilenames()
                            ->maxSize(10240) // 10MB (naikkan dari 5MB)
                            ->helperText('Ukuran maksimal 10MB. Format: JPG, PNG, WebP. Gambar akan disimpan sesuai ukuran asli.')
                            ->downloadable()
                            ->openable()
                            ->previewable()
                            ->getUploadedFileNameForStorageUsing(
                                fn ($file) => 'struktur_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                            )
                            ->columnSpanFull(),
                    ])->columns(1),
                
                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Non-aktifkan jika tidak ingin ditampilkan'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar_url')
                    ->label('Gambar')
                    ->square()
                    ->size(50)
                    ->defaultImageUrl(url('/images/default-struktur.png'))
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('nama_struktur')
                    ->label('Nama Struktur')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->nama_struktur),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->toggleable(),
                
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Non-Aktif'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('viewImage')
                        ->label('Lihat Gambar')
                        ->icon('heroicon-o-photo')
                        ->color('info')
                        ->modalHeading(fn (StrukturOrganisasi $record) => "Gambar Struktur: {$record->nama_struktur}")
                        ->modalContent(function (StrukturOrganisasi $record) {
                            return view('filament.components.struktur-image-modal', [
                                'record' => $record,
                                'gambar_url' => $record->gambar_url,
                                'nama_struktur' => $record->nama_struktur,
                            ]);
                        })
                        ->modalWidth('xl')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup'),
                    
                    Tables\Actions\Action::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (StrukturOrganisasi $record) => $record->update(['is_active' => true]))
                        ->visible(fn (StrukturOrganisasi $record) => !$record->is_active),
                    
                    Tables\Actions\Action::make('deactivate')
                        ->label('Non-Aktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (StrukturOrganisasi $record) => $record->update(['is_active' => false]))
                        ->visible(fn (StrukturOrganisasi $record) => $record->is_active),
                    
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 'all']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStrukturOrganisasis::route('/'),
            'create' => Pages\CreateStrukturOrganisasi::route('/create'),
            'edit' => Pages\EditStrukturOrganisasi::route('/{record}/edit'),
        ];
    }
}