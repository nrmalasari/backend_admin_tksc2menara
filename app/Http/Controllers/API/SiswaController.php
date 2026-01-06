<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    /**
     * Mendapatkan semua data siswa (publik)
     */
    public function index()
    {
        try {
            Log::info('=== GET ALL SISWA API CALLED ===');
            
            // Ambil semua siswa dengan relasi
            $siswas = Siswa::with(['tahunAjaran', 'kelas', 'siswaPendaftar'])
                ->where('status', 'aktif') // Hanya siswa aktif
                ->orderBy('nama_lengkap')
                ->get()
                ->map(function ($siswa) {
                    return [
                        'id' => $siswa->id,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nis' => $siswa->nis,
                        'nsin' => $siswa->nsin,
                        'status' => $siswa->status,
                        'foto_url' => $siswa->foto_url,
                        'formulir_url' => $siswa->formulir_url,
                        'tanggal_masuk' => $siswa->tanggal_masuk ? $siswa->tanggal_masuk->format('d/m/Y') : null,
                        'tanggal_keluar' => $siswa->tanggal_keluar ? $siswa->tanggal_keluar->format('d/m/Y') : null,
                        'asal_sekolah' => $siswa->asal_sekolah,
                        'tahun_ajaran_id' => $siswa->tahun_ajaran_id,
                        'tahun_ajaran' => $siswa->tahunAjaran ? [
                            'id' => $siswa->tahunAjaran->id,
                            'nama_tahun_ajaran' => $siswa->tahunAjaran->nama_tahun_ajaran,
                            'semester' => $siswa->tahunAjaran->semester,
                        ] : null,
                        'kelas_id' => $siswa->kelas_id,
                        'kelas' => $siswa->kelas ? [
                            'id_kelas' => $siswa->kelas->id_kelas,
                            'nama_kelas' => $siswa->kelas->nama_kelas,
                            'tingkat' => $siswa->kelas->tingkat,
                        ] : null,
                        'siswa_pendaftar_id' => $siswa->siswa_pendaftar_id,
                        'created_at' => $siswa->created_at->format('d/m/Y H:i'),
                        'updated_at' => $siswa->updated_at->format('d/m/Y H:i'),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $siswas,
                'message' => 'Data siswa berhasil diambil',
                'count' => $siswas->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data siswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data siswa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan siswa berdasarkan tahun ajaran
     */
    public function getByTahunAjaran($tahunAjaranId)
    {
        try {
            Log::info('=== GET SISWA BY TAHUN AJARAN API CALLED ===', ['tahun_ajaran_id' => $tahunAjaranId]);
            
            $siswas = Siswa::with(['tahunAjaran', 'kelas'])
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get()
                ->map(function ($siswa) {
                    return [
                        'id' => $siswa->id,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nis' => $siswa->nis,
                        'foto_url' => $siswa->foto_url,
                        'tahun_ajaran_id' => $siswa->tahun_ajaran_id,
                        'tahun_ajaran' => $siswa->tahunAjaran ? [
                            'nama_tahun_ajaran' => $siswa->tahunAjaran->nama_tahun_ajaran,
                        ] : null,
                        'kelas' => $siswa->kelas ? [
                            'nama_kelas' => $siswa->kelas->nama_kelas,
                        ] : null,
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $siswas,
                'message' => 'Data siswa berdasarkan tahun ajaran berhasil diambil',
                'count' => $siswas->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data siswa by tahun ajaran: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data siswa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan hanya siswa aktif
     */
    public function getAktif()
    {
        try {
            Log::info('=== GET SISWA AKTIF API CALLED ===');
            
            $siswas = Siswa::with(['tahunAjaran', 'kelas'])
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get()
                ->map(function ($siswa) {
                    return [
                        'id' => $siswa->id,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nis' => $siswa->nis,
                        'foto_url' => $siswa->foto_url,
                        'tahun_ajaran' => $siswa->tahunAjaran ? [
                            'nama_tahun_ajaran' => $siswa->tahunAjaran->nama_tahun_ajaran,
                        ] : null,
                        'kelas' => $siswa->kelas ? [
                            'nama_kelas' => $siswa->kelas->nama_kelas,
                        ] : null,
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $siswas,
                'message' => 'Data siswa aktif berhasil diambil',
                'count' => $siswas->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data siswa aktif: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data siswa aktif: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan detail siswa
     */
    public function show($id)
    {
        try {
            $siswa = Siswa::with(['tahunAjaran', 'kelas', 'siswaPendaftar'])
                ->find($id);
            
            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $siswa->id,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'nis' => $siswa->nis,
                    'nsin' => $siswa->nsin,
                    'status' => $siswa->status,
                    'foto_url' => $siswa->foto_url,
                    'formulir_url' => $siswa->formulir_url,
                    'tanggal_masuk' => $siswa->tanggal_masuk ? $siswa->tanggal_masuk->format('d/m/Y') : null,
                    'tanggal_keluar' => $siswa->tanggal_keluar ? $siswa->tanggal_keluar->format('d/m/Y') : null,
                    'asal_sekolah' => $siswa->asal_sekolah,
                    'alasan_keluar' => $siswa->alasan_keluar,
                    'tahun_ajaran' => $siswa->tahunAjaran ? [
                        'id' => $siswa->tahunAjaran->id,
                        'nama_tahun_ajaran' => $siswa->tahunAjaran->nama_tahun_ajaran,
                    ] : null,
                    'kelas' => $siswa->kelas ? [
                        'id_kelas' => $siswa->kelas->id_kelas,
                        'nama_kelas' => $siswa->kelas->nama_kelas,
                        'tingkat' => $siswa->kelas->tingkat,
                    ] : null,
                    'siswa_pendaftar' => $siswa->siswaPendaftar ? [
                        'id' => $siswa->siswaPendaftar->id,
                        'nama_lengkap' => $siswa->siswaPendaftar->nama_lengkap,
                        'nik' => $siswa->siswaPendaftar->nik_decrypted,
                    ] : null,
                ],
                'message' => 'Detail siswa berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil detail siswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail siswa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get siswa statistics
     */
    public function getStats(Request $request)
    {
        try {
            Log::info('=== GET SISWA STATS API CALLED ===');
            
            $stats = [
                'total_siswa' => Siswa::count(),
                'total_siswa_aktif' => Siswa::where('status', 'aktif')->count(),
                'total_siswa_lulus' => Siswa::where('status', 'lulus')->count(),
                'total_siswa_pindah' => Siswa::where('status', 'pindah')->count(),
                'kelas_distribution' => $this->getKelasDistribution(),
                'tahun_ajaran_distribution' => $this->getTahunAjaranDistribution(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Data statistik siswa berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil statistik siswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik siswa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get kelas distribution
     */
    private function getKelasDistribution()
    {
        $distributions = Siswa::with('kelas')
            ->select('kelas_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('kelas_id')
            ->get()
            ->map(function ($item) {
                return [
                    'kelas_id' => $item->kelas_id,
                    'kelas_nama' => $item->kelas ? $item->kelas->nama_kelas : 'Tidak Ada',
                    'count' => $item->count
                ];
            });
        
        return $distributions;
    }

    /**
     * Get tahun ajaran distribution
     */
    private function getTahunAjaranDistribution()
    {
        $distributions = Siswa::with('tahunAjaran')
            ->select('tahun_ajaran_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('tahun_ajaran_id')
            ->get()
            ->map(function ($item) {
                return [
                    'tahun_ajaran_id' => $item->tahun_ajaran_id,
                    'tahun_ajaran_nama' => $item->tahunAjaran ? $item->tahunAjaran->nama_tahun_ajaran : 'Tidak Ada',
                    'count' => $item->count
                ];
            });
        
        return $distributions;
    }
}