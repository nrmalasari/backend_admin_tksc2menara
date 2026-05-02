<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;

class SiswaPendaftar extends Model
{
    use HasFactory;

    protected $table = 'siswa_pendaftars';

    protected $fillable = [
        'regist_pendaftar_id',
        'tahun_ajaran_id',
        // Data Anak
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'jenis_kelamin',
        'usia',
        'alamat_jalan',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'kode_pos',
        'tinggi_badan',
        'berat_badan',
        'jumlah_saudara',
        'jarak_sekolah',
        'waktu_tempuh',
        // Data Orang Tua
        'nama_ayah',
        'nama_ibu',
        'nik_ayah',
        'nik_ibu',
        'tempat_lahir_ayah',
        'tempat_lahir_ibu',
        'tanggal_lahir_ayah',
        'tanggal_lahir_ibu',
        'pendidikan_ayah',
        'pendidikan_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'alamat_ayah',
        'alamat_ibu',
        'no_telp',
        'penghasilan',
        // File Upload
        'akte_kelahiran_path',
        'kartu_keluarga_path',
        'kia_path',
        'bpjs_path',
        // Status
        'status',
        'catatan_admin'
    ];

    protected $appends = [
        'nik_decrypted',
        'nik_ayah_decrypted',
        'nik_ibu_decrypted',
        'no_telp_decrypted',
        'penghasilan_decrypted',
        'status_text',
        'status_color',
        'formatted_penghasilan',
        'formatted_tanggal_lahir',
        'akte_kelahiran_url',
        'kartu_keluarga_url',
        'kia_url',
        'bpjs_url',
        'akte_kelahiran_exists',
        'kartu_keluarga_exists',
        'kia_exists',
        'bpjs_exists'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'jarak_sekolah' => 'decimal:2',
    ];

    // Relasi ke RegistPendaftar
    public function registPendaftar()
    {
        return $this->belongsTo(RegistPendaftar::class);
    }

    // Relasi ke TahunAjaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    // ============================
    // ENKRIPSI DAN DEKRIPSI
    // ============================

    private function getEncryptionKey()
    {
        $appKey = config('app.key');
        if (str_starts_with($appKey, 'base64:')) {
            $appKey = base64_decode(substr($appKey, 7));
        }
        return substr(hash('sha256', $appKey), 0, 32);
    }

    private function getEncryptionIV()
    {
        return '0123456789abcdef';
    }

    public function encryptAES($data)
    {
        try {
            if (empty($data)) {
                return null;
            }
            
            $key = $this->getEncryptionKey();
            $iv = $this->getEncryptionIV();
            
            $encrypted = openssl_encrypt((string)$data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            if ($encrypted === false) {
                throw new \Exception('Encryption failed: ' . openssl_error_string());
            }
            return base64_encode($encrypted);
        } catch (\Exception $e) {
            \Log::error('Encryption error: ' . $e->getMessage());
            return null;
        }
    }

    public function decryptAES($data)
    {
        try {
            if (empty($data)) {
                return '';
            }
            
            $key = $this->getEncryptionKey();
            $iv = $this->getEncryptionIV();
            
            $decrypted = openssl_decrypt(base64_decode($data), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            if ($decrypted === false) {
                throw new \Exception('Decryption failed: ' . openssl_error_string());
            }
            return $decrypted;
        } catch (\Exception $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            return '';
        }
    }

    // ============================
    // MUTATORS (ENKRIPSI)
    // ============================

    public function setNikAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['nik'] = $this->encryptAES($value);
        } else {
            $this->attributes['nik'] = null;
        }
    }

