<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    /**
     * Mendapatkan semua guru yang aktif
     */
    public function index(Request $request)
    {
        try {
            Log::info('Mengambil data semua guru');
            
            // Ambil semua guru, urutkan: kepala sekolah dulu, kemudian nama
            $gurus = Guru::orderByRaw("
                CASE 
                    WHEN LOWER(jabatan) LIKE '%kepala%' THEN 1
                    WHEN LOWER(jabatan) LIKE '%kasek%' THEN 1
                    WHEN LOWER(jabatan) LIKE '%headmaster%' THEN 1
                    WHEN LOWER(jabatan) LIKE '%principal%' THEN 1
                    ELSE 2 
                END
            ")
            ->orderBy('nama_lengkap', 'asc')
            ->get()
            ->map(function ($guru) {
                return [
                    'id_guru' => $guru->id_guru,
                    'nama_lengkap' => $guru->nama_lengkap,
                    'jabatan' => $guru->jabatan,
                    'nuptk' => $guru->nuptk,
                    'foto_url' => $guru->foto_url,
                    'guru_kelas' => $guru->guru_kelas,
                    'nama_kelas' => $guru->nama_kelas,
                    'status' => $guru->status,
                    'status_text' => $guru->status_text,
                    'email' => $guru->email,
                    'telepon' => $guru->telepon,
                    'alamat' => $guru->alamat,
                    'pendidikan_terakhir' => $guru->pendidikan_terakhir,
                    'bidang_studi' => $guru->bidang_studi,
                    'tanggal_mulai' => $guru->tanggal_mulai,
                    'tanggal_selesai' => $guru->tanggal_selesai,
                    // Hapus field yang tidak ada:
                    // 'gelar_depan' => $guru->gelar_depan ?? null,
                    // 'gelar_belakang' => $guru->gelar_belakang ?? null,
                    // 'pengalaman_kerja' => $guru->pengalaman_kerja ?? null,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $gurus,
                'message' => 'Data guru berhasil diambil',
                'total' => $gurus->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data guru: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data guru: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan guru aktif saja
     */
    public function getAktif(Request $request)
    {
        try {
            Log::info('Mengambil data guru aktif');
            
            // Ambil guru dengan status aktif saja
            $gurus = Guru::where('status', 'aktif')
                ->orderByRaw("
                    CASE 
                        WHEN LOWER(jabatan) LIKE '%kepala%' THEN 1
                        WHEN LOWER(jabatan) LIKE '%kasek%' THEN 1
                        WHEN LOWER(jabatan) LIKE '%headmaster%' THEN 1
                        WHEN LOWER(jabatan) LIKE '%principal%' THEN 1
                        ELSE 2 
                    END
                ")
                ->orderBy('nama_lengkap', 'asc')
                ->get()
                ->map(function ($guru) {
                    return [
                        'id_guru' => $guru->id_guru,
                        'nama_lengkap' => $guru->nama_lengkap,
                        'jabatan' => $guru->jabatan,
                        'foto_url' => $guru->foto_url,
                        'guru_kelas' => $guru->guru_kelas,
                        'nama_kelas' => $guru->nama_kelas,
                        'status' => $guru->status,
                        'pendidikan_terakhir' => $guru->pendidikan_terakhir,
                        'bidang_studi' => $guru->bidang_studi,
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $gurus,
                'message' => 'Data guru aktif berhasil diambil',
                'total' => $gurus->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data guru aktif: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data guru aktif: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan detail guru
     */
    public function show(Request $request, $id)
    {
        try {
            Log::info('Mengambil detail guru ID: ' . $id);
            
            $guru = Guru::find($id);
            
            if (!$guru) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru tidak ditemukan'
                ], 404);
            }
            
            $data = [
                'id_guru' => $guru->id_guru,
                'nama_lengkap' => $guru->nama_lengkap,
                'jabatan' => $guru->jabatan,
                'nuptk' => $guru->nuptk,
                'foto_url' => $guru->foto_url,
                'guru_kelas' => $guru->guru_kelas,
                'nama_kelas' => $guru->nama_kelas,
                'status' => $guru->status,
                'status_text' => $guru->status_text,
                'status_color' => $guru->status_color,
                'email' => $guru->email,
                'telepon' => $guru->telepon,
                'alamat' => $guru->alamat,
                'pendidikan_terakhir' => $guru->pendidikan_terakhir,
                'bidang_studi' => $guru->bidang_studi,
                'tanggal_mulai' => $guru->tanggal_mulai,
                'tanggal_selesai' => $guru->tanggal_selesai,
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Detail guru berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil detail guru: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail guru: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get guru statistics
     */
    public function getStats(Request $request)
    {
        try {
            Log::info('=== GET GURU STATS API CALLED ===');
            
            $stats = [
                'total_guru' => Guru::where('jabatan', 'Guru')->count(),
                'total_guru_aktif' => Guru::where('jabatan', 'Guru')
                    ->where('status', 'aktif')
                    ->count(),
                'total_guru_nonaktif' => Guru::where('jabatan', 'Guru')
                    ->where('status', 'nonaktif')
                    ->count(),
                'total_guru_pensiun' => Guru::where('jabatan', 'Guru')
                    ->where('status', 'pensiun')
                    ->count(),
                'jabatan_distribution' => $this->getJabatanDistribution(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Data statistik guru berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil statistik guru: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik guru: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get jabatan distribution
     */
    private function getJabatanDistribution()
    {
        $distributions = Guru::select('jabatan')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('jabatan')
            ->get()
            ->map(function ($item) {
                return [
                    'jabatan' => $item->jabatan,
                    'count' => $item->count
                ];
            });
        
        return $distributions;
    }
}