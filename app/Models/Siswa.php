<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';

    protected $fillable = [
        'siswa_pendaftar_id',
        'tahun_ajaran_id',
        'kelas_id',
        'nama_lengkap',
        'nis',
        'nsin',
        'status',
        'foto_path',
        'formulir_path',
        'tanggal_masuk',
        'tanggal_keluar',
        'alasan_keluar',
        'asal_sekolah',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
    ];

    protected $appends = [
        'foto_url',
        'formulir_url',
        'status_color',
        'status_text',
        'nama_kelas',
        'nama_tahun_ajaran',
        'foto_exists',
        'formulir_exists',
    ];

    // Mutator untuk file upload - konversi dari array ke string
    public function setFotoPathAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['foto_path'] = !empty($value) ? $value[0] : null;
        } else {
            $this->attributes['foto_path'] = $value;
        }
    }

    public function setFormulirPathAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['formulir_path'] = !empty($value) ? $value[0] : null;
        } else {
            $this->attributes['formulir_path'] = $value;
        }
    }

    // Accessor untuk form Filament - konversi dari string ke array
    protected function fotoPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? [$value] : [],
            set: fn ($value) => is_array($value) && !empty($value) ? $value[0] : null,
        );
    }

    protected function formulirPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? [$value] : [],
            set: fn ($value) => is_array($value) && !empty($value) ? $value[0] : null,
        );
    }

    // Relasi
    public function siswaPendaftar()
    {
        return $this->belongsTo(SiswaPendaftar::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    // Accessors
    public function getFotoUrlAttribute()
    {
        $path = $this->attributes['foto_path'] ?? null;
        
        if (!$path) {
            return asset('images/default-avatar.png');
        }
        
        // Pastikan $path adalah string
        if (is_array($path)) {
            $path = !empty($path) ? $path[0] : null;
        }
        
        if (!$path) {
            return asset('images/default-avatar.png');
        }
        
        // Pastikan $path adalah string sebelum menggunakan strpos
        $cleanPath = (string) $path;
        if (strpos($cleanPath, 'storage/') === 0) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (strpos($cleanPath, 'public/') === 0) {
            $cleanPath = substr($cleanPath, 7);
        }
        
        if (Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }
        
        return asset($cleanPath);
    }

    public function getFormulirUrlAttribute()
    {
        $path = $this->attributes['formulir_path'] ?? null;
        
        if (!$path) {
            return null;
        }
        
        // Pastikan $path adalah string
        if (is_array($path)) {
            $path = !empty($path) ? $path[0] : null;
        }
        
        if (!$path) {
            return null;
        }
        
        // Pastikan $path adalah string sebelum menggunakan strpos
        $cleanPath = (string) $path;
        if (strpos($cleanPath, 'storage/') === 0) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (strpos($cleanPath, 'public/') === 0) {
            $cleanPath = substr($cleanPath, 7);
        }
        
        if (Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }
        
        return asset($cleanPath);
    }

    public function getFotoExistsAttribute()
    {
        $path = $this->attributes['foto_path'] ?? null;
        
        if (!$path) {
            return false;
        }
        
        // Pastikan $path adalah string
        if (is_array($path)) {
            $path = !empty($path) ? $path[0] : null;
        }
        
        if (!$path) {
            return false;
        }
        
        $cleanPath = (string) $path;
        if (strpos($cleanPath, 'storage/') === 0) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (strpos($cleanPath, 'public/') === 0) {
            $cleanPath = substr($cleanPath, 7);
        }
        
        return Storage::disk('public')->exists($cleanPath);
    }

    public function getFormulirExistsAttribute()
    {
        $path = $this->attributes['formulir_path'] ?? null;
        
        if (!$path) {
            return false;
        }
        
        // Pastikan $path adalah string
        if (is_array($path)) {
            $path = !empty($path) ? $path[0] : null;
        }
        
        if (!$path) {
            return false;
        }
        
        $cleanPath = (string) $path;
        if (strpos($cleanPath, 'storage/') === 0) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (strpos($cleanPath, 'public/') === 0) {
            $cleanPath = substr($cleanPath, 7);
        }
        
        return Storage::disk('public')->exists($cleanPath);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'aktif' => 'success',
            'pindah' => 'warning',
            'lulus' => 'info',
            default => 'secondary',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'aktif' => 'Aktif',
            'pindah' => 'Pindah',
            'lulus' => 'Lulus',
            default => $this->status,
        };
    }

    public function getNamaKelasAttribute()
    {
        return $this->kelas ? $this->kelas->nama_kelas : '-';
    }

    public function getNamaTahunAjaranAttribute()
    {
        return $this->tahunAjaran ? $this->tahunAjaran->nama_tahun_ajaran : '-';
    }

    // Generate NIS otomatis berdasarkan tanggal_masuk
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Set tanggal masuk default jika belum diisi
            if (empty($model->tanggal_masuk)) {
                $model->tanggal_masuk = now();
            }
            
            // Generate NIS otomatis jika kosong
            if (empty($model->nis)) {
                $model->generateNIS();
            }
            
            // Auto-set formulir dari siswa pendaftar jika ada
            if ($model->siswa_pendaftar_id && empty($model->formulir_path)) {
                $pendaftar = SiswaPendaftar::find($model->siswa_pendaftar_id);
                if ($pendaftar && $pendaftar->akte_kelahiran_path) {
                    $model->formulir_path = $pendaftar->akte_kelahiran_path;
                }
            }
        });
        
        static::updating(function ($model) {
            // Jika tanggal_masuk berubah, update NIS (hanya jika NIS masih kosong)
            if ($model->isDirty('tanggal_masuk') && empty($model->nis)) {
                $model->generateNIS();
            }
            
            // Auto-update formulir jika siswa_pendaftar_id berubah
            if ($model->isDirty('siswa_pendaftar_id') && $model->siswa_pendaftar_id && empty($model->formulir_path)) {
                $pendaftar = SiswaPendaftar::find($model->siswa_pendaftar_id);
                if ($pendaftar && $pendaftar->akte_kelahiran_path) {
                    $model->formulir_path = $pendaftar->akte_kelahiran_path;
                }
            }
        });
    }

    // Method untuk generate NIS
    public function generateNIS()
    {
        // Gunakan tanggal_masuk untuk menentukan bulan dan tahun NIS
        $date = $this->tanggal_masuk ? Carbon::parse($this->tanggal_masuk) : now();
        $year = $date->format('Y');
        $month = $date->format('m');
        
        // Hitung urutan pendaftaran berdasarkan tahun ajaran
        $urutan = $this->getUrutanPendaftaran($date);
        
        // Format: urutan (3 digit) + bulan (2 digit) + tahun (4 digit)
        // Contoh: 001012024 = Urutan 001, Bulan 01 (Januari), Tahun 2024
        $urutanFormatted = str_pad($urutan, 3, '0', STR_PAD_LEFT);
        
        $this->nis = $urutanFormatted . $month . $year;
    }
    
    // Method untuk mendapatkan urutan pendaftaran
    private function getUrutanPendaftaran(Carbon $date)
    {
        // Jika ada tahun ajaran terkait, hitung berdasarkan tahun ajaran
        if ($this->tahun_ajaran_id) {
            $tahunAjaran = TahunAjaran::find($this->tahun_ajaran_id);
            if ($tahunAjaran) {
                // Parse tahun ajaran (misal: "2023/2024")
                $tahunParts = explode('/', $tahunAjaran->nama_tahun_ajaran);
                $tahunStart = isset($tahunParts[0]) ? (int)$tahunParts[0] : null;
                
                if ($tahunStart) {
                    // Cari siswa dengan tahun ajaran yang sama
                    $totalSiswaTahunAjaran = self::where('tahun_ajaran_id', $this->tahun_ajaran_id)->count();
                    return $totalSiswaTahunAjaran + 1;
                }
            }
        }
        
        // Fallback: hitung berdasarkan bulan dan tahun dari tanggal_masuk
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();
        
        $totalSiswaSameMonth = self::whereBetween('tanggal_masuk', [$startDate, $endDate])->count();
        return $totalSiswaSameMonth + 1;
    }
}