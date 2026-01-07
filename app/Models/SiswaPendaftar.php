<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    // Kunci dan IV untuk AES
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

    // ============================
    // ENCRYPTION SETTERS
    // ============================

    public function setNikAttribute($value)
    {
        if (!empty($value) && $value !== null) {
            $this->attributes['nik'] = $this->encryptAES($value);
        } else {
            $this->attributes['nik'] = null;
        }
    }

    public function setNikAyahAttribute($value)
    {
        if (!empty($value) && $value !== null) {
            $this->attributes['nik_ayah'] = $this->encryptAES($value);
        } else {
            $this->attributes['nik_ayah'] = null;
        }
    }

    public function setNikIbuAttribute($value)
    {
        if (!empty($value) && $value !== null) {
            $this->attributes['nik_ibu'] = $this->encryptAES($value);
        } else {
            $this->attributes['nik_ibu'] = null;
        }
    }

    public function setNoTelpAttribute($value)
    {
        if (!empty($value) && $value !== null) {
            $this->attributes['no_telp'] = $this->encryptAES($value);
        } else {
            $this->attributes['no_telp'] = null;
        }
    }

    public function setPenghasilanAttribute($value)
    {
        if (!empty($value) && $value !== null) {
            // Konversi ke string untuk enkripsi
            $stringValue = (string)$value;
            $this->attributes['penghasilan'] = $this->encryptAES($stringValue);
        } else {
            $this->attributes['penghasilan'] = $this->encryptAES('0');
        }
    }

    // ============================
    // DECRYPTION GETTERS
    // ============================

    public function getNikDecryptedAttribute()
    {
        if (isset($this->attributes['nik']) && !empty($this->attributes['nik'])) {
            try {
                return $this->decryptAES($this->attributes['nik']);
            } catch (\Exception $e) {
                \Log::error('NIK Decryption error: ' . $e->getMessage());
                return '';
            }
        }
        return '';
    }

    public function getNikAyahDecryptedAttribute()
    {
        if (isset($this->attributes['nik_ayah']) && !empty($this->attributes['nik_ayah'])) {
            try {
                return $this->decryptAES($this->attributes['nik_ayah']);
            } catch (\Exception $e) {
                \Log::error('NIK Ayah Decryption error: ' . $e->getMessage());
                return '';
            }
        }
        return '';
    }

    public function getNikIbuDecryptedAttribute()
    {
        if (isset($this->attributes['nik_ibu']) && !empty($this->attributes['nik_ibu'])) {
            try {
                return $this->decryptAES($this->attributes['nik_ibu']);
            } catch (\Exception $e) {
                \Log::error('NIK Ibu Decryption error: ' . $e->getMessage());
                return '';
            }
        }
        return '';
    }

    public function getNoTelpDecryptedAttribute()
    {
        if (isset($this->attributes['no_telp']) && !empty($this->attributes['no_telp'])) {
            try {
                return $this->decryptAES($this->attributes['no_telp']);
            } catch (\Exception $e) {
                \Log::error('No. Telp Decryption error: ' . $e->getMessage());
                return '';
            }
        }
        return '';
    }

    public function getPenghasilanDecryptedAttribute()
    {
        if (isset($this->attributes['penghasilan']) && !empty($this->attributes['penghasilan'])) {
            try {
                $decrypted = $this->decryptAES($this->attributes['penghasilan']);
                return (float)$decrypted;
            } catch (\Exception $e) {
                \Log::error('Penghasilan Decryption error: ' . $e->getMessage());
                return 0;
            }
        }
        return 0;
    }

    // ============================
    // ENCRYPTION/DECRYPTION METHODS
    // ============================

    public function encryptAES($data)
    {
        try {
            $key = $this->getEncryptionKey();
            $iv = $this->getEncryptionIV();
            
            $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            if ($encrypted === false) {
                throw new \Exception('Encryption failed: ' . openssl_error_string());
            }
            return base64_encode($encrypted);
        } catch (\Exception $e) {
            \Log::error('Encryption error: ' . $e->getMessage());
            return $data;
        }
    }

    public function decryptAES($data)
    {
        try {
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
    // FORMATTERS DAN URL GAMBAR
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

    /**
     * Helper untuk mendapatkan URL dokumen dengan benar
     */
    private function getDocumentUrl($path)
    {
        if (!$path) {
            return null;
        }
        
        // Hapus 'storage/' atau 'public/' jika ada di awal
        $cleanPath = $path;
        if (strpos($cleanPath, 'storage/') === 0) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (strpos($cleanPath, 'public/') === 0) {
            $cleanPath = substr($cleanPath, 7);
        }
        
        // Cek apakah file ada di storage
        if (Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }
        
        // Coba akses langsung jika file ada di public
        $publicPath = public_path($path);
        if (file_exists($publicPath)) {
            return asset($path);
        }
        
        return null;
    }

    /**
     * Helper untuk cek apakah dokumen ada
     */
    private function documentExists($path)
    {
        if (!$path) {
            return false;
        }
        
        $cleanPath = $path;
        if (strpos($cleanPath, 'storage/') === 0) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (strpos($cleanPath, 'public/') === 0) {
            $cleanPath = substr($cleanPath, 7);
        }
        
        return Storage::disk('public')->exists($cleanPath);
    }

    public function getAkteKelahiranUrlAttribute()
    {
        return $this->getDocumentUrl($this->akte_kelahiran_path);
    }

    public function getKartuKeluargaUrlAttribute()
    {
        return $this->getDocumentUrl($this->kartu_keluarga_path);
    }

    public function getKiaUrlAttribute()
    {
        return $this->getDocumentUrl($this->kia_path);
    }

    public function getBpjsUrlAttribute()
    {
        return $this->getDocumentUrl($this->bpjs_path);
    }

    public function getAkteKelahiranExistsAttribute()
    {
        return $this->documentExists($this->akte_kelahiran_path);
    }

    public function getKartuKeluargaExistsAttribute()
    {
        return $this->documentExists($this->kartu_keluarga_path);
    }

    public function getKiaExistsAttribute()
    {
        return $this->documentExists($this->kia_path);
    }

    public function getBpjsExistsAttribute()
    {
        return $this->documentExists($this->bpjs_path);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->status)) {
                $model->status = 'menunggu';
            }
        });
    }

    // Tambahkan di dalam class SiswaPendaftar:

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    // TAMBAHKAN RELASI INI - Relasi ke TahunAjaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }


}