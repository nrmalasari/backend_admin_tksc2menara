<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistPendaftarResource\Pages;
use App\Models\RegistPendaftar;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class RegistPendaftarResource extends Resource
{
    protected static ?string $model = RegistPendaftar::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationLabel = 'Data Pendaftar';
    protected static ?string $modelLabel = 'Pendaftar';
    protected static ?string $pluralModelLabel = 'Data Pendaftar';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pendaftar')
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->disabled()
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->disabled()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Forms\Components\Section::make('Keamanan & Enkripsi')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Password (Terenkripsi AES-256-CBC)')
                            ->disabled()
                            ->columnSpan(2)
                            ->helperText('Password dalam bentuk terenkripsi menggunakan AES-256-CBC')
                            ->formatStateUsing(fn ($record) => 
                                $record ? substr($record->password, 0, 50) . '...' : ''
                            ),
                        
                        Forms\Components\TextInput::make('decrypted_password')
                            ->label('Password Asli (Terdekripsi)')
                            ->disabled()
                            ->columnSpan(2)
                            ->helperText('Password asli yang sudah didekripsi - HANYA UNTUK ADMIN')
                            ->default(fn ($record) => 
                                $record ? $record->decrypted_password : 'Tidak tersedia'
                            ),
                        
                        Forms\Components\Placeholder::make('encryption_info')
                            ->label('Informasi Enkripsi')
                            ->content('Password dienkripsi menggunakan AES-256-CBC dengan kunci dari APP_KEY Laravel')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Forms\Components\Section::make('Informasi Registrasi')
                    ->schema([
                        Forms\Components\TextInput::make('registration_ip')
                            ->label('IP Registrasi')
                            ->disabled()
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('user_agent')
                            ->label('Browser/Device')
                            ->disabled()
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('created_at')
                            ->label('Tanggal Daftar')
                            ->disabled()
                            ->formatStateUsing(fn ($record) => 
                                $record ? $record->created_at->format('d/m/Y H:i:s') : ''
                            )
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->collapsible(),
                
                Forms\Components\Section::make('Data Sensitif Terenkripsi')
                    ->schema([
                        Forms\Components\Textarea::make('encrypted_data')
                            ->label('Data Sensitif')
                            ->disabled()
                            ->rows(8)
                            ->columnSpanFull()
                            ->formatStateUsing(function ($record) {
                                if ($record && $record->encrypted_data) {
                                    $data = $record->encrypted_data;
                                    if (is_string($data) && json_decode($data) !== null) {
                                        $data = json_decode($data, true);
                                    }
                                    return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                                }
                                return 'Tidak ada data';
                            }),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('decrypted_password')
                    ->label('Password Asli')
                    ->limit(15)
                    ->tooltip(fn ($record) => $record->decrypted_password)
                    ->html()
                    ->formatStateUsing(fn ($record) => 
                        $record->decrypted_password 
                            ? '<span class="font-mono bg-green-50 text-green-700 px-2 py-1 rounded text-xs">' . 
                              $record->decrypted_password . '</span>'
                            : '<span class="text-gray-400">-</span>'
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('password')
                    ->label('Password (Enkripsi)')
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->password)
                    ->html()
                    ->formatStateUsing(fn ($record) => 
                        '<span class="font-mono text-gray-500 text-xs">' . 
                        substr($record->password, 0, 20) . '...</span>'
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('registration_ip')
                    ->label('IP')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'],
                                fn ($query, $date) => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when($data['created_until'],
                                fn ($query, $date) => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('Belum ada data pendaftar')
            ->emptyStateDescription('Data pendaftar akan muncul di sini setelah registrasi dari website PPDB.');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistPendaftars::route('/'),
            'view' => Pages\ViewRegistPendaftar::route('/{record}'),
        ];
    }
}