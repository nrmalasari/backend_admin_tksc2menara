<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeritaAcaraResource\Pages;
use App\Models\BeritaAcara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Components\Repeater;

class BeritaAcaraResource extends Resource
{
    protected static ?string $model = BeritaAcara::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Manajemen Media';
    protected static ?string $navigationLabel = 'Berita Acara';
    protected static ?string $pluralModelLabel = 'Berita Acara';
    protected static ?string $modelLabel = 'Berita Acara';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Berita Acara')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Foto Utama')
                            ->image()
                            ->directory('berita-acara')
                            ->disk('public')
                            ->maxSize(2048)
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 
                                    'thumbnail-' . time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                            )
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('450')
                            ->helperText('Foto utama berita (Rasio 16:9, maks. 2MB)')
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Berita')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Pemeriksaan Kesehatan Siswa TK SC2 Menara')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('judul', Str::title($state));
                            }),
                        
                        Forms\Components\DatePicker::make('tanggal_acara')
                            ->label('Tanggal Acara')
                            ->required()
                            ->default(now())
                            ->displayFormat('d/m/Y')
                            ->native(false),
                        
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(5)
                            ->maxLength(5000)
                            ->placeholder('Deskripsi lengkap berita acara...'),
                        
                        Forms\Components\TagsInput::make('tags')
                            ->label('Tags/Kategori')
                            ->placeholder('Tambahkan tag')
                            ->separator(',')
                            ->helperText('Tekan Enter atau koma untuk menambah tag'),
                    ])->columns(1),
                
                Forms\Components\Section::make('Galeri Foto Tambahan')
                    ->description('Tambahkan foto-foto lain untuk galeri berita')
                    ->schema([
                        Repeater::make('media')
                            ->relationship()
                            ->schema([
                                Forms\Components\FileUpload::make('path')
                                    ->label('Upload Foto')
                                    ->image()
                                    ->directory('berita-acara/gallery')
                                    ->disk('public')
                                    ->maxSize(2048)
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (TemporaryUploadedFile $file): string => 
                                            'gallery-' . time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                                    )
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('800')
                                    ->imageResizeTargetHeight('450')
                                    ->required(),
                                
                                Forms\Components\TextInput::make('keterangan')
                                    ->label('Keterangan Foto')
                                    ->placeholder('Deskripsi singkat foto')
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('urutan')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Angka kecil akan ditampilkan lebih dulu'),
                                
                                Forms\Components\Toggle::make('is_thumbnail')
                                    ->label('Jadikan Foto Utama?')
                                    ->helperText('Jika foto ini ingin dijadikan foto utama')
                                    ->default(false)
                                    ->inline(false),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->reorderable()
                            ->cloneable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                $state['keterangan'] ?? 'Foto ' . ($state['urutan'] + 1) ?? 'Foto Baru'
                            )
                            ->defaultItems(0),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($operation) => $operation === 'edit'),
                
                Forms\Components\Section::make('Pengaturan Publikasi')
                    ->schema([
                        Forms\Components\Select::make('publikasi')
                            ->label('Status Publikasi')
                            ->options([
                                'draft' => 'Draft',
                                'publik' => 'Publik',
                                'arsip' => 'Arsip',
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false)
                            ->helperText('Pilih status publikasi berita'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('Foto Utama')
                    ->square()
                    ->defaultImageUrl(asset('images/default-news.png'))
                    ->extraImgAttributes(['class' => 'object-cover rounded'])
                    ->size(50),
                
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->weight('medium')
                    ->tooltip(fn ($record) => $record->judul),
                
                // PERBAIKAN: Gunakan format date() tanpa accessor custom
                Tables\Columns\TextColumn::make('tanggal_acara')
                    ->label('Tanggal Acara')
                    ->date('d/m/Y') // Gunakan format() method dari Filament
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('short_deskripsi')
                    ->label('Deskripsi')
                    ->limit(80)
                    ->tooltip(fn ($record) => $record->deskripsi),
                
                Tables\Columns\BadgeColumn::make('publikasi')
                    ->label('Status')
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'publik' => 'Publik',
                        'draft' => 'Draft',
                        'arsip' => 'Arsip',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'publik' => 'success',
                        'draft' => 'warning',
                        'arsip' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_images')
                    ->label('Jml Foto')
                    ->getStateUsing(fn ($record) => $record->media->count() . ' foto')
                    ->badge()
                    ->color(fn ($state) => strpos($state, '0') === false ? 'primary' : 'gray')
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('tags_string')
                    ->label('Tags')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                
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
                Tables\Filters\SelectFilter::make('publikasi')
                    ->label('Status Publikasi')
                    ->options([
                        'publik' => 'Publik',
                        'draft' => 'Draft',
                        'arsip' => 'Arsip',
                    ]),
                
                Tables\Filters\Filter::make('tanggal_acara')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_dari')
                            ->label('Dari Tanggal')
                            ->native(false),
                        Forms\Components\DatePicker::make('tanggal_sampai')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['tanggal_dari'], fn ($q, $date) => $q->whereDate('tanggal_acara', '>=', $date))
                            ->when($data['tanggal_sampai'], fn ($q, $date) => $q->whereDate('tanggal_acara', '<=', $date));
                    }),
                
                Tables\Filters\Filter::make('has_media')
                    ->label('Memiliki Galeri Foto')
                    ->query(fn ($query) => $query->whereHas('media'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('publish')
                    ->label('Publikasikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (BeritaAcara $record) => $record->update(['publikasi' => 'publik']))
                    ->visible(fn (BeritaAcara $record) => $record->publikasi !== 'publik'),
                
                Tables\Actions\Action::make('draft')
                    ->label('Set Draft')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->action(fn (BeritaAcara $record) => $record->update(['publikasi' => 'draft']))
                    ->visible(fn (BeritaAcara $record) => $record->publikasi !== 'draft'),
                
                Tables\Actions\Action::make('archive')
                    ->label('Arsipkan')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->action(fn (BeritaAcara $record) => $record->update(['publikasi' => 'arsip']))
                    ->visible(fn (BeritaAcara $record) => $record->publikasi !== 'arsip'),
                
                Tables\Actions\Action::make('manageMedia')
                    ->label('Kelola Foto')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->url(fn (BeritaAcara $record) => BeritaAcaraResource::getUrl('edit', ['record' => $record]) . '#galeri-foto-tambahan'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publishSelected')
                        ->label('Publikasikan Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['publikasi' => 'publik'])),
                    
                    Tables\Actions\BulkAction::make('draftSelected')
                        ->label('Set Draft Terpilih')
                        ->icon('heroicon-o-pencil')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['publikasi' => 'draft'])),
                    
                    Tables\Actions\BulkAction::make('archiveSelected')
                        ->label('Arsipkan Terpilih')
                        ->icon('heroicon-o-archive-box')
                        ->color('gray')
                        ->action(fn ($records) => $records->each->update(['publikasi' => 'arsip'])),
                ]),
            ])
            ->defaultSort('tanggal_acara', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBeritaAcaras::route('/'),
            'create' => Pages\CreateBeritaAcara::route('/create'),
            'edit' => Pages\EditBeritaAcara::route('/{record}/edit'),
            'view' => Pages\ViewBeritaAcara::route('/{record}'),
        ];
    }
}