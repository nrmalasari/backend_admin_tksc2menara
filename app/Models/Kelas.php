<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    
    protected $primaryKey = 'id_kelas'; // Tambahkan ini
    
    public $incrementing = true;
    
    protected $keyType = 'int';

    protected $fillable = [
        'nama_kelas',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

     public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}