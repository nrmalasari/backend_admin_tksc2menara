<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SambutanKepalaSekolahResource\Pages;
use App\Models\SambutanKepalaSekolah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class SambutanKepalaSekolahResource extends Resource
{
    protected static ?string $model = SambutanKepalaSekolah::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Manajemen Website';
    protected static ?string $navigationLabel = 'Sambutan Kepala Sekolah';
    protected static ?string $pluralModelLabel = 'Sambutan Kepala Sekolah';
    protected static ?string $modelLabel = 'Sambutan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Foto Kepala Sekolah')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->label('Upload Foto')
                            ->image()
                            ->directory('sambutan-kepala-sekolah')
                            ->preserveFilenames()
                            ->maxSize(2048) // 2MB
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1') // Foto persegi
                            ->imageResizeTargetWidth(400)
                            ->imageResizeTargetHeight(400)
                            ->helperText('Upload foto kepala sekolah. Ukuran maksimal 2MB. Format: JPG, PNG, JPEG.')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Isi Sambutan')
                    ->schema([
                        Forms\Components\RichEditor::make('sambutan')
                            ->label('Teks Sambutan')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'bulletList',
                                'orderedList',
                            ])
                            ->columnSpanFull()
                            ->helperText('Tulis sambutan dari kepala sekolah. Bisa menggunakan format teks sederhana.'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->size(50)
                    ->getStateUsing(fn ($record) => $record->foto_url)
                    ->defaultImageUrl(asset('images/default-profile.png')),
                
                Tables\Columns\TextColumn::make('sambutan')
                    ->label('Sambutan')
                    ->limit(100)
                    ->html()
                    ->tooltip(fn ($record) => strip_tags($record->sambutan))
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(null); // Nonaktifkan klik row untuk edit
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSambutanKepalaSekolahs::route('/'),
            'create' => Pages\CreateSambutanKepalaSekolah::route('/create'),
            'edit' => Pages\EditSambutanKepalaSekolah::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                \Illuminate\Database\Eloquent\SoftDeletingScope::class,
            ]);
    }
}