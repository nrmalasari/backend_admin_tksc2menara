<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Contracts\View\View;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $pluralModelLabel = 'Data Pembayaran';
    protected static ?string $modelLabel = 'Pembayaran';
    
    protected static ?int $navigationSort = 1;
    public static function getBreadcrumb(): string
    {
        return 'Pembayaran';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Data Pembayaran';
    }

    public static function getModelLabel(): string
    {
        return 'Pembayaran';
    }

    public static function getNavigationLabel(): string
    {
        return 'Pembayaran';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('regist_pendaftar_id')
                            ->label('Pendaftar')
                            ->relationship('registPendaftar', 'username')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Forms\Components\DatePicker::make('tanggal_pembayaran')
                            ->label('Tanggal Pembayaran')
                            ->default(now())
                            ->required(),
                        
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Pembayar')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'transfer' => 'Transfer Bank',
                                'manual' => 'Manual (Bayar di Kantor)',
                            ])
                            ->required()
                            ->reactive(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Detail Bank')
                    ->schema([
                        Forms\Components\TextInput::make('nama_bank')
                            ->label('Nama Bank')
                            ->required()
                            ->maxLength(100)
                            ->hidden(fn (Forms\Get $get): bool => $get('metode_pembayaran') !== 'transfer'),
                        
                        Forms\Components\TextInput::make('no_rek')
                            ->label('Nomor Rekening')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Nomor rekening akan dienkripsi secara otomatis')
                            ->hidden(fn (Forms\Get $get): bool => $get('metode_pembayaran') !== 'transfer'),
                    ])->hidden(fn (Forms\Get $get): bool => $get('metode_pembayaran') !== 'transfer'),
                
                // **PERBAIKAN: Bagian Jumlah Pembayaran dipisah**
                Forms\Components\Section::make('Jumlah Pembayaran')
                    ->schema([
                        Forms\Components\TextInput::make('jumlah_pembayaran')
                            ->label('Jumlah Pembayaran')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->rules([
                                'numeric',
                                'min:1',
                            ])
                            ->helperText(function (Forms\Get $get) {
                                return $get('metode_pembayaran') === 'manual' 
                                    ? 'Masukkan jumlah yang dibayarkan di kantor'
                                    : 'Masukkan jumlah transfer';
                            }),
                    ]),
                
                // **PERBAIKAN: Bagian Bukti Pembayaran - Admin bisa upload untuk semua metode**
                Forms\Components\Section::make('Bukti Pembayaran')
                    ->schema([
                        Forms\Components\FileUpload::make('bukti_pembayaran')
                            ->label('Bukti Pembayaran')
                            ->directory('bukti-pembayaran')
                            ->disk('public')
                            ->image()
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'application/pdf'])
                            ->hint('Maksimal 5MB, format: JPG, PNG, GIF, WebP, PDF')
                            ->helperText(function (Forms\Get $get) {
                                if ($get('metode_pembayaran') === 'manual') {
                                    return 'Upload bukti pembayaran setelah siswa membayar di kantor';
                                } else {
                                    return 'Upload bukti transfer dari siswa';
                                }
                            })
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 
                                    'bukti_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                            )
                            ->uploadingMessage('Sedang mengunggah file...')
                            ->uploadProgressIndicatorPosition('right')
                            ->hintIcon('heroicon-o-information-circle'),
                        
                        // Tampilkan info khusus untuk metode manual
                        Forms\Components\Placeholder::make('manual_info')
                            ->label('Catatan untuk Pembayaran Manual')
                            ->content('Untuk pembayaran manual, pastikan siswa telah membayar di kantor sebelum upload bukti pembayaran.')
                            ->hidden(fn (Forms\Get $get): bool => $get('metode_pembayaran') !== 'manual'),
                    ]),
                
                Forms\Components\Section::make('Status dan Verifikasi')
                    ->schema([
                        Forms\Components\Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->options([
                                'menunggu' => 'Menunggu Pembayaran',
                                'diproses' => 'Sedang Diproses',
                                'diverifikasi' => 'Terverifikasi',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('menunggu')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                // Jika status diverifikasi dan metode manual, otomatis upload bukti default
                                if ($state === 'diverifikasi' && $get('metode_pembayaran') === 'manual' && !$get('bukti_pembayaran')) {
                                    // Set bukti default untuk pembayaran manual yang diverifikasi
                                    $set('bukti_pembayaran', 'default-payment.png');
                                }
                            }),
                        
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->placeholder('Masukkan catatan untuk pendaftar...')
                            ->helperText('Catatan ini akan ditampilkan kepada pendaftar'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registPendaftar.username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Pembayar')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->formatStateUsing(fn ($state) => $state === 'transfer' ? 'Transfer Bank' : 'Manual')
                    ->badge()
                    ->color(fn ($state) => $state === 'transfer' ? 'info' : 'warning'),
                
                Tables\Columns\TextColumn::make('nama_bank')
                    ->label('Bank')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state, Pembayaran $record) => 
                        $record->metode_pembayaran === 'manual' ? 'Pembayaran di Kantor' : $state
                    ),
                
                Tables\Columns\TextColumn::make('no_rek_decrypted')
                    ->label('No. Rekening')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state, Pembayaran $record) => 
                        $record->metode_pembayaran === 'manual' ? '-' : ($state ?? 'Tidak ada')
                    ),
                
                Tables\Columns\TextColumn::make('jumlah_pembayaran')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable()
                    ->formatStateUsing(fn ($state, Pembayaran $record) => 
                        'Rp ' . number_format($state, 0, ',', '.')
                    ),
                
                Tables\Columns\TextColumn::make('tanggal_pembayaran')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'menunggu' => 'gray',
                        'diproses' => 'warning',
                        'diverifikasi' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        'diverifikasi' => 'Terverifikasi',
                        'ditolak' => 'Ditolak',
                        default => $state,
                    }),
                
                Tables\Columns\ImageColumn::make('bukti_pembayaran_url')
                    ->label('Bukti')
                    ->getStateUsing(fn ($record) => $record->bukti_pembayaran_url)
                    ->height(50)
                    ->width(50)
                    ->grow(false)
                    ->defaultImageUrl(asset('images/default-payment.png'))
                    ->extraImgAttributes(['class' => 'object-cover rounded'])
                    ->url(fn (Pembayaran $record) => $record->bukti_pembayaran_url)
                    ->openUrlInNewTab(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->label('Status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        'diverifikasi' => 'Terverifikasi',
                        'ditolak' => 'Ditolak',
                    ]),
                
                Tables\Filters\SelectFilter::make('metode_pembayaran')
                    ->label('Metode')
                    ->options([
                        'transfer' => 'Transfer Bank',
                        'manual' => 'Manual',
                    ]),
                
                // **PERBAIKAN: Typo diperbaiki di sini**
                Tables\Filters\Filter::make('tanggal_pembayaran')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_pembayaran', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_pembayaran', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                // Action untuk melihat bukti
                Tables\Actions\Action::make('viewProof')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Bukti Pembayaran')
                    ->modalContent(function (Pembayaran $record): View {
                        return view('filament.components.proof-modal', [
                            'imageUrl' => $record->bukti_pembayaran_url ?? asset('images/default-payment.png'),
                            'isImage' => in_array(pathinfo($record->bukti_pembayaran_url, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']),
                            'isManual' => $record->metode_pembayaran === 'manual',
                        ]);
                    })
                    ->modalWidth('4xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->visible(fn (Pembayaran $record) => !empty($record->bukti_pembayaran) || $record->metode_pembayaran === 'manual'),
                
                // Action untuk upload bukti manual
                Tables\Actions\Action::make('uploadProof')
                    ->label('Upload Bukti')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->color('primary')
                    ->form([
                        Forms\Components\FileUpload::make('bukti_pembayaran')
                            ->label('Bukti Pembayaran')
                            ->directory('bukti-pembayaran')
                            ->disk('public')
                            ->image()
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'application/pdf'])
                            ->hint('Maksimal 5MB, format: JPG, PNG, GIF, WebP, PDF')
                            ->required()
                            ->helperText('Upload bukti pembayaran yang telah diterima di kantor'),
                    ])
                    ->action(function (Pembayaran $record, array $data) {
                        $record->bukti_pembayaran = $data['bukti_pembayaran'];
                        
                        // Jika belum diverifikasi, otomatis update status
                        if ($record->status_pembayaran === 'menunggu') {
                            $record->status_pembayaran = 'diverifikasi';
                            $record->catatan_admin = 'Pembayaran manual telah diverifikasi oleh admin';
                        }
                        
                        $record->save();
                    })
                    ->visible(fn (Pembayaran $record) => 
                        $record->metode_pembayaran === 'manual' && empty($record->bukti_pembayaran)
                    ),
                
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Pembayaran $record) {
                        $record->status_pembayaran = 'diverifikasi';
                        
                        // Untuk pembayaran manual, set bukti default jika belum ada
                        if ($record->metode_pembayaran === 'manual' && empty($record->bukti_pembayaran)) {
                            $record->bukti_pembayaran = 'default-payment.png';
                            $record->catatan_admin = 'Pembayaran manual telah diverifikasi. Bukti pembayaran diterima di kantor.';
                        }
                        
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pembayaran')
                    ->modalDescription(function (Pembayaran $record) {
                        if ($record->metode_pembayaran === 'manual') {
                            return 'Apakah Anda yakin ingin memverifikasi pembayaran manual ini? Pastikan siswa telah membayar di kantor.';
                        }
                        return 'Apakah Anda yakin ingin memverifikasi pembayaran ini?';
                    })
                    ->visible(fn (Pembayaran $record) => $record->status_pembayaran !== 'diverifikasi'),
                
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Masukkan alasan penolakan...'),
                    ])
                    ->action(function (Pembayaran $record, array $data) {
                        $record->status_pembayaran = 'ditolak';
                        $record->catatan_admin = $data['catatan_admin'];
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pembayaran')
                    ->modalDescription('Apakah Anda yakin ingin menolak pembayaran ini?')
                    ->visible(fn (Pembayaran $record) => $record->status_pembayaran !== 'ditolak'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('verify')
                        ->label('Verifikasi Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->status_pembayaran = 'diverifikasi';
                                
                                // Untuk pembayaran manual, set bukti default jika belum ada
                                if ($record->metode_pembayaran === 'manual' && empty($record->bukti_pembayaran)) {
                                    $record->bukti_pembayaran = 'default-payment.png';
                                }
                                
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }

     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status_pembayaran', ['menunggu', 'diproses'])->count();
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
    
    public static function getNavigationBadgeTooltip(): ?string
    {
        $count = static::getModel()::whereIn('status_pembayaran', ['menunggu', 'diproses'])->count();
        return "{$count} pembayaran menunggu verifikasi";
    }
}