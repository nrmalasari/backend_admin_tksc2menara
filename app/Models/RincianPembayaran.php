<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RincianPembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_rincian_pembayaran',
        'total_harga',
        'deskripsi',
        'jenis',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope untuk rincian aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk diurutkan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('id');
    }

    /**
     * Get formatted price
     */
    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    /**
     * Get jenis label
     */
    public function getJenisLabelAttribute()
    {
        $labels = [
            'formulir' => 'Formulir Pendaftaran',
            'uang_bangunan' => 'Uang Bangunan',
            'seragam' => 'Seragam Sekolah',
            'buku' => 'Buku Paket',
            'lainnya' => 'Lainnya',
        ];

        return $labels[$this->jenis] ?? 'Lainnya';
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
}