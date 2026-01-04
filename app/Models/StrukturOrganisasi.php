<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class StrukturOrganisasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_struktur',
        'gambar_struktur',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'gambar_url',
        'status_label',
        'status_color',
    ];

    /**
     * Get gambar URL attribute
     */
    public function getGambarUrlAttribute()
    {
        $path = $this->attributes['gambar_struktur'] ?? null;
        
        if (empty($path)) {
            return asset('images/default-struktur.png');
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
            return asset('images/default-struktur.png');
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
        return asset('images/default-struktur.png');
    }

    /**
     * Get semua gambar URLs (untuk multiple images)
     */
    public function getAllGambarUrlsAttribute()
    {
        $paths = $this->attributes['gambar_struktur'] ?? null;
        $urls = [];
        
        if (empty($paths)) {
            return [asset('images/default-struktur.png')];
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
                $urls[] = asset('images/default-struktur.png');
            }
        }
        
        return empty($urls) ? [asset('images/default-struktur.png')] : $urls;
    }

    /**
     * Cek apakah gambar ada di storage
     */
    public function getGambarExistsAttribute()
    {
        $path = $this->attributes['gambar_struktur'] ?? null;
        
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
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Non-Aktif';
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute()
    {
        return $this->is_active ? 'success' : 'danger';
    }

    /**
     * Scope untuk struktur yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk diurutkan berdasarkan yang terbaru
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}