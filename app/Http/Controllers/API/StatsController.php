<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\SiswaPendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StatsController extends Controller
{
    /**
     * Get all statistics for dashboard
     */
    public function index(Request $request)
    {
        try {
            Log::info('=== GET STATISTICS API CALLED ===');
            
            // Get statistics
            $stats = [
                // Total siswa (semua status)
                'total_siswa' => $this->getTotalSiswa(),
                
                // Total siswa aktif
                'total_siswa_aktif' => $this->getTotalSiswaAktif(),
                
                // Total siswa lulus
                'total_siswa_lulus' => $this->getTotalSiswaLulus(),
                
                // Total guru (only with jabatan = 'Guru')
                'total_guru' => $this->getTotalGuru(),
                
                // Total guru aktif (only with jabatan = 'Guru' and status aktif)
                'total_guru_aktif' => $this->getTotalGuruAktif(),
                
                // Total pendaftar baru (menunggu verifikasi)
                'total_pendaftar_baru' => $this->getTotalPendaftarBaru(),
                
                // Total pendaftar diverifikasi
                'total_pendaftar_diverifikasi' => $this->getTotalPendaftarDiverifikasi(),
                
                // Total pendaftar ditolak
                'total_pendaftar_ditolak' => $this->getTotalPendaftarDitolak(),
            ];
            
            // Calculate percentages
            $totalPendaftar = $stats['total_pendaftar_baru'] + 
                             $stats['total_pendaftar_diverifikasi'] + 
                             $stats['total_pendaftar_ditolak'];
            
            $stats['percentage_diverifikasi'] = $totalPendaftar > 0 ? 
                round(($stats['total_pendaftar_diverifikasi'] / $totalPendaftar) * 100, 1) : 0;
            
            $stats['percentage_ditolak'] = $totalPendaftar > 0 ? 
                round(($stats['total_pendaftar_ditolak'] / $totalPendaftar) * 100, 1) : 0;
            
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Data statistik berhasil diambil',
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data statistik: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data statistik: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get statistics for home page (simple version)
     */
    public function getHomeStats(Request $request)
    {
        try {
            Log::info('=== GET HOME STATISTICS API CALLED ===');
            
            $stats = [
                'total_siswa' => $this->getTotalSiswaAktif(), // Only active siswa for homepage
                'total_guru' => $this->getTotalGuruAktif(),   // Only active guru with jabatan 'Guru'
                'total_lulus' => $this->getTotalSiswaLulus(), // Students who have graduated
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Data statistik homepage berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data statistik homepage: ' . $e->getMessage());
            
            // Return default values for homepage
            return response()->json([
                'success' => true,
                'data' => [
                    'total_siswa' => 35,    // Default fallback
                    'total_guru' => 2,      // Default fallback
                    'total_lulus' => 1000,  // Default fallback
                ],
                'message' => 'Data statistik (fallback mode)'
            ]);
        }
    }
    
    /**
     * Get statistics by category
     */
    public function getStatsByCategory(Request $request, $category)
    {
        try {
            Log::info('=== GET STATS BY CATEGORY API CALLED ===', ['category' => $category]);
            
            $stat = match($category) {
                'siswa' => ['total_siswa' => $this->getTotalSiswaAktif()],
                'siswa-all' => ['total_siswa' => $this->getTotalSiswa()],
                'siswa-lulus' => ['total_siswa_lulus' => $this->getTotalSiswaLulus()],
                'siswa-aktif' => ['total_siswa_aktif' => $this->getTotalSiswaAktif()],
                'guru' => ['total_guru' => $this->getTotalGuruAktif()],
                'guru-all' => ['total_guru' => $this->getTotalGuru()],
                'pendaftar' => ['total_pendaftar_baru' => $this->getTotalPendaftarBaru()],
                default => null,
            };
            
            if ($stat === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori statistik tidak valid'
                ], 400);
            }
            
            return response()->json([
                'success' => true,
                'data' => $stat,
                'message' => 'Data statistik kategori ' . $category . ' berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data statistik kategori: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data statistik kategori'
            ], 500);
        }
    }
    
    /**
     * Helper methods
     */
    private function getTotalSiswa()
    {
        return Siswa::count();
    }
    
    private function getTotalSiswaAktif()
    {
        return Siswa::where('status', 'aktif')->count();
    }
    
    private function getTotalSiswaLulus()
    {
        return Siswa::where('status', 'lulus')->count();
    }
    
    private function getTotalGuru()
    {
        return Guru::where('jabatan', 'Guru')->count();
    }
    
    private function getTotalGuruAktif()
    {
        return Guru::where('jabatan', 'Guru')
            ->where('status', 'aktif')
            ->count();
    }
    
    private function getTotalPendaftarBaru()
    {
        return SiswaPendaftar::whereIn('status', ['menunggu', 'diproses'])->count();
    }
    
    private function getTotalPendaftarDiverifikasi()
    {
        return SiswaPendaftar::where('status', 'diverifikasi')->count();
    }
    
    private function getTotalPendaftarDitolak()
    {
        return SiswaPendaftar::where('status', 'ditolak')->count();
    }
}