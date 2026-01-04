<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class SambutanKepalaSekolah extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sambutan',
        'foto',
    ];

    protected $appends = [
        'foto_url',
    ];

    /**
     * Get foto URL attribute
     */
    public function getFotoUrlAttribute()
    {
        $path = $this->attributes['foto'] ?? null;
        
        if (empty($path)) {
            return asset('images/default-profile.png');
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
        return asset('images/default-profile.png');
    }

    /**
     * Cek apakah foto ada di storage
     */
    public function getFotoExistsAttribute()
    {
        $path = $this->attributes['foto'] ?? null;
        
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
}