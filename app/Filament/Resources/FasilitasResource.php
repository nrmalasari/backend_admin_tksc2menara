<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FasilitasResource\Pages;
use App\Models\Fasilitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FasilitasResource extends Resource
{
    protected static ?string $model = Fasilitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Manajemen Media';
    protected static ?string $navigationLabel = 'Fasilitas Sekolah';
    protected static ?string $pluralModelLabel = 'Data Fasilitas';
    protected static ?string $modelLabel = 'Fasilitas';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Fasilitas')
                    ->schema([
                        Forms\Components\TextInput::make('nama_fasilitas')
                            ->label('Nama Fasilitas')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Ruang Kepala Sekolah, Perpustakaan, dll'),
                        
                        Forms\Components\DatePicker::make('tanggal_update')
                            ->label('Tanggal Update')
                            ->default(now())
                            ->displayFormat('d/m/Y')
                            ->native(false),
                        
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Deskripsi singkat tentang fasilitas'),
                        
                        Forms\Components\FileUpload::make('gambar_fasilitas')
                            ->label('Gambar Fasilitas')
                            ->image()
                            ->directory('fasilitas')
                            ->disk('cloudinary')
                            ->preserveFilenames()
                            ->maxSize(5120) // 5MB (naikkan dari 2MB)
                            ->helperText('Ukuran maksimal 5MB. Format: JPG, PNG, WebP. Gambar akan disimpan sesuai ukuran asli.')
                            ->downloadable()
                            ->openable()
                            ->previewable()
                            ->getUploadedFileNameForStorageUsing(
                                fn ($file) => 'fasilitas_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                            )
                            ->columnSpanFull(),
                    ])->columns(1),
                
                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Status Publikasi')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Non-aktifkan untuk menyimpan sebagai draft'),
                        
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
                    ->alignCenter()
                    ->toggleable(),
                
                Tables\Columns\ImageColumn::make('gambar_url')
                    ->label('Gambar')
                    ->disk('cloudinary')
                    ->square()
                    ->size(50)
                    ->defaultImageUrl(url('/images/default-fasilitas.png'))
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('nama_fasilitas')
                    ->label('Nama Fasilitas')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->nama_fasilitas),
                
                Tables\Columns\TextColumn::make('formatted_tanggal_update')
                    ->label('Tanggal Update')
                    ->sortable(['tanggal_update'])
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->deskripsi)
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('is_published')
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
                Tables\Filters\Filter::make('tanggal_update')
                    ->label('Filter Tanggal Update')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn ($query, $date) => $query->whereDate('tanggal_update', '>=', $date)
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn ($query, $date) => $query->whereDate('tanggal_update', '<=', $date)
                            );
                    }),
                
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Publikasi')
                    ->placeholder('Semua')
                    ->trueLabel('Dipublikasikan')
                    ->falseLabel('Draft'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('publish')
                    ->label('Publikasikan')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->action(fn (Fasilitas $record) => $record->update(['is_published' => true]))
                    ->visible(fn (Fasilitas $record) => !$record->is_published),
                
                Tables\Actions\Action::make('unpublish')
                    ->label('Simpan Draft')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->action(fn (Fasilitas $record) => $record->update(['is_published' => false]))
                    ->visible(fn (Fasilitas $record) => $record->is_published),
                
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Fasilitas $record) => $record->gambar_url, true)
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publishSelected')
                        ->label('Publikasikan Terpilih')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_published' => true])),
                    
                    Tables\Actions\BulkAction::make('unpublishSelected')
                        ->label('Simpan Draft Terpilih')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['is_published' => false])),
                ]),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan')
            ->paginated([5, 10, 25, 50, 'all']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFasilitas::route('/'),
            'create' => Pages\CreateFasilitas::route('/create'),
            'edit' => Pages\EditFasilitas::route('/{record}/edit'),
        ];
    }
}