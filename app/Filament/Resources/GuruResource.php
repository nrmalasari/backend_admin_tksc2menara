<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Models\Guru;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?string $navigationLabel = 'Guru';
    protected static ?string $pluralModelLabel = 'Data Guru';
    protected static ?string $createButtonLabel = 'Tambah Guru'; 
    protected static ?string $modelLabel = 'Guru';
    protected static ?int $navigationSort = 3;

    public static function getBreadcrumb(): string
    {
        return 'Guru';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pribadi')
                    ->schema([
                        Forms\Components\FileUpload::make('foto_path')
                            ->label('Foto Guru')
                            ->directory('guru/foto')
                            ->disk('cloudinary')
                            ->image()
                            ->maxSize(2048)
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('300')
                            ->imageResizeTargetHeight('300')
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 
                                    'guru_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension()
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
                                $filename = 'guru_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                                $path = $file->storeAs('guru/foto', $filename, 'cloudinary');
                                return $path;
                            })
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('jabatan')
                            ->label('Jabatan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Guru Kelas, Guru Mapel, Kepala Sekolah'),
                        
                        Forms\Components\TextInput::make('nuptk')
                            ->label('NUPTK')
                            ->numeric()
                            ->unique(ignoreRecord: true)
                            ->nullable()
                            ->maxLength(16)
                            ->helperText('Nomor Unik Pendidik dan Tenaga Kependidikan'),
                        
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Non Aktif',
                                'pensiun' => 'Pensiun',
                            ])
                            ->default('aktif')
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Penempatan Kelas')
                    ->schema([
                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->relationship('kelas', 'nama_kelas')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Pilih kelas jika guru bertugas sebagai wali kelas')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $kelas = Kelas::find($state);
                                    if ($kelas) {
                                        $set('guru_kelas', $kelas->nama_kelas);
                                    }
                                } else {
                                    $set('guru_kelas', null);
                                }
                            }),
                        
                        Forms\Components\TextInput::make('guru_kelas')
                            ->label('Nama Kelas (Otomatis)')
                            ->readOnly()
                            ->dehydrated()
                            ->placeholder('Akan terisi otomatis setelah memilih kelas'),
                        
                    ])->columns(3),
                
                Forms\Components\Section::make('Informasi Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->nullable()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('telepon')
                            ->label('Telepon')
                            ->tel()
                            ->nullable()
                            ->maxLength(20),
                        
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->nullable()
                            ->rows(2)
                            ->maxLength(500),
                    ])->columns(2),
                
                Forms\Components\Section::make('Informasi Pendidikan & Kepegawaian')
                    ->schema([
                        Forms\Components\TextInput::make('pendidikan_terakhir')
                            ->label('Pendidikan Terakhir')
                            ->nullable()
                            ->maxLength(100)
                            ->placeholder('Contoh: S1 Pendidikan Matematika'),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_mulai')
                                    ->label('Tanggal Mulai Bekerja')
                                    ->nullable(),
                                
                                Forms\Components\DatePicker::make('tanggal_selesai')
                                    ->label('Tanggal Selesai Bekerja')
                                    ->nullable()
                                    ->visible(fn (callable $get) => in_array($get('status'), ['nonaktif', 'pensiun'])),
                            ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_url')
                    ->label('Foto')
                    ->disk('cloudinary')
                    ->square()
                    ->defaultImageUrl(asset('images/default-avatar.png'))
                    ->extraImgAttributes(['class' => 'object-cover rounded'])
                    ->size(50),
                
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Guru')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                
                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable()
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('nuptk')
                    ->label('NUPTK')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->copyable()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('guru_kelas')
                    ->label('Guru Kelas')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->color('info')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ?: '-'),
                
                Tables\Columns\TextColumn::make('bidang_studi')
                    ->label('Bidang Studi')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'danger',
                        'pensiun' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non Aktif',
                        'pensiun' => 'Pensiun',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('telepon')
                    ->label('Telepon')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non Aktif',
                        'pensiun' => 'Pensiun',
                    ]),
                
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\Filter::make('tanpa_kelas')
                    ->label('Tanpa Kelas')
                    ->query(fn ($query) => $query->whereNull('kelas_id'))
                    ->toggle(),
                
                Tables\Filters\Filter::make('jabatan')
                    ->form([
                        Forms\Components\TextInput::make('jabatan')
                            ->label('Jabatan mengandung')
                            ->placeholder('Masukkan kata kunci jabatan'),
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['jabatan'])) {
                            $query->where('jabatan', 'like', '%' . $data['jabatan'] . '%');
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('viewFoto')
                        ->label('Lihat Foto')
                        ->icon('heroicon-o-photo')
                        ->color('info')
                        ->modalHeading(fn (Guru $record) => "Foto Guru: {$record->nama_lengkap}")
                        ->modalContent(function (Guru $record) {
                            return view('filament.components.guru-foto-modal', [
                                'record' => $record,
                                'foto_url' => $record->foto_url,
                                'nama_lengkap' => $record->nama_lengkap,
                            ]);
                        })
                        ->modalWidth('lg')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup'),
                    
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
                                ->options(Kelas::all()->pluck('nama_kelas', 'id_kelas'))
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->kelas_id = $data['kelas_id'];
                                $kelas = Kelas::find($data['kelas_id']);
                                if ($kelas) {
                                    $record->guru_kelas = $kelas->nama_kelas;
                                }
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
                                    'nonaktif' => 'Non Aktif',
                                    'pensiun' => 'Pensiun',
                                ])
                                ->required(),
                            Forms\Components\DatePicker::make('tanggal_selesai')
                                ->label('Tanggal Selesai')
                                ->nullable()
                                ->visible(fn (callable $get) => in_array($get('status'), ['nonaktif', 'pensiun'])),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->status = $data['status'];
                                if (in_array($data['status'], ['nonaktif', 'pensiun'])) {
                                    $record->tanggal_selesai = $data['tanggal_selesai'] ?? now();
                                }
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('nama_lengkap', 'asc')
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
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'view' => Pages\ViewGuru::route('/{record}'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }

}