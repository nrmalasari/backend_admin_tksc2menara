<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Models\Siswa;
use App\Models\SiswaPendaftar;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?string $navigationLabel = 'Siswa Terdaftar';
    protected static ?string $pluralModelLabel = 'Data Siswa Terdaftar';
    protected static ?string $modelLabel = 'Siswa Terdaftar';
    protected static ?int $navigationSort = 2;

    public static function getBreadcrumb(): string
    {
        return 'Siswa Terdaftar';
    }

    // Method untuk mendapatkan urutan pendaftaran
    private static function getUrutanPendaftaran($tanggalMasuk, $tahunAjaranId = null)
    {
        $date = Carbon::parse($tanggalMasuk);
        
        // Jika ada tahun ajaran, hitung berdasarkan tahun ajaran
        if ($tahunAjaranId) {
            $totalSiswaTahunAjaran = Siswa::where('tahun_ajaran_id', $tahunAjaranId)->count();
            return $totalSiswaTahunAjaran + 1;
        }
        
        // Fallback: hitung berdasarkan bulan
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();
        
        $totalSiswaSameMonth = Siswa::whereBetween('tanggal_masuk', [$startDate, $endDate])->count();
        return $totalSiswaSameMonth + 1;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pendaftaran')
                    ->schema([
                        Forms\Components\Select::make('siswa_pendaftar_id')
                            ->label('Data Pendaftar')
                            ->options(
                                SiswaPendaftar::where('status', 'diverifikasi')
                                    ->whereDoesntHave('siswa')
                                    ->get()
                                    ->mapWithKeys(function ($item) {
                                        return [$item->id => $item->nama_lengkap . ' (NIK: ' . $item->nik_decrypted . ')'];
                                    })
                            )
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih dari siswa pendaftar yang sudah diverifikasi')
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $pendaftar = SiswaPendaftar::find($state);
                                    if ($pendaftar) {
                                        // Auto-fill data dari pendaftar
                                        $set('nama_lengkap', $pendaftar->nama_lengkap);
                                        $set('asal_sekolah', $pendaftar->asal_sekolah ?? null);
                                        
                                        // Set tahun ajaran otomatis dari data pendaftar
                                        if ($pendaftar->tahun_ajaran_id) {
                                            $set('tahun_ajaran_id', $pendaftar->tahun_ajaran_id);
                                        }
                                        
                                        // Set formulir otomatis dari akte kelahiran
                                        if ($pendaftar->akte_kelahiran_path) {
                                            $set('formulir_path', [$pendaftar->akte_kelahiran_path]);
                                        }
                                    }
                                }
                            }),
                        
                        Forms\Components\Select::make('tahun_ajaran_id')
                            ->label('Tahun Ajaran')
                            ->relationship('tahunAjaran', 'nama_tahun_ajaran')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Otomatis terisi jika memilih dari data pendaftar')
                            ->disabled(fn (callable $get) => !empty($get('siswa_pendaftar_id'))),
                        
                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->relationship('kelas', 'nama_kelas')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Kelas dapat diisi nanti'),
                    ])->columns(3),
                
                Forms\Components\Section::make('Data Siswa')
                    ->schema([
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nis')
                                    ->label('NIS')
                                    ->unique(ignoreRecord: true)
                                    ->nullable()
                                    ->helperText('Akan digenerate otomatis berdasarkan tanggal masuk'),
                                
                                Forms\Components\TextInput::make('nsin')
                                    ->label('NSIN')
                                    ->unique(ignoreRecord: true)
                                    ->nullable(),
                            ]),
                        
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif' => 'Aktif',
                                'pindah' => 'Pindah',
                                'lulus' => 'Lulus',
                            ])
                            ->default('aktif')
                            ->required()
                            ->live(),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_masuk')
                                    ->label('Tanggal Masuk')
                                    ->default(now())
                                    ->required()
                                    ->live(),
                                
                                Forms\Components\DatePicker::make('tanggal_keluar')
                                    ->label('Tanggal Keluar')
                                    ->nullable()
                                    ->visible(fn (callable $get) => in_array($get('status'), ['pindah', 'lulus'])),
                            ]),
                        
                        Forms\Components\TextInput::make('asal_sekolah')
                            ->label('Asal Sekolah')
                            ->nullable()
                            ->maxLength(255)
                            ->helperText('Isi jika siswa pindahan atau transfer dari sekolah lain'),
                        
                        Forms\Components\Textarea::make('alasan_keluar')
                            ->label('Alasan Keluar')
                            ->nullable()
                            ->rows(2)
                            ->visible(fn (callable $get) => in_array($get('status'), ['pindah', 'lulus'])),
                    ])->columns(2),
                
                Forms\Components\Section::make('Dokumen & Foto')
                    ->schema([
                        Forms\Components\FileUpload::make('foto_path')
                            ->label('Foto Siswa')
                            ->directory('siswa/foto')
                            ->disk('public')
                            ->image()
                            ->maxSize(2048)
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('300')
                            ->imageResizeTargetHeight('300')
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 
                                    'foto_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                            )
                            ->multiple(false)
                            ->maxFiles(1)
                            ->panelLayout('compact')
                            ->openable()
                            ->downloadable()
                            ->previewable(true)
                            ->panelAspectRatio('1:1')
                            ->loadingIndicatorPosition('left')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'])
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                $filename = 'foto_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                                $path = $file->storeAs('siswa/foto', $filename, 'public');
                                return $path;
                            }),
                        
                        // Formulir akan otomatis diambil dari pendaftar, tetapi bisa di-upload manual jika diperlukan
                        Forms\Components\FileUpload::make('formulir_path')
                            ->label('Formulir Pendaftaran (Opsional)')
                            ->helperText('Formulir akan otomatis diambil dari data pendaftar. Upload manual hanya jika diperlukan.')
                            ->directory('siswa/formulir')
                            ->disk('public')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(5120)
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 
                                    'formulir_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension()
                            )
                            ->multiple(false)
                            ->maxFiles(1)
                            ->panelLayout('compact')
                            ->openable()
                            ->downloadable()
                            ->previewable(false)
                            ->loadingIndicatorPosition('left')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left')
                            ->dehydrated(true)
                            ->hidden(fn (callable $get) => !empty($get('siswa_pendaftar_id')))
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                $filename = 'formulir_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                                $path = $file->storeAs('siswa/formulir', $filename, 'public');
                                return $path;
                            }),
                    ])->columns(2)
                    ->hidden(fn (callable $get) => !empty($get('siswa_pendaftar_id')) && empty($get('formulir_path'))),
                
                // Section untuk menampilkan informasi lengkap dari siswa pendaftar yang dipilih
                Forms\Components\Section::make('Informasi Siswa dari Formulir')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                // Data Pribadi
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('data_pribadi_title')
                                            ->label('DATA PRIBADI')
                                            ->content('')
                                            ->extraAttributes(['class' => 'font-bold text-gray-800 text-sm uppercase']),
                                        
                                        Forms\Components\Placeholder::make('nama_lengkap_info')
                                            ->label('Nama Lengkap')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    return $pendaftar->nama_lengkap ?? '-';
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('nik_info')
                                            ->label('NIK')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    return $pendaftar->nik_decrypted ?? '-';
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('tempat_tanggal_lahir')
                                            ->label('TTL')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    if ($pendaftar) {
                                                        $tempatLahir = $pendaftar->tempat_lahir ?? '-';
                                                        $tanggalLahir = $pendaftar->tanggal_lahir ? 
                                                            $pendaftar->tanggal_lahir->format('d/m/Y') : '-';
                                                        return $tempatLahir . ', ' . $tanggalLahir;
                                                    }
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('jenis_kelamin')
                                            ->label('Jenis Kelamin')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    if ($pendaftar) {
                                                        return $pendaftar->jenis_kelamin === 'laki-laki' ? 'Laki-laki' : 
                                                               ($pendaftar->jenis_kelamin === 'perempuan' ? 'Perempuan' : '-');
                                                    }
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('tahun_ajaran_info')
                                            ->label('Tahun Ajaran (Pendaftaran)')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    if ($pendaftar && $pendaftar->tahunAjaran) {
                                                        return $pendaftar->tahunAjaran->nama_tahun_ajaran ?? '-';
                                                    }
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                    ])
                                    ->columnSpan(1),
                                
                                // Data Orang Tua
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('orang_tua_title')
                                            ->label('DATA ORANG TUA')
                                            ->content('')
                                            ->extraAttributes(['class' => 'font-bold text-gray-800 text-sm uppercase']),
                                        
                                        Forms\Components\Placeholder::make('nama_ayah')
                                            ->label('Nama Ayah')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    return $pendaftar->nama_ayah ?? '-';
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('nama_ibu')
                                            ->label('Nama Ibu')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    return $pendaftar->nama_ibu ?? '-';
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('pekerjaan_ayah')
                                            ->label('Pekerjaan Ayah')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    return $pendaftar->pekerjaan_ayah ?? '-';
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('pekerjaan_ibu')
                                            ->label('Pekerjaan Ibu')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    return $pendaftar->pekerjaan_ibu ?? '-';
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('telepon_ortu')
                                            ->label('Telepon Orang Tua')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    return $pendaftar->no_telp_decrypted ?? '-';
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                    ])
                                    ->columnSpan(1),
                                
                                // Data Alamat & Lainnya
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('alamat_title')
                                            ->label('ALAMAT & LAINNYA')
                                            ->content('')
                                            ->extraAttributes(['class' => 'font-bold text-gray-800 text-sm uppercase']),
                                        
                                        Forms\Components\Placeholder::make('alamat_lengkap')
                                            ->label('Alamat Lengkap')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    if ($pendaftar) {
                                                        $alamat = $pendaftar->alamat_jalan ?? '';
                                                        $rt = $pendaftar->rt ? 'RT ' . $pendaftar->rt : '';
                                                        $rw = $pendaftar->rw ? 'RW ' . $pendaftar->rw : '';
                                                        $kelurahan = $pendaftar->kelurahan ?? '';
                                                        $kecamatan = $pendaftar->kecamatan ?? '';
                                                        $kota = $pendaftar->kota ?? '';
                                                        $kodePos = $pendaftar->kode_pos ?? '';
                                                        
                                                        $parts = array_filter([$alamat, $rt, $rw, $kelurahan, $kecamatan, $kota, $kodePos]);
                                                        return implode(', ', $parts);
                                                    }
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('asal_sekolah_info')
                                            ->label('Asal Sekolah')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    return $pendaftar->asal_sekolah ?? '-';
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('agama')
                                            ->label('Agama')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    if ($pendaftar) {
                                                        $agama = strtolower($pendaftar->agama ?? '');
                                                        return ucfirst($agama);
                                                    }
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        Forms\Components\Placeholder::make('usia')
                                            ->label('Usia')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    return $pendaftar->usia ? $pendaftar->usia . ' tahun' : '-';
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                        
                                        // Status Formulir dengan warna teks
                                        Forms\Components\Placeholder::make('status_formulir')
                                            ->label('Status Formulir')
                                            ->content(function (callable $get) {
                                                $pendaftarId = $get('siswa_pendaftar_id');
                                                if ($pendaftarId) {
                                                    $pendaftar = SiswaPendaftar::find($pendaftarId);
                                                    if ($pendaftar) {
                                                        $status = match($pendaftar->status) {
                                                            'diverifikasi' => 'Terverifikasi',
                                                            'diproses' => 'Diproses',
                                                            'menunggu' => 'Menunggu',
                                                            'ditolak' => 'Ditolak',
                                                            default => ucfirst($pendaftar->status),
                                                        };
                                                        
                                                        $color = match($pendaftar->status) {
                                                            'diverifikasi' => 'text-green-600',
                                                            'diproses' => 'text-blue-600',
                                                            'menunggu' => 'text-yellow-600',
                                                            'ditolak' => 'text-red-600',
                                                            default => 'text-gray-600',
                                                        };
                                                        
                                                        return new HtmlString(
                                                            '<span class="' . $color . ' font-medium">' . $status . '</span>'
                                                        );
                                                    }
                                                }
                                                return '-';
                                            })
                                            ->extraAttributes(['class' => 'text-sm']),
                                    ])
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->hidden(fn (callable $get) => empty($get('siswa_pendaftar_id')))
                    ->description('Data lengkap dari formulir pendaftaran siswa')
                    ->icon('heroicon-o-document-text'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_url')
                    ->label('Foto')
                    ->square()
                    ->defaultImageUrl(asset('images/default-avatar.png'))
                    ->extraImgAttributes(['class' => 'object-cover rounded'])
                    ->size(50),
                
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->placeholder('-')
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('nsin')
                    ->label('NSIN')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->placeholder('-')
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('tahunAjaran.nama_tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->sortable()
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'aktif' => 'success',
                        'pindah' => 'warning',
                        'lulus' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'aktif' => 'Aktif',
                        'pindah' => 'Pindah',
                        'lulus' => 'Lulus',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                // Tambahkan kolom untuk menunjukkan siswa dari pendaftaran atau manual
                Tables\Columns\IconColumn::make('siswa_pendaftar_id')
                    ->label('Sumber')
                    ->getStateUsing(fn ($record) => $record->siswa_pendaftar_id ? 'pendaftaran' : 'manual')
                    ->icon(fn ($state) => $state === 'pendaftaran' ? 'heroicon-o-document-text' : 'heroicon-o-user-plus')
                    ->color(fn ($state) => $state === 'pendaftaran' ? 'success' : 'gray')
                    ->tooltip(fn ($state) => $state === 'pendaftaran' ? 'Dari Pendaftaran' : 'Input Manual'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'nama_tahun_ajaran')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'pindah' => 'Pindah',
                        'lulus' => 'Lulus',
                    ]),
                
                Tables\Filters\Filter::make('tanggal_masuk')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_masuk', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_masuk', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['dari_tanggal'] && !$data['sampai_tanggal']) {
                            return null;
                        }
                        
                        $indicators = [];
                        if ($data['dari_tanggal']) {
                            $indicators[] = 'Dari: ' . $data['dari_tanggal'];
                        }
                        if ($data['sampai_tanggal']) {
                            $indicators[] = 'Sampai: ' . $data['sampai_tanggal'];
                        }
                        
                        return 'Tanggal Masuk: ' . implode(' - ', $indicators);
                    }),
                
                Tables\Filters\Filter::make('tanpa_kelas')
                    ->label('Tanpa Kelas')
                    ->query(fn (Builder $query): Builder => $query->whereNull('kelas_id'))
                    ->toggle(),
                
                // Filter berdasarkan sumber data
                Tables\Filters\SelectFilter::make('sumber_data')
                    ->label('Sumber Data')
                    ->options([
                        'pendaftaran' => 'Dari Pendaftaran',
                        'manual' => 'Input Manual',
                    ])
                    ->query(function (Builder $query, $state) {
                        if ($state['value'] === 'pendaftaran') {
                            return $query->whereNotNull('siswa_pendaftar_id');
                        } elseif ($state['value'] === 'manual') {
                            return $query->whereNull('siswa_pendaftar_id');
                        }
                        return $query;
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
                        ->modalHeading(fn (Siswa $record) => "Dokumen Siswa: {$record->nama_lengkap}")
                        ->modalContent(function (Siswa $record) {
                            return view('filament.components.siswa-documents-modal', [
                                'record' => $record,
                                'documents' => [
                                    [
                                        'name' => 'Foto Siswa',
                                        'url' => $record->foto_url,
                                        'exists' => $record->foto_exists,
                                        'type' => 'image',
                                    ],
                                    [
                                        'name' => 'Formulir Pendaftaran',
                                        'url' => $record->formulir_url,
                                        'exists' => $record->formulir_exists,
                                        'type' => $record->formulir_exists ? (pathinfo($record->formulir_path, PATHINFO_EXTENSION) === 'pdf' ? 'pdf' : 'image') : null,
                                    ],
                                ]
                            ]);
                        })
                        ->modalWidth('4xl')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup'),
                    
                    Tables\Actions\Action::make('generateNIS')
                        ->label('Generate NIS')
                        ->icon('heroicon-o-identification')
                        ->color('warning')
                        ->action(function (Siswa $record) {
                            if (empty($record->nis)) {
                                // Gunakan tanggal_masuk untuk menentukan bulan dan tahun NIS
                                $date = $record->tanggal_masuk ? Carbon::parse($record->tanggal_masuk) : now();
                                $year = $date->format('Y');
                                $month = $date->format('m');
                                
                                // Hitung urutan pendaftaran
                                $urutan = 1;
                                if ($record->tahun_ajaran_id) {
                                    $totalSiswaTahunAjaran = Siswa::where('tahun_ajaran_id', $record->tahun_ajaran_id)->count();
                                    $urutan = $totalSiswaTahunAjaran + 1;
                                } else {
                                    // Hitung berdasarkan bulan
                                    $startDate = $date->copy()->startOfMonth();
                                    $endDate = $date->copy()->endOfMonth();
                                    $totalSiswaSameMonth = Siswa::whereBetween('tanggal_masuk', [$startDate, $endDate])->count();
                                    $urutan = $totalSiswaSameMonth + 1;
                                }
                                
                                // Format: urutan (3 digit) + bulan (2 digit) + tahun (4 digit)
                                $urutanFormatted = str_pad($urutan, 3, '0', STR_PAD_LEFT);
                                $record->nis = $urutanFormatted . $month . $year;
                                $record->save();
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Generate NIS')
                        ->modalDescription('Apakah Anda yakin ingin generate NIS untuk siswa ini?')
                        ->visible(fn (Siswa $record) => empty($record->nis)),
                    
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('assignKelas')
                        ->label('Assign ke Kelas')
                        ->icon('heroicon-o-academic-cap')
                        ->color('primary')
                        ->form([
                            Forms\Components\Select::make('kelas_id')
                                ->label('Kelas')
                                ->options(Kelas::all()->pluck('nama_kelas', 'id'))
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->kelas_id = $data['kelas_id'];
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),
                    
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'aktif' => 'Aktif',
                                    'pindah' => 'Pindah',
                                    'lulus' => 'Lulus',
                                ])
                                ->required(),
                            Forms\Components\DatePicker::make('tanggal_keluar')
                                ->label('Tanggal Keluar')
                                ->visible(fn (callable $get) => in_array($get('status'), ['pindah', 'lulus'])),
                            Forms\Components\Textarea::make('alasan_keluar')
                                ->label('Alasan Keluar')
                                ->rows(2)
                                ->placeholder('Masukkan alasan...')
                                ->visible(fn (callable $get) => in_array($get('status'), ['pindah', 'lulus'])),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->status = $data['status'];
                                if (in_array($data['status'], ['pindah', 'lulus'])) {
                                    $record->tanggal_keluar = $data['tanggal_keluar'] ?? now();
                                    $record->alasan_keluar = $data['alasan_keluar'] ?? null;
                                }
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'view' => Pages\ViewSiswa::route('/{record}'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'aktif')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
}