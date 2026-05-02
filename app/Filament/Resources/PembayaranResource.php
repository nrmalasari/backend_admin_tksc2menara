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
                                if ($state === 'diverifikasi' && $get('metode_pembayaran') === 'manual' && !$get('bukti_pembayaran')) {
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
                
                // PERBAIKAN: Hapus searchable() dari no_rek_decrypted karena ini field virtual
                Tables\Columns\TextColumn::make('no_rek_decrypted')
                    ->label('No. Rekening')
                    ->sortable() // Hanya sortable, TIDAK searchable
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
                
                Tables\Actions\Action::make('viewProof')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Bukti Pembayaran')
                    ->modalDescription(function (Pembayaran $record) {
                        if (empty($record->bukti_pembayaran)) {
                            return '❌ **Bukti pembayaran belum diupload.**';
                        }
                        
                        return '✅ **Bukti pembayaran tersedia.**';
                    })
                    ->modalContent(function (Pembayaran $record) {
                        if (empty($record->bukti_pembayaran)) {
                            $html = <<<HTML
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <span class="text-yellow-800 font-medium">Belum ada bukti pembayaran</span>
                                </div>
                                <p class="mt-2 text-yellow-700">Silakan upload bukti pembayaran terlebih dahulu menggunakan tombol "Upload Bukti".</p>
                            </div>
                            HTML;
                            
                            return new \Illuminate\Support\HtmlString($html);
                        }
                        
                        $html = <<<HTML
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-center">
                                <a href="{$record->bukti_pembayaran_url}" 
                                   target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Bukti Pembayaran
                                </a>
                            </div>
                            <p class="mt-3 text-sm text-gray-600 text-center">File akan dibuka di tab baru.</p>
                        </div>
                        HTML;
                        
                        return new \Illuminate\Support\HtmlString($html);
                    })
                    ->modalWidth('lg')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->visible(fn (Pembayaran $record) => true),
                
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
                                
                                if ($record->metode_pembayaran === 'manual' && empty($record->bukti_pembayaran)) {
                                    $record->bukti_pembayaran = 'default-payment.png';
                                }
                                
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            // PERBAIKAN: Tambahkan ini untuk handle global search
            ->modifyQueryUsing(function (Builder $query) {
                // Filament secara otomatis menambahkan search pada kolom yang memiliki ->searchable()
                // Untuk no_rek_decrypted, kita tidak bisa search karena field virtual
                // Jadi biarkan Filament handle yang lain secara default
            });
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