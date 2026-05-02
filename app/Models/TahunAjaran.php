<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_tahun_ajaran', // Hanya satu field
    ];

    public function siswaPendaftars(): HasMany
    {
        return $this->hasMany(SiswaPendaftar::class);
    }

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class);
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    // Hapus method tahunAjaran() yang duplikat karena sudah ada relasi di atas
    // public function tahunAjaran() // HAPUS BARIS INI
    // {
    //     return $this->belongsTo(TahunAjaran::class);
    // }
    
    // Tambahkan method untuk mendapatkan tahun awal dan akhir
    public function getTahunAwalAttribute()
    {
        if (preg_match('/(\d{4})[\/\-](\d{4})/', $this->nama_tahun_ajaran, $matches)) {
            return $matches[1];
        } elseif (preg_match('/(\d{4})/', $this->nama_tahun_ajaran, $matches)) {
            return $matches[1];
        }
        return date('Y');
    }
    
    public function getTahunAkhirAttribute()
    {
        if (preg_match('/(\d{4})[\/\-](\d{4})/', $this->nama_tahun_ajaran, $matches)) {
            return $matches[2];
        } elseif (preg_match('/(\d{4})/', $this->nama_tahun_ajaran, $matches)) {
            return (int)$matches[1] + 1;
        }
        return date('Y') + 1;
    }
}