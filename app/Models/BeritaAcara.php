<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaAcara extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'judul',
        'slug',
        'tanggal_acara',
        'deskripsi',
        'thumbnail',
        'publikasi',
        'tags',
    ];

    protected $casts = [
        'tanggal_acara' => 'date',
        'tags' => 'array',
    ];

    protected $appends = [
        'thumbnail_url',
    ];

    /**
     * Boot method untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Pastikan slug di-generate sebelum validasi
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->judul);
            }
        });
        
        static::updating(function ($model) {
            // Jika judul berubah, generate slug baru
            if ($model->isDirty('judul')) {
                $model->slug = $model->generateUniqueSlug($model->judul, $model->id);
            }
        });
    }

    /**
     * Generate unique slug (termasuk soft deleted)
     */
    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;
        
        // Cek di semua record (termasuk soft deleted)
        while ($this->slugExists($slug, $excludeId, true)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    /**
     * Check if slug exists (termasuk soft deleted)
     */
    private function slugExists($slug, $excludeId = null, $withTrashed = false)
    {
        $query = $withTrashed ? self::withTrashed() : self::query();
        $query->where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Relasi ke multiple media/images
     */
    public function media()
    {
        return $this->hasMany(BeritaAcaraMedia::class)->orderBy('urutan');
    }

    /**
     * Get thumbnail media
     */
    public function thumbnailMedia()
    {
        return $this->hasOne(BeritaAcaraMedia::class)->where('is_thumbnail', true);
    }

    /**
     * Get gallery images (excluding thumbnail)
     */
    public function galleryImages()
    {
        return $this->hasMany(BeritaAcaraMedia::class)->where('is_thumbnail', false)->orderBy('urutan');
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if (empty($this->thumbnail)) {
            return asset('images/default-news.png');
        }
        
        if (strpos($this->thumbnail, 'http') === 0 || strpos($this->thumbnail, '//') === 0) {
            return $this->thumbnail;
        }
        
        $possiblePaths = [
            $this->thumbnail,
            'storage/' . $this->thumbnail,
            'public/' . $this->thumbnail,
            str_replace('storage/', '', $this->thumbnail),
            str_replace('public/', '', $this->thumbnail),
            Storage::url($this->thumbnail),
        ];
        
        foreach ($possiblePaths as $path) {
            $cleanPath = ltrim($path, '/');
            
            if (Storage::disk('public')->exists($cleanPath)) {
                return asset('storage/' . $cleanPath);
            }
            
            $publicPath = public_path($cleanPath);
            if (file_exists($publicPath)) {
                return asset($cleanPath);
            }
            
            $assetPath = asset($cleanPath);
            if ($this->urlExists($assetPath)) {
                return $assetPath;
            }
        }
        
        return asset('images/default-news.png');
    }
    
    /**
     * Helper untuk cek apakah URL ada
     */
    private function urlExists($url)
    {
        $headers = @get_headers($url);
        return $headers && strpos($headers[0], '200') !== false;
    }

    /**
     * Scope untuk berita yang dipublikasikan
     */
    public function scopePublished($query)
    {
        return $query->where('publikasi', 'publik');
    }

    /**
     * Scope untuk berita draft
     */
    public function scopeDraft($query)
    {
        return $query->where('publikasi', 'draft');
    }

    /**
     * Scope untuk berita arsip
     */
    public function scopeArchived($query)
    {
        return $query->where('publikasi', 'arsip');
    }

    /**
     * Scope untuk mencari berdasarkan slug
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get related news
     */
    public function relatedNews($limit = 3)
    {
        // PERBAIKAN: Handle tags yang mungkin string
        $tags = $this->tags;
        
        // Jika tags adalah string, coba konversi ke array
        if (is_string($tags)) {
            $decoded = json_decode($tags, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $tags = $decoded;
            } else {
                // Jika bukan JSON, anggap sebagai string yang dipisahkan koma
                $tags = array_filter(array_map('trim', explode(',', $tags)));
            }
        }
        
        $query = self::published()
            ->where('id', '!=', $this->id);
        
        // Jika ada tags, tambahkan filter
        if (!empty($tags) && is_array($tags)) {
            $query->where(function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    if (!empty($tag) && $tag !== 'null' && $tag !== 'NULL') {
                        $q->orWhereJsonContains('tags', $tag)
                          ->orWhere('tags', 'like', '%"' . $tag . '"%')
                          ->orWhere('tags', 'like', '%' . $tag . '%');
                    }
                }
            });
        }
        
        return $query->orderBy('tanggal_acara', 'desc')
                     ->limit($limit)
                     ->get();
    }

    /**
     * Helper untuk mendapatkan tags sebagai array (dengan normalisasi)
     */
    public function getTagsArrayAttribute()
    {
        $tags = $this->tags;
        
        // Jika tags adalah string, coba konversi ke array
        if (is_string($tags)) {
            $decoded = json_decode($tags, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $tags = $decoded;
            } else {
                // Jika bukan JSON, anggap sebagai string yang dipisahkan koma
                $tags = array_filter(
                    array_map('trim', explode(',', $tags)),
                    function($tag) {
                        return !empty($tag) && $tag !== 'null' && $tag !== 'NULL';
                    }
                );
            }
        }
        
        // Jika masih bukan array, kembalikan array kosong
        if (!is_array($tags)) {
            return [];
        }
        
        // Filter dan trim
        return array_filter(
            array_map('trim', $tags),
            function($tag) {
                return !empty($tag) && $tag !== 'null' && $tag !== 'NULL';
            }
        );
    }
}