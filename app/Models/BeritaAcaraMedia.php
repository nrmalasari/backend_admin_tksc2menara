<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BeritaAcaraMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'berita_acara_id',
        'path',
        'nama_file',
        'urutan',
        'keterangan',
        'is_thumbnail',
    ];

    protected $casts = [
        'is_thumbnail' => 'boolean',
        'urutan' => 'integer',
    ];

    protected $appends = [
        'url',
    ];

    /**
     * Relasi ke BeritaAcara
     */
    public function beritaAcara()
    {
        return $this->belongsTo(BeritaAcara::class);
    }

    /**
     * Set nama_file automatically based on path
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nama_file) && !empty($model->path)) {
                $model->nama_file = basename($model->path);
            }
        });

        static::updating(function ($model) {
            if (empty($model->nama_file) && !empty($model->path)) {
                $model->nama_file = basename($model->path);
            }
        });
    }

    /**
     * Get full URL for image
     */
    public function getUrlAttribute()
    {
        $path = $this->attributes['path'] ?? null;
        
        if (!$path) {
            return null;
        }
        
        // Bersihkan path dari 'storage/' atau 'public/'
        $cleanPath = (string) $path;
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
        
        // Jika tidak, coba langsung return path
        return asset($cleanPath);
    }

    /**
     * Get file extension
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * Get file size in KB
     */
    public function getFileSizeAttribute()
    {
        $cleanPath = $this->path;
        if (strpos($cleanPath, 'storage/') === 0) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (strpos($cleanPath, 'public/') === 0) {
            $cleanPath = substr($cleanPath, 7);
        }
        
        if (Storage::disk('public')->exists($cleanPath)) {
            $size = filesize(storage_path('app/public/' . $cleanPath));
            return round($size / 1024, 2) . ' KB';
        }
        return '0 KB';
    }

    /**
     * Check if image exists
     */
    public function getExistsAttribute()
    {
        $cleanPath = $this->path;
        if (strpos($cleanPath, 'storage/') === 0) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (strpos($cleanPath, 'public/') === 0) {
            $cleanPath = substr($cleanPath, 7);
        }
        
        return Storage::disk('public')->exists($cleanPath);
    }
}