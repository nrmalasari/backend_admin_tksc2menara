<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StrukturOrganisasiController extends Controller
{
    /**
     * Mendapatkan semua struktur organisasi yang aktif
     */
    public function index(Request $request)
    {
        try {
            Log::info('Mengambil data struktur organisasi', [
                'params' => $request->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);
            
            // Query dasar
            $query = StrukturOrganisasi::query();
            
            // Filter hanya yang aktif
            if (!$request->has('include_inactive') || $request->input('include_inactive') !== 'true') {
                $query->where('is_active', true);
            }
            
            // Urutkan berdasarkan yang terbaru
            $query->orderBy('created_at', 'desc');
            
            // Dapatkan data
            $strukturs = $query->get()->map(function ($struktur) {
                return $this->formatStruktur($struktur);
            });
            
            Log::info('Data struktur organisasi berhasil diambil', [
                'total' => $strukturs->count()
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $strukturs,
                'message' => 'Data struktur organisasi berhasil diambil',
                'total' => $strukturs->count(),
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data struktur organisasi: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            // Fallback ke data dummy jika error
            $dummyData = $this->getDummyData();
            
            return response()->json([
                'success' => true,
                'data' => $dummyData,
                'message' => 'Data struktur organisasi berhasil diambil (fallback mode)',
                'total' => count($dummyData),
                'timestamp' => now()->toISOString()
            ]);
        }
    }

    /**
     * Mendapatkan struktur organisasi terbaru
     */
    public function getLatest(Request $request)
    {
        try {
            $limit = $request->input('limit', 1); // Default 1 untuk struktur organisasi
            
            Log::info('Mengambil struktur organisasi terbaru', [
                'limit' => $limit
            ]);
            
            $strukturs = StrukturOrganisasi::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($struktur) {
                    return $this->formatStruktur($struktur);
                });
            
            return response()->json([
                'success' => true,
                'data' => $strukturs,
                'message' => 'Struktur organisasi terbaru berhasil diambil',
                'total' => $strukturs->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil struktur organisasi terbaru: ' . $e->getMessage());
            
            // Fallback
            $dummyData = $this->getDummyData();
            $limitedData = array_slice($dummyData, 0, min(count($dummyData), 1));
            
            return response()->json([
                'success' => true,
                'data' => $limitedData,
                'message' => 'Struktur organisasi terbaru berhasil diambil (fallback mode)',
                'total' => count($limitedData)
            ]);
        }
    }

    /**
     * Mendapatkan struktur organisasi aktif (yang sedang digunakan)
     */
    public function getActive(Request $request)
    {
        try {
            Log::info('Mengambil struktur organisasi aktif');
            
            $struktur = StrukturOrganisasi::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$struktur) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Tidak ada struktur organisasi aktif'
                ]);
            }
            
            $data = $this->formatStruktur($struktur);
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Struktur organisasi aktif berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil struktur organisasi aktif: ' . $e->getMessage());
            
            // Fallback ke dummy data pertama
            $dummyData = $this->getDummyData();
            $activeData = !empty($dummyData) ? $dummyData[0] : null;
            
            return response()->json([
                'success' => true,
                'data' => $activeData,
                'message' => 'Struktur organisasi aktif berhasil diambil (fallback mode)'
            ]);
        }
    }

    /**
     * Mendapatkan detail struktur organisasi
     */
    public function show(Request $request, $id)
    {
        try {
            Log::info('Mengambil detail struktur organisasi', [
                'id' => $id
            ]);
            
            $struktur = StrukturOrganisasi::find($id);
            
            if (!$struktur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Struktur organisasi tidak ditemukan'
                ], 404);
            }
            
            // Cek apakah aktif
            if (!$struktur->is_active && (!$request->has('include_inactive') || $request->input('include_inactive') !== 'true')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Struktur organisasi tidak tersedia'
                ], 403);
            }
            
            $data = $this->formatStruktur($struktur);
            
            // Tambah field tambahan untuk detail
            $data['all_images'] = $struktur->all_gambar_urls;
            $data['created_at'] = $struktur->created_at->format('d/m/Y H:i');
            $data['updated_at'] = $struktur->updated_at->format('d/m/Y H:i');
            $data['gambar_exists'] = $struktur->gambar_exists;
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Detail struktur organisasi berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil detail struktur organisasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail struktur organisasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan jumlah struktur organisasi (summary)
     */
    public function summary(Request $request)
    {
        try {
            $total = StrukturOrganisasi::count();
            $active = StrukturOrganisasi::where('is_active', true)->count();
            $latest = StrukturOrganisasi::orderBy('created_at', 'desc')->first();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_struktur' => $total,
                    'struktur_aktif' => $active,
                    'terakhir_update' => $latest ? $latest->updated_at->format('d/m/Y') : '-',
                    'gambar_tersedia' => StrukturOrganisasi::whereNotNull('gambar_struktur')
                        ->where('is_active', true)
                        ->count()
                ],
                'message' => 'Summary struktur organisasi berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil summary struktur organisasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil summary struktur organisasi'
            ], 500);
        }
    }

    /**
     * Test endpoint untuk debugging
     */
    public function testAll(Request $request)
    {
        try {
            Log::info('Test endpoint struktur organisasi dipanggil');
            
            $strukturs = StrukturOrganisasi::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $strukturs->map(function ($struktur) {
                    return [
                        'id' => $struktur->id,
                        'nama_struktur' => $struktur->nama_struktur,
                        'gambar_struktur' => $struktur->gambar_struktur,
                        'gambar_url' => $struktur->gambar_url,
                        'is_active' => $struktur->is_active,
                        'status_label' => $struktur->status_label,
                        'created_at' => $struktur->created_at,
                        'updated_at' => $struktur->updated_at
                    ];
                }),
                'count' => $strukturs->count(),
                'active_count' => $strukturs->where('is_active', true)->count(),
                'message' => 'Test endpoint berhasil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error test endpoint struktur organisasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Test endpoint gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug endpoint
     */
    public function debugAll(Request $request)
    {
        try {
            $strukturs = StrukturOrganisasi::all();
            
            $debugData = [];
            foreach ($strukturs as $item) {
                $debugData[] = [
                    'id' => $item->id,
                    'nama_struktur' => $item->nama_struktur,
                    'gambar_struktur_raw' => $item->getRawOriginal('gambar_struktur'),
                    'gambar_url' => $item->gambar_url,
                    'all_images' => $item->all_gambar_urls,
                    'gambar_exists' => $item->gambar_exists,
                    'is_active' => $item->is_active,
                    'status_label' => $item->status_label,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'storage_info' => $item->gambar_struktur ? [
                        'exists_in_storage' => Storage::disk('public')->exists(str_replace('storage/', '', $item->gambar_struktur)),
                        'path_in_storage' => $item->gambar_struktur,
                        'full_path' => storage_path('app/public/' . str_replace('storage/', '', $item->gambar_struktur))
                    ] : null
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $debugData,
                'count' => count($debugData),
                'storage_disk' => 'public',
                'default_image' => asset('images/default-struktur.png'),
                'message' => 'Debug data struktur organisasi'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Debug endpoint gagal'
            ], 500);
        }
    }

    /**
     * Helper function untuk format data struktur organisasi
     */
    private function formatStruktur(StrukturOrganisasi $struktur)
    {
        return [
            'id' => $struktur->id,
            'name' => $struktur->nama_struktur,
            'image' => $struktur->gambar_url,
            'thumbnail_url' => $struktur->gambar_url,
            'nama_struktur' => $struktur->nama_struktur,
            'is_active' => $struktur->is_active,
            'status_label' => $struktur->status_label,
            'status_color' => $struktur->status_color,
            'created_at' => $struktur->created_at->format('d/m/Y H:i'),
            'updated_at' => $struktur->updated_at->format('d/m/Y H:i'),
            'created_date' => $struktur->created_at->format('d/m/Y'),
            'updated_date' => $struktur->updated_at->format('d/m/Y'),
        ];
    }

    /**
     * Data dummy untuk fallback
     */
    private function getDummyData()
    {
        return [
            [
                'id' => 1,
                'name' => 'Struktur Organisasi TK SC2 Menara Parepare',
                'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&auto=format&fit=crop',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&auto=format&fit=crop',
                'nama_struktur' => 'Struktur Organisasi TK SC2 Menara Parepare',
                'is_active' => true,
                'status_label' => 'Aktif',
                'status_color' => 'success',
                'created_at' => '15/03/2025 10:30',
                'updated_at' => '15/03/2025 10:30',
                'created_date' => '15/03/2025',
                'updated_date' => '15/03/2025',
            ]
        ];
    }
}