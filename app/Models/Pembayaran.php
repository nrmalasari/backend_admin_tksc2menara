<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'regist_pendaftar_id',
        'tanggal_pembayaran',
        'nama',
        'nama_bank',
        'no_rek',
        'metode_pembayaran',
        'jumlah_pembayaran',
        'jenis_pembayaran', // TAMBAHKAN INI
        'bukti_pembayaran',
        'catatan_admin',
        'status_pembayaran'
    ];

    protected $appends = [
        'no_rek_decrypted', 
        'status_text', 
        'status_color', 
        'formatted_jumlah',
        'formatted_tanggal',
        'bukti_pembayaran_url'
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'jumlah_pembayaran' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    /**
     * Encrypt no_rek dengan AES-256-CBC sebelum save
     */
    public function setNoRekAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['no_rek'] = $this->encryptAES($value);
        } else {
            $this->attributes['no_rek'] = '';
        }
    }

    /**
     * Get decrypted no_rek
     */
    public function getNoRekDecryptedAttribute()
    {
        if (isset($this->attributes['no_rek']) && !empty($this->attributes['no_rek'])) {
            try {
                return $this->decryptAES($this->attributes['no_rek']);
            } catch (\Exception $e) {
                \Log::error('Decryption error: ' . $e->getMessage());
                return '';
            }
        }
        return '';
    }

    /**
     * Encrypt dengan AES-256-CBC
     */
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

    /**
     * Decrypt AES-256-CBC
     */
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

    /**
     * Format tanggal untuk display
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal_pembayaran ? $this->tanggal_pembayaran->format('d/m/Y') : '';
    }

    /**
     * URL lengkap untuk bukti pembayaran
     */
    public function getBuktiPembayaranUrlAttribute()
    {
        if ($this->bukti_pembayaran) {
            $path = $this->bukti_pembayaran;
            
            if (strpos($path, 'storage/') === 0) {
                $path = substr($path, 8);
            }
            
            if (strpos($path, 'public/') === 0) {
                $path = substr($path, 7);
            }
            
            return asset('storage/' . $path);
        }
        
        return asset('images/default-payment.png');
    }

    /**
     * Path lengkap untuk bukti pembayaran (tanpa storage/)
     */
    public function getBuktiPembayaranPathAttribute()
    {
        if (!$this->bukti_pembayaran) {
            return null;
        }
        
        $path = $this->bukti_pembayaran;
        
        if (strpos($path, 'storage/') === 0) {
            $path = substr($path, 8);
        }
        
        if (strpos($path, 'public/') === 0) {
            $path = substr($path, 7);
        }
        
        return $path;
    }

    /**
     * Cek apakah file bukti ada di storage
     */
    public function getBuktiExistsAttribute()
    {
        $path = $this->bukti_pembayaran_path;
        return $path ? Storage::disk('public')->exists($path) : false;
    }

    /**
     * Format jumlah pembayaran untuk display
     */
    public function getFormattedJumlahAttribute()
    {
        // Tampilkan jumlah untuk SEMUA metode, termasuk manual
        return 'Rp ' . number_format($this->jumlah_pembayaran, 0, ',', '.');
    }

    /**
     * Status badge color untuk display
     */
    public function getStatusColorAttribute()
    {
        $status = $this->status_pembayaran;
        if ($status === 'pending') {
            $status = 'menunggu';
        }
        
        return match($status) {
            'menunggu' => 'warning',
            'diproses' => 'info',
            'diverifikasi' => 'success',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Status text untuk display
     */
    public function getStatusTextAttribute()
    {
        $status = $this->status_pembayaran;
        if ($status === 'pending') {
            $status = 'menunggu';
        }
        
        return match($status) {
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses',
            'diverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Boot method untuk set default
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->status_pembayaran)) {
                $model->status_pembayaran = 'menunggu';
            }
            
            // Untuk metode manual, set default values tapi JANGAN override jumlah_pembayaran
            if ($model->metode_pembayaran === 'manual') {
                // Hanya set jika belum ada nilai
                if (empty($model->nama_bank)) {
                    $model->nama_bank = 'Pembayaran di Kantor';
                }
                if (empty($model->no_rek)) {
                    $model->no_rek = '';
                }
                // JANGAN set jumlah_pembayaran = 0!
                // Biarkan nilai dari input user
            }
            
            if (empty($model->tanggal_pembayaran)) {
                $model->tanggal_pembayaran = now();
            }
        });
        
        static::updating(function ($model) {
            // Untuk metode manual, set default values jika kosong
            if ($model->metode_pembayaran === 'manual') {
                // Hanya set jika belum ada nilai
                if (empty($model->nama_bank) || $model->nama_bank !== 'Pembayaran di Kantor') {
                    $model->nama_bank = 'Pembayaran di Kantor';
                }
                if (!empty($model->no_rek)) {
                    $model->no_rek = '';
                }
                // JANGAN set jumlah_pembayaran = 0!
                // Biarkan nilai dari input user
            }
        });
    }
}