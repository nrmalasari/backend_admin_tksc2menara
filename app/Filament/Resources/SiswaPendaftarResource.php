<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaPendaftarResource\Pages;
use App\Models\SiswaPendaftar;
use App\Models\TahunAjaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SiswaPendaftarResource extends Resource
{
    protected static ?string $model = SiswaPendaftar::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?string $navigationLabel = 'Siswa Pendaftar';
    
    // Label untuk singular dan plural
    protected static ?string $pluralModelLabel = 'Data Siswa Pendaftar';
    protected static ?string $modelLabel = 'Siswa Pendaftar';
    
    protected static ?int $navigationSort = 1;

    // Untuk breadcrumb
    public static function getBreadcrumb(): string
    {
        return 'Siswa Pendaftar';
    }
    
    // Untuk label plural
    public static function getPluralModelLabel(): string
    {
        return 'Data Siswa Pendaftar';
    }
    
    // Untuk label singular
    public static function getModelLabel(): string
    {
        return 'Siswa Pendaftar';
    }
    
    // Untuk judul navigasi
    public static function getNavigationLabel(): string
    {
        return 'Siswa Pendaftar';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pendaftaran')
                    ->schema([
                        Forms\Components\Select::make('regist_pendaftar_id')
                            ->label('ID Pendaftar')
                            ->relationship('registPendaftar', 'username')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        // Tambahkan field tahun_ajaran_id di form
                        Forms\Components\Select::make('tahun_ajaran_id')
                            ->label('Tahun Ajaran')
                            ->relationship('tahunAjaran', 'nama_tahun_ajaran')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Forms\Components\Select::make('status')
                            ->label('Status Pendaftaran')
                            ->options([
                                'menunggu' => 'Menunggu Verifikasi',
                                'diproses' => 'Sedang Diproses',
                                'diverifikasi' => 'Terverifikasi',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('menunggu')
                            ->required(),
                    ])->columns(3), // Ubah menjadi 3 kolom untuk menampung field baru
                
                Forms\Components\Section::make('A. Data Anak')
                    ->schema([
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('nik_decrypted')
                            ->label('NIK')
                            ->required()
                            ->maxLength(20)
                            ->helperText('NIK akan dienkripsi secara otomatis'),
                        
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->required()
                            ->maxLength(100),
                        
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                        
                        Forms\Components\Select::make('agama')
                            ->label('Agama')
                            ->options([
                                'islam' => 'Islam',
                                'kristen' => 'Kristen',
                                'katolik' => 'Katolik',
                                'hindu' => 'Hindu',
                                'buddha' => 'Buddha',
                                'konghucu' => 'Konghucu',
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'laki-laki' => 'Laki-laki',
                                'perempuan' => 'Perempuan',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('usia')
                            ->label('Usia')
                            ->numeric()
                            ->required()
                            ->minValue(0),
                        
                        Forms\Components\Textarea::make('alamat_jalan')
                            ->label('Alamat Jalan')
                            ->required()
                            ->rows(2),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('rt')
                                    ->label('RT')
                                    ->required()
                                    ->maxLength(5),
                                
                                Forms\Components\TextInput::make('rw')
                                    ->label('RW')
                                    ->required()
                                    ->maxLength(5),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kelurahan')
                                    ->label('Kelurahan')
                                    ->required()
                                    ->maxLength(100),
                                
                                Forms\Components\TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->required()
                                    ->maxLength(100),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kota')
                                    ->label('Kota')
                                    ->required()
                                    ->maxLength(100),
                                
                                Forms\Components\TextInput::make('kode_pos')
                                    ->label('Kode Pos')
                                    ->required()
                                    ->maxLength(10),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('tinggi_badan')
                                    ->label('Tinggi Badan (cm)')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0),
                                
                                Forms\Components\TextInput::make('berat_badan')
                                    ->label('Berat Badan (kg)')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0),
                            ]),
                        
                        Forms\Components\TextInput::make('jumlah_saudara')
                            ->label('Jumlah Saudara Kandung')
                            ->numeric()
                            ->required()
                            ->minValue(0),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('jarak_sekolah')
                                    ->label('Jarak ke Sekolah (km)')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->step(0.1),
                                
                                Forms\Components\TextInput::make('waktu_tempuh')
                                    ->label('Waktu Tempuh (menit)')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0),
                            ]),
                    ])->columns(2),
                
                Forms\Components\Section::make('B. Data Orang Tua/Wali')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_ayah')
                                    ->label('Nama Ayah')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('nama_ibu')
                                    ->label('Nama Ibu')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nik_ayah_decrypted')
                                    ->label('NIK Ayah')
                                    ->required()
                                    ->maxLength(20)
                                    ->helperText('NIK akan dienkripsi secara otomatis'),
                                
                                Forms\Components\TextInput::make('nik_ibu_decrypted')
                                    ->label('NIK Ibu')
                                    ->required()
                                    ->maxLength(20)
                                    ->helperText('NIK akan dienkripsi secara otomatis'),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('tempat_lahir_ayah')
                                    ->label('Tempat Lahir Ayah')
                                    ->required()
                                    ->maxLength(100),
                                
                                Forms\Components\TextInput::make('tempat_lahir_ibu')
                                    ->label('Tempat Lahir Ibu')
                                    ->required()
                                    ->maxLength(100),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_lahir_ayah')
                                    ->label('Tanggal Lahir Ayah')
                                    ->required(),
                                
                                Forms\Components\DatePicker::make('tanggal_lahir_ibu')
                                    ->label('Tanggal Lahir Ibu')
                                    ->required(),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('pendidikan_ayah')
                                    ->label('Pendidikan Ayah')
                                    ->options([
                                        'sd' => 'SD',
                                        'smp' => 'SMP',
                                        'sma' => 'SMA',
                                        'd3' => 'D3',
                                        's1' => 'S1',
                                        's2' => 'S2',
                                        's3' => 'S3',
                                    ])
                                    ->required(),
                                
                                Forms\Components\Select::make('pendidikan_ibu')
                                    ->label('Pendidikan Ibu')
                                    ->options([
                                        'sd' => 'SD',
                                        'smp' => 'SMP',
                                        'sma' => 'SMA',
                                        'd3' => 'D3',
                                        's1' => 'S1',
                                        's2' => 'S2',
                                        's3' => 'S3',
                                    ])
                                    ->required(),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('pekerjaan_ayah')
                                    ->label('Pekerjaan Ayah')
                                    ->required()
                                    ->maxLength(100),
                                
                                Forms\Components\TextInput::make('pekerjaan_ibu')
                                    ->label('Pekerjaan Ibu')
                                    ->required()
                                    ->maxLength(100),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Textarea::make('alamat_ayah')
                                    ->label('Alamat Ayah')
                                    ->required()
                                    ->rows(2),
                                
                                Forms\Components\Textarea::make('alamat_ibu')
                                    ->label('Alamat Ibu')
                                    ->required()
                                    ->rows(2),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('no_telp_decrypted')
                                    ->label('No. Telpon/HP')
                                    ->required()
                                    ->maxLength(20)
                                    ->helperText('No. Telpon akan dienkripsi secara otomatis'),
                                
                                Forms\Components\TextInput::make('penghasilan_decrypted')
                                    ->label('Penghasilan Bulanan')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->helperText('Penghasilan akan dienkripsi secara otomatis'),
                            ]),
                    ])->columns(2),
                
                Forms\Components\Section::make('C. Dokumen Pendukung')
                    ->schema([
                        Forms\Components\FileUpload::make('akte_kelahiran_path')
                            ->label('Akte Kelahiran')
                            ->directory('siswa/dokumen')
                            ->disk('public')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'])
                            ->maxSize(2048)
                            ->downloadable()
                            ->openable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 
                                    'akte_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                            ),
                        
                        Forms\Components\FileUpload::make('kartu_keluarga_path')
                            ->label('Kartu Keluarga')
                            ->directory('siswa/dokumen')
                            ->disk('public')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'])
                            ->maxSize(2048)
                            ->downloadable()
                            ->openable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 
                                    'kk_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                            ),
                        
                        Forms\Components\FileUpload::make('kia_path')
                            ->label('KIA')
                            ->directory('siswa/dokumen')
                            ->disk('public')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'])
                            ->maxSize(2048)
                            ->downloadable()
                            ->openable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 
                                    'kia_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                            ),
                        
                        Forms\Components\FileUpload::make('bpjs_path')
                            ->label('BPJS')
                            ->directory('siswa/dokumen')
                            ->disk('public')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'])
                            ->maxSize(2048)
                            ->downloadable()
                            ->openable()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 
                                    'bpjs_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                            ),
                    ])->columns(2),
                
                Forms\Components\Section::make('Catatan Admin')
                    ->schema([
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan')
                            ->rows(3)
                            ->placeholder('Masukkan catatan untuk pendaftar...')
                            ->helperText('Catatan ini untuk internal admin'),
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
                
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('nik_decrypted')
                    ->label('NIK')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('NIK disalin ke clipboard')
                    ->copyMessageDuration(1500),
                
                // Tambahkan kolom tahun ajaran
                Tables\Columns\TextColumn::make('tahunAjaran.nama_tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('agama')
                    ->label('Agama')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('usia')
                    ->label('Usia')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('nama_ayah')
                    ->label('Nama Ayah')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('nama_ibu')
                    ->label('Nama Ibu')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('no_telp_decrypted')
                    ->label('No. Telpon')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('formatted_penghasilan')
                    ->label('Penghasilan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'menunggu' => 'warning',
                        'diproses' => 'info',
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
                
                Tables\Columns\ImageColumn::make('akte_kelahiran_url')
                    ->label('Akte')
                    ->getStateUsing(fn ($record) => $record->akte_kelahiran_url)
                    ->height(40)
                    ->width(40)
                    ->grow(false)
                    ->defaultImageUrl(asset('images/default-document.png'))
                    ->extraImgAttributes(['class' => 'object-cover rounded'])
                    ->url(fn ($record) => $record->akte_kelahiran_url)
                    ->openUrlInNewTab(),
                
                Tables\Columns\ImageColumn::make('kartu_keluarga_url')
                    ->label('KK')
                    ->getStateUsing(fn ($record) => $record->kartu_keluarga_url)
                    ->height(40)
                    ->width(40)
                    ->grow(false)
                    ->defaultImageUrl(asset('images/default-document.png'))
                    ->extraImgAttributes(['class' => 'object-cover rounded'])
                    ->url(fn ($record) => $record->kartu_keluarga_url)
                    ->openUrlInNewTab(),
                
                Tables\Columns\ImageColumn::make('kia_url')
                    ->label('KIA')
                    ->getStateUsing(fn ($record) => $record->kia_url)
                    ->height(40)
                    ->width(40)
                    ->grow(false)
                    ->defaultImageUrl(asset('images/default-document.png'))
                    ->extraImgAttributes(['class' => 'object-cover rounded'])
                    ->url(fn ($record) => $record->kia_url)
                    ->openUrlInNewTab(),
                
                Tables\Columns\ImageColumn::make('bpjs_url')
                    ->label('BPJS')
                    ->getStateUsing(fn ($record) => $record->bpjs_url)
                    ->height(40)
                    ->width(40)
                    ->grow(false)
                    ->defaultImageUrl(asset('images/default-document.png'))
                    ->extraImgAttributes(['class' => 'object-cover rounded'])
                    ->url(fn ($record) => $record->bpjs_url)
                    ->openUrlInNewTab(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tambahkan filter berdasarkan tahun ajaran
                Tables\Filters\SelectFilter::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'nama_tahun_ajaran')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        'diverifikasi' => 'Terverifikasi',
                        'ditolak' => 'Ditolak',
                    ]),
                
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'laki-laki' => 'Laki-laki',
                        'perempuan' => 'Perempuan',
                    ]),
                
                Tables\Filters\SelectFilter::make('agama')
                    ->label('Agama')
                    ->options([
                        'islam' => 'Islam',
                        'kristen' => 'Kristen',
                        'katolik' => 'Katolik',
                        'hindu' => 'Hindu',
                        'buddha' => 'Buddha',
                        'konghucu' => 'Konghucu',
                    ]),
                
                Tables\Filters\Filter::make('usia')
                    ->form([
                        Forms\Components\TextInput::make('usia_dari')
                            ->label('Usia Dari')
                            ->numeric()
                            ->placeholder('Minimal usia'),
                        Forms\Components\TextInput::make('usia_sampai')
                            ->label('Usia Sampai')
                            ->numeric()
                            ->placeholder('Maksimal usia'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['usia_dari'],
                                fn (Builder $query, $usia): Builder => $query->where('usia', '>=', $usia),
                            )
                            ->when(
                                $data['usia_sampai'],
                                fn (Builder $query, $usia): Builder => $query->where('usia', '<=', $usia),
                            );
                    }),
                
                Tables\Filters\Filter::make('tanggal_lahir')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_lahir', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_lahir', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('viewDocuments')
                        ->label('Lihat Dokumen')
                        ->icon('heroicon-o-folder-open')
                        ->color('info')
                        ->modalHeading(fn (SiswaPendaftar $record) => "Dokumen Pendaftaran: {$record->nama_lengkap}")
                        ->modalContent(function (SiswaPendaftar $record): View {
                            return view('filament.components.documents-modal', [
                                'record' => $record,
                                'documents' => [
                                    [
                                        'name' => 'Akte Kelahiran',
                                        'url' => $record->akte_kelahiran_url,
                                        'exists' => $record->akte_kelahiran_exists,
                                        'path' => $record->akte_kelahiran_path,
                                    ],
                                    [
                                        'name' => 'Kartu Keluarga',
                                        'url' => $record->kartu_keluarga_url,
                                        'exists' => $record->kartu_keluarga_exists,
                                        'path' => $record->kartu_keluarga_path,
                                    ],
                                    [
                                        'name' => 'KIA',
                                        'url' => $record->kia_url,
                                        'exists' => $record->kia_exists,
                                        'path' => $record->kia_path,
                                    ],
                                    [
                                        'name' => 'BPJS',
                                        'url' => $record->bpjs_url,
                                        'exists' => $record->bpjs_exists,
                                        'path' => $record->bpjs_path,
                                    ],
                                ]
                            ]);
                        })
                        ->modalWidth('5xl')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup')
                        ->visible(fn (SiswaPendaftar $record) => 
                            $record->akte_kelahiran_exists || 
                            $record->kartu_keluarga_exists || 
                            $record->kia_exists || 
                            $record->bpjs_exists
                        ),
                    
                    Tables\Actions\Action::make('verify')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (SiswaPendaftar $record) {
                            $record->status = 'diverifikasi';
                            $record->catatan_admin = 'Pendaftaran telah diverifikasi oleh admin.';
                            $record->save();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi Pendaftaran')
                        ->modalDescription('Apakah Anda yakin ingin memverifikasi pendaftaran ini?')
                        ->visible(fn (SiswaPendaftar $record) => $record->status !== 'diverifikasi'),
                    
                    Tables\Actions\Action::make('process')
                        ->label('Proses')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(function (SiswaPendaftar $record) {
                            $record->status = 'diproses';
                            $record->catatan_admin = 'Pendaftaran sedang diproses oleh admin.';
                            $record->save();
                        })
                        ->requiresConfirmation()
                        ->visible(fn (SiswaPendaftar $record) => $record->status === 'menunggu'),
                    
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
                        ->action(function (SiswaPendaftar $record, array $data) {
                            $record->status = 'ditolak';
                            $record->catatan_admin = $data['catatan_admin'];
                            $record->save();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Pendaftaran')
                        ->modalDescription('Apakah Anda yakin ingin menolak pendaftaran ini?')
                        ->visible(fn (SiswaPendaftar $record) => $record->status !== 'ditolak'),
                    
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('verifySelected')
                        ->label('Verifikasi Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->status = 'diverifikasi';
                                $record->catatan_admin = 'Pendaftaran telah diverifikasi oleh admin.';
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),
                    
                    Tables\Actions\BulkAction::make('rejectSelected')
                        ->label('Tolak Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Textarea::make('catatan_admin')
                                ->label('Alasan Penolakan')
                                ->required()
                                ->placeholder('Masukkan alasan penolakan untuk semua yang dipilih...'),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->status = 'ditolak';
                                $record->catatan_admin = $data['catatan_admin'];
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),
                    
                    Tables\Actions\BulkAction::make('processSelected')
                        ->label('Proses Terpilih')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->status = 'diproses';
                                $record->catatan_admin = 'Pendaftaran sedang diproses oleh admin.';
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswaPendaftars::route('/'),
            'create' => Pages\CreateSiswaPendaftar::route('/create'),
            'view' => Pages\ViewSiswaPendaftar::route('/{record}'),
            'edit' => Pages\EditSiswaPendaftar::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'menunggu')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}