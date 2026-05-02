<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TahunAjaranController extends Controller
{
    /**
     * Mendapatkan semua tahun ajaran
     */
    public function index(Request $request)
    {
        try {
            Log::info('Mengambil data semua tahun ajaran');
            
            $tahunAjarans = TahunAjaran::orderBy('created_at', 'desc')
                ->get()
                ->map(function ($tahunAjaran) {
                    return [
                        'id' => $tahunAjaran->id,
                        'nama_tahun_ajaran' => $tahunAjaran->nama_tahun_ajaran,
                        'created_at' => $tahunAjaran->created_at ? $tahunAjaran->created_at->format('d/m/Y H:i') : null,
                        'updated_at' => $tahunAjaran->updated_at ? $tahunAjaran->updated_at->format('d/m/Y H:i') : null,
                        'created_date' => $tahunAjaran->created_at ? $tahunAjaran->created_at->format('d/m/Y') : null,
                        'updated_date' => $tahunAjaran->updated_at ? $tahunAjaran->updated_at->format('d/m/Y') : null,
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $tahunAjarans,
                'message' => 'Data tahun ajaran berhasil diambil',
                'total' => $tahunAjarans->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data tahun ajaran: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data tahun ajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan tahun ajaran terbaru
     */
    public function getLatest(Request $request)
    {
        try {
            Log::info('Mengambil tahun ajaran terbaru');
            
            $tahunAjaran = TahunAjaran::orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            
            if (!$tahunAjaran) {
                // Jika tidak ada tahun ajaran, buat data default
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => 1,
                        'nama_tahun_ajaran' => date('Y') . ' / ' . (date('Y') + 1),
                        'tahun_awal' => date('Y'),
                        'tahun_akhir' => date('Y') + 1,
                        'created_at' => now()->format('d/m/Y H:i'),
                        'updated_at' => now()->format('d/m/Y H:i'),
                        'created_date' => now()->format('d/m/Y'),
                        'updated_date' => now()->format('d/m/Y'),
                    ],
                    'message' => 'Menggunakan tahun ajaran default'
                ]);
            }
            
            // Ekstrak tahun dari nama tahun ajaran
            $nama = $tahunAjaran->nama_tahun_ajaran;
            $tahunAwal = date('Y');
            $tahunAkhir = date('Y') + 1;
            
            // Coba ekstrak tahun dari string (format: "2024/2025" atau "Tahun Ajaran 2024/2025")
            if (preg_match('/(\d{4})[\/\-](\d{4})/', $nama, $matches)) {
                $tahunAwal = $matches[1];
                $tahunAkhir = $matches[2];
            } elseif (preg_match('/(\d{4})/', $nama, $matches)) {
                $tahunAwal = $matches[1];
                $tahunAkhir = (int)$matches[1] + 1;
            }
            
            $data = [
                'id' => $tahunAjaran->id,
                'nama_tahun_ajaran' => $tahunAjaran->nama_tahun_ajaran,
                'tahun_awal' => $tahunAwal,
                'tahun_akhir' => $tahunAkhir,
                'created_at' => $tahunAjaran->created_at ? $tahunAjaran->created_at->format('d/m/Y H:i') : null,
                'updated_at' => $tahunAjaran->updated_at ? $tahunAjaran->updated_at->format('d/m/Y H:i') : null,
                'created_date' => $tahunAjaran->created_at ? $tahunAjaran->created_at->format('d/m/Y') : null,
                'updated_date' => $tahunAjaran->updated_at ? $tahunAjaran->updated_at->format('d/m/Y') : null,
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Tahun ajaran terbaru berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil tahun ajaran terbaru: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil tahun ajaran terbaru: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan tahun ajaran aktif
     */
    public function getActive(Request $request)
    {
        try {
            Log::info('Mengambil tahun ajaran aktif');
            
            // Dalam contoh ini, anggap tahun ajaran terbaru adalah yang aktif
            $tahunAjaran = TahunAjaran::orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            
            if (!$tahunAjaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada tahun ajaran yang tersedia'
                ], 404);
            }
            
            // Ekstrak tahun dari nama tahun ajaran
            $nama = $tahunAjaran->nama_tahun_ajaran;
            $tahunAwal = date('Y');
            $tahunAkhir = date('Y') + 1;
            
            if (preg_match('/(\d{4})[\/\-](\d{4})/', $nama, $matches)) {
                $tahunAwal = $matches[1];
                $tahunAkhir = $matches[2];
            } elseif (preg_match('/(\d{4})/', $nama, $matches)) {
                $tahunAwal = $matches[1];
                $tahunAkhir = (int)$matches[1] + 1;
            }
            
            $data = [
                'id' => $tahunAjaran->id,
                'nama_tahun_ajaran' => $tahunAjaran->nama_tahun_ajaran,
                'tahun_awal' => $tahunAwal,
                'tahun_akhir' => $tahunAkhir,
                'is_active' => true,
                'created_at' => $tahunAjaran->created_at ? $tahunAjaran->created_at->format('d/m/Y H:i') : null,
                'updated_at' => $tahunAjaran->updated_at ? $tahunAjaran->updated_at->format('d/m/Y H:i') : null,
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Tahun ajaran aktif berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil tahun ajaran aktif: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil tahun ajaran aktif: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan detail tahun ajaran by ID
     */
    public function show(Request $request, $id)
    {
        try {
            Log::info('Mengambil detail tahun ajaran ID: ' . $id);
            
            $tahunAjaran = TahunAjaran::find($id);
            
            if (!$tahunAjaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tahun ajaran tidak ditemukan'
                ], 404);
            }
            
            // Ekstrak tahun dari nama tahun ajaran
            $nama = $tahunAjaran->nama_tahun_ajaran;
            $tahunAwal = date('Y');
            $tahunAkhir = date('Y') + 1;
            
            if (preg_match('/(\d{4})[\/\-](\d{4})/', $nama, $matches)) {
                $tahunAwal = $matches[1];
                $tahunAkhir = $matches[2];
            } elseif (preg_match('/(\d{4})/', $nama, $matches)) {
                $tahunAwal = $matches[1];
                $tahunAkhir = (int)$matches[1] + 1;
            }
            
            $data = [
                'id' => $tahunAjaran->id,
                'nama_tahun_ajaran' => $tahunAjaran->nama_tahun_ajaran,
                'tahun_awal' => $tahunAwal,
                'tahun_akhir' => $tahunAkhir,
                'created_at' => $tahunAjaran->created_at ? $tahunAjaran->created_at->format('d/m/Y H:i') : null,
                'updated_at' => $tahunAjaran->updated_at ? $tahunAjaran->updated_at->format('d/m/Y H:i') : null,
                'created_date' => $tahunAjaran->created_at ? $tahunAjaran->created_at->format('d/m/Y') : null,
                'updated_date' => $tahunAjaran->updated_at ? $tahunAjaran->updated_at->format('d/m/Y') : null,
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Detail tahun ajaran berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil detail tahun ajaran: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail tahun ajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan statistik tahun ajaran
     */
    public function getStats(Request $request)
    {
        try {
            Log::info('Mengambil statistik tahun ajaran');
            
            $totalTahunAjaran = TahunAjaran::count();
            $latestTahunAjaran = TahunAjaran::orderBy('created_at', 'desc')->first();
            
            $stats = [
                'total_tahun_ajaran' => $totalTahunAjaran,
                'latest_tahun_ajaran' => $latestTahunAjaran ? $latestTahunAjaran->nama_tahun_ajaran : null,
                'latest_id' => $latestTahunAjaran ? $latestTahunAjaran->id : null,
                'current_year' => date('Y'),
                'next_year' => date('Y') + 1,
                'default_tahun_ajaran' => date('Y') . ' / ' . (date('Y') + 1),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistik tahun ajaran berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil statistik tahun ajaran: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik tahun ajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint untuk debugging
     */
    public function testAll(Request $request)
    {
        try {
            Log::info('Test: Mengambil semua data tahun ajaran (debug mode)');
            
            $tahunAjarans = TahunAjaran::all();
            
            $formattedData = $tahunAjarans->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_tahun_ajaran' => $item->nama_tahun_ajaran,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'count' => $tahunAjarans->count(),
                'message' => 'Test endpoint berhasil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Test endpoint error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Test endpoint gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}