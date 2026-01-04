<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Fasilitas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_fasilitas',
        'gambar_fasilitas',
        'tanggal_update',
        'deskripsi',
        'is_published',
        'urutan',
    ];

    protected $casts = [
        'tanggal_update' => 'date',
        'is_published' => 'boolean',
        'urutan' => 'integer',
    ];

    protected $appends = [
        'gambar_url',
        'status_label',
        'status_color',
        'formatted_tanggal_update',
    ];

    /**
     * Accessor untuk gambar_fasilitas (untuk Filament/Form)
     */
    protected function gambarFasilitas(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // Jika value adalah string JSON, decode
                if (is_string($value) && strpos($value, '[') !== false) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        return $decoded;
                    }
                }
                
                // Jika value adalah array, return langsung
                if (is_array($value)) {
                    return $value;
                }
                
                // Jika value adalah string (single path), wrap dalam array
                return $value ? [$value] : [];
            },
            set: function ($value) {
                if (is_null($value) || $value === '') {
                    return null;
                }
                
                // Jika value adalah array dari Filament
                if (is_array($value)) {
                    // Filter null/empty values
                    $value = array_filter($value, function($item) {
                        return $item !== null && $item !== '';
                    });
                    
                    // Jika array kosong setelah filter, return null
                    if (empty($value)) {
                        return null;
                    }
                    
                    // Ambil gambar pertama (jika multiple)
                    $firstImage = $value[0];
                    
                    // Jika gambar adalah instance UploadedFile (upload baru)
                    if (is_object($firstImage) && method_exists($firstImage, 'store')) {
                        // Simpan di folder fasilitas
                        $path = $firstImage->store('fasilitas', 'public');
                        return $path;
                    }
                    
                    // Jika gambar adalah path string
                    if (is_string($firstImage)) {
                        // Cek apakah path sudah ada di storage
                        if (strpos($firstImage, 'storage/') === 0) {
                            $firstImage = substr($firstImage, 8);
                        }
                        return $firstImage;
                    }
                }
                
                // Jika value adalah string langsung
                if (is_string($value)) {
                    // Cek apakah string mengandung path storage
                    if (strpos($value, 'storage/') === 0) {
                        $value = substr($value, 8);
                    }
                    return $value;
                }
                
                // Default: simpan sebagai string kosong
                return null;
            }
        );
    }

    /**
     * Get gambar URL attribute
     */
    public function getGambarUrlAttribute()
    {
        $path = $this->attributes['gambar_fasilitas'] ?? null;
        
        if (empty($path)) {
            return asset('images/default-fasilitas.png'); // PERBAIKAN: .png bukan .jpg
        }
        
        // Handle jika path adalah JSON array
        if (is_string($path) && strpos($path, '[') !== false) {
            $decoded = json_decode($path, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                $path = $decoded[0];
            }
        }
        
        // Jika path adalah array, ambil yang pertama
        if (is_array($path) && !empty($path)) {
            $path = $path[0];
        }
        
        if (empty($path)) {
            return asset('images/default-fasilitas.png');
        }
        
        // Clean path dari prefix storage/ atau public/
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
        
        // Coba cek di public path
        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }
        
        // Coba cek sebagai URL absolute
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        // Fallback ke default
        return asset('images/default-fasilitas.png');
    }

    /**
     * Get semua gambar URLs (untuk multiple images)
     */
    public function getAllGambarUrlsAttribute()
    {
        $paths = $this->attributes['gambar_fasilitas'] ?? null;
        $urls = [];
        
        if (empty($paths)) {
            return [asset('images/default-fasilitas.png')];
        }
        
        // Handle jika paths adalah JSON array
        if (is_string($paths) && strpos($paths, '[') !== false) {
            $decoded = json_decode($paths, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $paths = $decoded;
            } else {
                $paths = [$paths];
            }
        }
        
        // Jika bukan array, buat array
        if (!is_array($paths)) {
            $paths = [$paths];
        }
        
        // Filter null/empty values
        $paths = array_filter($paths, function($path) {
            return !empty($path);
        });
        
        // Generate URLs untuk setiap path
        foreach ($paths as $path) {
            $cleanPath = $path;
            if (strpos($cleanPath, 'storage/') === 0) {
                $cleanPath = substr($cleanPath, 8);
            }
            if (strpos($cleanPath, 'public/') === 0) {
                $cleanPath = substr($cleanPath, 7);
            }
            
            if (Storage::disk('public')->exists($cleanPath)) {
                $urls[] = asset('storage/' . $cleanPath);
            } elseif (file_exists(public_path($cleanPath))) {
                $urls[] = asset($cleanPath);
            } elseif (filter_var($path, FILTER_VALIDATE_URL)) {
                $urls[] = $path;
            } else {
                $urls[] = asset('images/default-fasilitas.png');
            }
        }
        
        return empty($urls) ? [asset('images/default-fasilitas.png')] : $urls;
    }

    /**
     * Cek apakah gambar ada di storage
     */
    public function getGambarExistsAttribute()
    {
        $path = $this->attributes['gambar_fasilitas'] ?? null;
        
        if (empty($path)) {
            return false;
        }
        
        // Handle jika path adalah JSON array
        if (is_string($path) && strpos($path, '[') !== false) {
            $decoded = json_decode($path, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                $path = $decoded[0];
            }
        }
        
        // Jika path adalah array, ambil yang pertama
        if (is_array($path) && !empty($path)) {
            $path = $path[0];
        }
        
        if (empty($path)) {
            return false;
        }
        
        // Clean path dari prefix storage/ atau public/
        $cleanPath = $path;
        if (strpos($cleanPath, 'storage/') === 0) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (strpos($cleanPath, 'public/') === 0) {
            $cleanPath = substr($cleanPath, 7);
        }
        
        // Cek apakah file ada di storage
        if (Storage::disk('public')->exists($cleanPath)) {
            return true;
        }
        
        // Coba cek di public path
        if (file_exists(public_path($cleanPath))) {
            return true;
        }
        
        // Coba cek sebagai URL absolute
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return true;
        }
        
        return false;
    }

    /**
     * Get formatted tanggal update
     */
    public function getFormattedTanggalUpdateAttribute()
    {
        return $this->tanggal_update 
            ? $this->tanggal_update->format('d/m/Y') 
            : 'Belum ada tanggal';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return $this->is_published ? 'Dipublikasikan' : 'Draft';
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute()
    {
        return $this->is_published ? 'success' : 'danger';
    }

    /**
     * Scope untuk fasilitas yang dipublikasikan
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope untuk diurutkan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('id');
    }

    /**
     * Boot method untuk handle events
     */
    protected static function boot()
    {
        parent::boot();
        
        // Set tanggal_update ke tanggal sekarang ketika membuat record baru
        static::creating(function ($model) {
            if (empty($model->tanggal_update)) {
                $model->tanggal_update = now();
            }
        });
        
        // Update tanggal_update ketika mengupdate record
        static::updating(function ($model) {
            $model->tanggal_update = now();
        });
    }
}