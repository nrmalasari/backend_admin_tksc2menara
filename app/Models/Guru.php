<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';
    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'nama_lengkap',
        'jabatan',
        'nuptk',
        'foto_path',
        'guru_kelas',
        'kelas_id',
        'status',
        'email',
        'telepon',
        'alamat',
        'pendidikan_terakhir',
        'bidang_studi',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'nuptk' => 'string', // Karena NUPTK bisa panjang
    ];

    protected $appends = [
        'foto_url',
        'status_color',
        'status_text',
        'nama_kelas',
    ];

    // Mutator untuk file upload
    public function setFotoPathAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['foto_path'] = !empty($value) ? $value[0] : null;
        } else {
            $this->attributes['foto_path'] = $value;
        }
    }

    // Accessor untuk form Filament
    protected function fotoPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? [$value] : [],
            set: fn ($value) => is_array($value) && !empty($value) ? $value[0] : null,
        );
    }

    // Relasi ke kelas
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
        
        if (is_array($path)) {
            $path = !empty($path) ? $path[0] : null;
        }
        
        if (!$path) {
            return asset('images/default-avatar.png');
        }
        
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
            'nonaktif' => 'danger',
            'pensiun' => 'warning',
            default => 'secondary',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'aktif' => 'Aktif',
            'nonaktif' => 'Non Aktif',
            'pensiun' => 'Pensiun',
            default => $this->status,
        };
    }

    public function getNamaKelasAttribute()
    {
        return $this->kelas ? $this->kelas->nama_kelas : '-';
    }

    // Auto-update guru_kelas saat kelas_id berubah
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            if ($model->kelas_id) {
                $kelas = Kelas::find($model->kelas_id);
                if ($kelas) {
                    $model->guru_kelas = $kelas->nama_kelas;
                }
            } else {
                $model->guru_kelas = null;
            }
        });
        
        static::updating(function ($model) {
            if ($model->isDirty('kelas_id')) {
                if ($model->kelas_id) {
                    $kelas = Kelas::find($model->kelas_id);
                    if ($kelas) {
                        $model->guru_kelas = $kelas->nama_kelas;
                    }
                } else {
                    $model->guru_kelas = null;
                }
            }
        });
    }
}