    public function setNikAyahAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['nik_ayah'] = $this->encryptAES($value);
        } else {
            $this->attributes['nik_ayah'] = null;
        }
    }

    public function setNikIbuAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['nik_ibu'] = $this->encryptAES($value);
        } else {
            $this->attributes['nik_ibu'] = null;
        }
    }

    public function setNoTelpAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['no_telp'] = $this->encryptAES($value);
        } else {
            $this->attributes['no_telp'] = null;
        }
    }

    public function setPenghasilanAttribute($value)
    {
        if (!empty($value) && $value !== null) {
            $stringValue = (string)$value;
            $this->attributes['penghasilan'] = $this->encryptAES($stringValue);
        } else {
            $this->attributes['penghasilan'] = $this->encryptAES('0');
        }
    }

    // ============================
    // ACCESSORS (DEKRIPSI)
    // ============================

    public function getNikDecryptedAttribute()
    {
        if (isset($this->attributes['nik']) && !empty($this->attributes['nik'])) {
            return $this->decryptAES($this->attributes['nik']);
        }
        return '';
    }

    public function getNikAyahDecryptedAttribute()
    {
        if (isset($this->attributes['nik_ayah']) && !empty($this->attributes['nik_ayah'])) {
            return $this->decryptAES($this->attributes['nik_ayah']);
        }
        return '';
    }

    public function getNikIbuDecryptedAttribute()
    {
        if (isset($this->attributes['nik_ibu']) && !empty($this->attributes['nik_ibu'])) {
            return $this->decryptAES($this->attributes['nik_ibu']);
        }
        return '';
    }

    public function getNoTelpDecryptedAttribute()
    {
        if (isset($this->attributes['no_telp']) && !empty($this->attributes['no_telp'])) {
            return $this->decryptAES($this->attributes['no_telp']);
        }
        return '';
    }

    public function getPenghasilanDecryptedAttribute()
    {
        if (isset($this->attributes['penghasilan']) && !empty($this->attributes['penghasilan'])) {
            $decrypted = $this->decryptAES($this->attributes['penghasilan']);
            return (float)$decrypted;
        }
        return 0;
    }

    // ============================
    // FORMATTERS
    // ============================

    public function getFormattedTanggalLahirAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->format('d/m/Y') : '';
    }

    public function getFormattedPenghasilanAttribute()
    {
        $penghasilan = $this->penghasilan_decrypted;
        return 'Rp ' . number_format($penghasilan, 0, ',', '.');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'menunggu' => 'warning',
            'diproses' => 'info',
            'diverifikasi' => 'success',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'menunggu' => 'Menunggu Verifikasi',
            'diproses' => 'Sedang Diproses',
            'diverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    // ============================
    // URL DOKUMEN - PERBAIKAN UTAMA
    // ============================

    /**
     * Generate URL untuk file dokumen
     * Sekarang menggunakan route proxy: /lihat-file/{filename}
     */
    private function getDocumentUrl($path)
    {
        if (!$path) {
            return null;
        }
        
        \Log::info('Getting document URL for path: ' . $path);
        
        $cleanPath = $path;
        
        // 1. Hapus prefix storage/ atau public/ jika ada
        $cleanPath = str_replace(['storage/', 'public/'], '', $cleanPath);
        
        // 2. Hapus api/secure-files/ jika ada (kita hanya butuh filename)
        $cleanPath = str_replace('api/secure-files/', '', $cleanPath);
        
        // 3. Hapus siswa/dokumen/ jika ada
        $cleanPath = str_replace('siswa/dokumen/', '', $cleanPath);
        
        // 4. Ambil hanya filename
        $filename = basename($cleanPath);
        
        // 5. Generate URL menggunakan route proxy
        try {
            $url = route('ambil.file.rahasia', ['filename' => $filename]);
            \Log::info('Generated route proxy URL: ' . $url);
            return $url;
        } catch (\Exception $e) {
            \Log::error('Failed to generate route URL: ' . $e->getMessage());
            
            // Fallback ke URL langsung jika route tidak ada
            $fallbackUrl = url('/lihat-file/' . $filename);
            \Log::info('Using fallback URL: ' . $fallbackUrl);
            return $fallbackUrl;
        }
    }

    /**
     * Get the URL for akte kelahiran
     */
    public function getAkteKelahiranUrlAttribute()
    {
        return $this->getDocumentUrl($this->akte_kelahiran_path);
    }

    /**
     * Get the URL for kartu keluarga
     */
    public function getKartuKeluargaUrlAttribute()
    {
        return $this->getDocumentUrl($this->kartu_keluarga_path);
    }

    /**
     * Get the URL for KIA
     */
    public function getKiaUrlAttribute()
    {
        return $this->getDocumentUrl($this->kia_path);
    }

    /**
     * Get the URL for BPJS
     */
    public function getBpjsUrlAttribute()
    {
        return $this->getDocumentUrl($this->bpjs_path);
    }

    /**
     * Cek apakah file benar-benar ada di storage
     */
    private function documentExists($path)
    {
        if (!$path) {
            return false;
        }
        
        $cleanPath = $path;
        
        // 1. Hapus prefix
        $cleanPath = str_replace(['storage/', 'public/'], '', $cleanPath);
        
        // 2. Hapus api/secure-files/ atau siswa/dokumen/ untuk dapat filename
        $cleanPath = str_replace(['api/secure-files/', 'siswa/dokumen/'], '', $cleanPath);
        
        // 3. Cek file di disk 'rahasia' (storage/app/secure-files/)
        $filename = basename($cleanPath);
        
        $exists = Storage::disk('rahasia')->exists($filename);
        
        if (!$exists) {
            \Log::warning('File not found in secure storage: ' . $filename);
            
            // Coba cari di lokasi alternatif (public disk untuk backward compatibility)
            $possiblePaths = [
                'api/secure-files/' . $filename,
                'siswa/dokumen/' . $filename,
                $filename
            ];
            
            foreach ($possiblePaths as $tryPath) {
                if (Storage::disk('public')->exists($tryPath)) {
                    \Log::info('Found file at alternative path: ' . $tryPath);
                    return true;
                }
            }
        }
        
        return $exists;
    }

    /**
     * Check if akte kelahiran file exists
     */
    public function getAkteKelahiranExistsAttribute()
    {
        return $this->documentExists($this->akte_kelahiran_path);
    }

    /**
     * Check if kartu keluarga file exists
     */
    public function getKartuKeluargaExistsAttribute()
    {
        return $this->documentExists($this->kartu_keluarga_path);
    }

    /**
     * Check if KIA file exists
     */
    public function getKiaExistsAttribute()
    {
        return $this->documentExists($this->kia_path);
    }

    /**
     * Check if BPJS file exists
     */
    public function getBpjsExistsAttribute()
    {
        return $this->documentExists($this->bpjs_path);
    }

    // ============================
    // HELPER UNTUK DEBUGGING
    // ============================

    /**
     * Debug info untuk file
     */
    public function getFileDebugInfo()
    {
        return [
            'akte' => [
                'db_path' => $this->akte_kelahiran_path,
                'filename' => basename(str_replace(['storage/', 'public/', 'api/secure-files/', 'siswa/dokumen/'], '', $this->akte_kelahiran_path)),
                'url' => $this->akte_kelahiran_url,
                'exists' => $this->akte_kelahiran_exists,
                'route' => route('ambil.file.rahasia', ['filename' => basename(str_replace(['storage/', 'public/', 'api/secure-files/', 'siswa/dokumen/'], '', $this->akte_kelahiran_path))]),
            ],
            'kk' => [
                'db_path' => $this->kartu_keluarga_path,
                'filename' => basename(str_replace(['storage/', 'public/', 'api/secure-files/', 'siswa/dokumen/'], '', $this->kartu_keluarga_path)),
                'url' => $this->kartu_keluarga_url,
                'exists' => $this->kartu_keluarga_exists,
                'route' => route('ambil.file.rahasia', ['filename' => basename(str_replace(['storage/', 'public/', 'api/secure-files/', 'siswa/dokumen/'], '', $this->kartu_keluarga_path))]),
            ],
            'kia' => [
                'db_path' => $this->kia_path,
                'filename' => basename(str_replace(['storage/', 'public/', 'api/secure-files/', 'siswa/dokumen/'], '', $this->kia_path)),
                'url' => $this->kia_url,
                'exists' => $this->kia_exists,
                'route' => route('ambil.file.rahasia', ['filename' => basename(str_replace(['storage/', 'public/', 'api/secure-files/', 'siswa/dokumen/'], '', $this->kia_path))]),
            ],
            'bpjs' => [
                'db_path' => $this->bpjs_path,
                'filename' => basename(str_replace(['storage/', 'public/', 'api/secure-files/', 'siswa/dokumen/'], '', $this->bpjs_path)),
                'url' => $this->bpjs_url,
                'exists' => $this->bpjs_exists,
                'route' => route('ambil.file.rahasia', ['filename' => basename(str_replace(['storage/', 'public/', 'api/secure-files/', 'siswa/dokumen/'], '', $this->bpjs_path))]),
            ],
        ];
    }

    /**
     * Update semua path di database dari siswa/dokumen/ ke hanya filename
     */
    public static function migrateDatabasePaths()
    {
        \Log::info('Starting database path migration to filename only...');
        
        $updated = 0;
        $siswas = self::all();
        
        foreach ($siswas as $siswa) {
            $updatedFields = 0;
            
            $fields = ['akte_kelahiran_path', 'kartu_keluarga_path', 'kia_path', 'bpjs_path'];
            
            foreach ($fields as $field) {
                $path = $siswa->$field;
                
                if ($path) {
                    // Ekstrak hanya filename
                    $filename = basename(str_replace(['storage/', 'public/', 'api/secure-files/', 'siswa/dokumen/'], '', $path));
                    
                    if ($filename !== $path) {
                        $siswa->$field = $filename;
                        $updatedFields++;
                        
                        \Log::info("Updated {$field} for siswa {$siswa->id}: {$path} → {$filename}");
                    }
                }
            }
            
            if ($updatedFields > 0) {
                $siswa->save();
                $updated++;
            }
        }
        
        \Log::info("Database migration completed. Updated {$updated} records.");
        return $updated;
    }

    // ============================
    // SCOPE UNTUK PENCARIAN
    // ============================

    /**
     * Scope untuk pencarian data terenkripsi
     */
    public function scopeSearchEncrypted(Builder $query, string $search, string $column)
    {
        return $query->where(function ($q) use ($search, $column) {
            return $q;
        });
    }

    /**
     * Scope untuk pencarian global
     */
    public function scopeGlobalSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            // Kolom yang tidak terenkripsi
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('tempat_lahir', 'like', "%{$search}%")
              ->orWhere('agama', 'like', "%{$search}%")
              ->orWhere('jenis_kelamin', 'like', "%{$search}%")
              ->orWhere('nama_ayah', 'like', "%{$search}%")
              ->orWhere('nama_ibu', 'like', "%{$search}%")
              ->orWhereHas('registPendaftar', function ($subQuery) use ($search) {
                  $subQuery->where('username', 'like', "%{$search}%");
              })
              ->orWhereHas('tahunAjaran', function ($subQuery) use ($search) {
                  $subQuery->where('nama_tahun_ajaran', 'like', "%{$search}%");
              });
        });
    }

    // ============================
    // MUTATORS TAMBAHAN UNTUK FILE
    // ============================

    /**
     * Mutator untuk otomatis convert path saat save
     */
    public function setAkteKelahiranPathAttribute($value)
    {
        $this->attributes['akte_kelahiran_path'] = $this->normalizeFilePath($value);
    }

    public function setKartuKeluargaPathAttribute($value)
    {
        $this->attributes['kartu_keluarga_path'] = $this->normalizeFilePath($value);
    }

    public function setKiaPathAttribute($value)
    {
        $this->attributes['kia_path'] = $this->normalizeFilePath($value);
    }

    public function setBpjsPathAttribute($value)
    {
        $this->attributes['bpjs_path'] = $this->normalizeFilePath($value);
    }

    /**
     * Normalize file path ke hanya filename
     */
    private function normalizeFilePath($path)
    {
        if (!$path) {
            return null;
        }
        
        // Hapus semua prefix dan ambil hanya filename
        $cleanPath = str_replace(['storage/', 'public/', 'api/secure-files/', 'siswa/dokumen/'], '', $path);
        
        return basename($cleanPath);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->status)) {
                $model->status = 'menunggu';
            }
            
            // Auto-normalize file paths saat create
            $model->normalizeAllFilePaths();
        });
        
        static::updating(function ($model) {
            // Auto-normalize file paths saat update
            $model->normalizeAllFilePaths();
        });
    }
    
    /**
     * Normalize semua file paths
     */
    private function normalizeAllFilePaths()
    {
        $fields = ['akte_kelahiran_path', 'kartu_keluarga_path', 'kia_path', 'bpjs_path'];
        
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $this->$field = $this->normalizeFilePath($this->$field);
            }
        }
    }

    // ============================
    // METODE UNTUK API RESPONSE
    // ============================

    /**
     * Format data untuk API response
     */
    public function toApiArray()
    {
        return [
            'id' => $this->id,
            'regist_pendaftar_id' => $this->regist_pendaftar_id,
            'nama_lengkap' => $this->nama_lengkap,
            'nik' => $this->nik_decrypted,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'formatted_tanggal_lahir' => $this->formatted_tanggal_lahir,
            'agama' => $this->agama,
            'jenis_kelamin' => $this->jenis_kelamin,
            'usia' => $this->usia,
            'alamat_jalan' => $this->alamat_jalan,
            'rt' => $this->rt,
            'rw' => $this->rw,
            'kelurahan' => $this->kelurahan,
            'kecamatan' => $this->kecamatan,
            'kota' => $this->kota,
            'kode_pos' => $this->kode_pos,
            'tinggi_badan' => $this->tinggi_badan,
            'berat_badan' => $this->berat_badan,
            'jumlah_saudara' => $this->jumlah_saudara,
            'jarak_sekolah' => (float)$this->jarak_sekolah,
            'waktu_tempuh' => $this->waktu_tempuh,
            
            // Data Orang Tua
            'nama_ayah' => $this->nama_ayah,
            'nama_ibu' => $this->nama_ibu,
            'nik_ayah' => $this->nik_ayah_decrypted,
            'nik_ibu' => $this->nik_ibu_decrypted,
            'tempat_lahir_ayah' => $this->tempat_lahir_ayah,
            'tempat_lahir_ibu' => $this->tempat_lahir_ibu,
            'tanggal_lahir_ayah' => $this->tanggal_lahir_ayah,
            'tanggal_lahir_ibu' => $this->tanggal_lahir_ibu,
            'pendidikan_ayah' => $this->pendidikan_ayah,
            'pendidikan_ibu' => $this->pendidikan_ibu,
            'pekerjaan_ayah' => $this->pekerjaan_ayah,
            'pekerjaan_ibu' => $this->pekerjaan_ibu,
            'alamat_ayah' => $this->alamat_ayah,
            'alamat_ibu' => $this->alamat_ibu,
            'no_telp' => $this->no_telp_decrypted,
            'penghasilan' => (float)$this->penghasilan_decrypted,
            'formatted_penghasilan' => $this->formatted_penghasilan,
            
            // Dokumen dengan URL route proxy
            'akte_kelahiran' => $this->akte_kelahiran_url,
            'akte_kelahiran_url' => $this->akte_kelahiran_url,
            'akte_kelahiran_exists' => $this->akte_kelahiran_exists,
            'kartu_keluarga' => $this->kartu_keluarga_url,
            'kartu_keluarga_url' => $this->kartu_keluarga_url,
            'kartu_keluarga_exists' => $this->kartu_keluarga_exists,
            'kia' => $this->kia_url,
            'kia_url' => $this->kia_url,
            'kia_exists' => $this->kia_exists,
            'bpjs' => $this->bpjs_url,
            'bpjs_url' => $this->bpjs_url,
            'bpjs_exists' => $this->bpjs_exists,
            
            // Status
            'status' => $this->status,
            'status_text' => $this->status_text,
            'status_color' => $this->status_color,
            'catatan_admin' => $this->catatan_admin,
            
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}