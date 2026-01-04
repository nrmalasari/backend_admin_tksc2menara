<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FasilitasController extends Controller
{
    /**
     * Mendapatkan semua fasilitas yang dipublikasikan
     */
    public function index(Request $request)
    {
        try {
            Log::info('Mengambil data fasilitas', [
                'params' => $request->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);
            
            // Query dasar
            $query = Fasilitas::query();
            
            // Filter hanya yang dipublikasikan
            if (!$request->has('include_draft') || $request->input('include_draft') !== 'true') {
                $query->where('is_published', true);
            }
            
            // Filter berdasarkan urutan
            $query->orderBy('urutan', 'asc')->orderBy('id', 'asc');
            
            // Dapatkan data
            $fasilitas = $query->get()->map(function ($fasilitas) {
                return $this->formatFasilitas($fasilitas);
            });
            
            Log::info('Data fasilitas berhasil diambil', [
                'total' => $fasilitas->count()
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $fasilitas,
                'message' => 'Data fasilitas berhasil diambil',
                'total' => $fasilitas->count(),
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data fasilitas: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            // Fallback ke data dummy jika error
            $dummyData = $this->getDummyData();
            
            return response()->json([
                'success' => true,
                'data' => $dummyData,
                'message' => 'Data fasilitas berhasil diambil (fallback mode)',
                'total' => count($dummyData),
                'timestamp' => now()->toISOString()
            ]);
        }
    }

    /**
     * Mendapatkan fasilitas terbaru
     */
    public function getLatest(Request $request)
    {
        try {
            $limit = $request->input('limit', 6);
            
            Log::info('Mengambil fasilitas terbaru', [
                'limit' => $limit
            ]);
            
            $fasilitas = Fasilitas::where('is_published', true)
                ->orderBy('tanggal_update', 'desc')
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($fasilitas) {
                    return $this->formatFasilitas($fasilitas);
                });
            
            return response()->json([
                'success' => true,
                'data' => $fasilitas,
                'message' => 'Fasilitas terbaru berhasil diambil',
                'total' => $fasilitas->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil fasilitas terbaru: ' . $e->getMessage());
            
            // Fallback
            $dummyData = $this->getDummyData();
            $limitedData = array_slice($dummyData, 0, min(count($dummyData), 6));
            
            return response()->json([
                'success' => true,
                'data' => $limitedData,
                'message' => 'Fasilitas terbaru berhasil diambil (fallback mode)',
                'total' => count($limitedData)
            ]);
        }
    }

    /**
     * Mendapatkan detail fasilitas
     */
    public function show(Request $request, $id)
    {
        try {
            Log::info('Mengambil detail fasilitas', [
                'id' => $id
            ]);
            
            $fasilitas = Fasilitas::find($id);
            
            if (!$fasilitas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fasilitas tidak ditemukan'
                ], 404);
            }
            
            // Cek apakah dipublikasikan
            if (!$fasilitas->is_published && (!$request->has('include_draft') || $request->input('include_draft') !== 'true')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fasilitas tidak tersedia'
                ], 403);
            }
            
            $data = $this->formatFasilitas($fasilitas);
            
            // Tambah field tambahan untuk detail
            $data['all_images'] = $fasilitas->all_gambar_urls;
            $data['created_at'] = $fasilitas->created_at->format('d/m/Y H:i');
            $data['updated_at'] = $fasilitas->updated_at->format('d/m/Y H:i');
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Detail fasilitas berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil detail fasilitas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail fasilitas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan fasilitas berdasarkan slug (untuk URL yang ramah SEO)
     */
    public function showBySlug(Request $request, $slug)
    {
        try {
            Log::info('Mengambil fasilitas by slug', [
                'slug' => $slug
            ]);
            
            // Convert slug back to normal name
            $name = str_replace('-', ' ', $slug);
            
            $fasilitas = Fasilitas::whereRaw('LOWER(nama_fasilitas) = ?', [strtolower($name)])
                ->orWhereRaw('LOWER(REPLACE(nama_fasilitas, " ", "-")) = ?', [strtolower($slug)])
                ->first();
            
            if (!$fasilitas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fasilitas tidak ditemukan'
                ], 404);
            }
            
            // Cek apakah dipublikasikan
            if (!$fasilitas->is_published && (!$request->has('include_draft') || $request->input('include_draft') !== 'true')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fasilitas tidak tersedia'
                ], 403);
            }
            
            $data = $this->formatFasilitas($fasilitas);
            
            // Tambah field tambahan untuk detail
            $data['all_images'] = $fasilitas->all_gambar_urls;
            $data['created_at'] = $fasilitas->created_at->format('d/m/Y H:i');
            $data['updated_at'] = $fasilitas->updated_at->format('d/m/Y H:i');
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Detail fasilitas berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil fasilitas by slug: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail fasilitas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan jumlah fasilitas (summary)
     */
    public function summary(Request $request)
    {
        try {
            $total = Fasilitas::where('is_published', true)->count();
            $latestUpdate = Fasilitas::where('is_published', true)
                ->orderBy('tanggal_update', 'desc')
                ->first();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_fasilitas' => $total,
                    'terakhir_update' => $latestUpdate ? $latestUpdate->tanggal_update->format('d/m/Y') : '-',
                    'foto_tersedia' => Fasilitas::where('is_published', true)
                        ->whereNotNull('gambar_fasilitas')
                        ->count()
                ],
                'message' => 'Summary fasilitas berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil summary fasilitas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil summary fasilitas'
            ], 500);
        }
    }

    /**
     * Test endpoint untuk debugging
     */
    public function testAll(Request $request)
    {
        try {
            Log::info('Test endpoint fasilitas dipanggil');
            
            $fasilitas = Fasilitas::orderBy('urutan', 'asc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $fasilitas->map(function ($fasilitas) {
                    return [
                        'id' => $fasilitas->id,
                        'nama_fasilitas' => $fasilitas->nama_fasilitas,
                        'gambar_fasilitas' => $fasilitas->gambar_fasilitas,
                        'gambar_url' => $fasilitas->gambar_url,
                        'deskripsi' => $fasilitas->deskripsi,
                        'tanggal_update' => $fasilitas->tanggal_update,
                        'is_published' => $fasilitas->is_published,
                        'urutan' => $fasilitas->urutan,
                        'created_at' => $fasilitas->created_at,
                        'updated_at' => $fasilitas->updated_at
                    ];
                }),
                'count' => $fasilitas->count(),
                'published_count' => $fasilitas->where('is_published', true)->count(),
                'message' => 'Test endpoint berhasil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error test endpoint fasilitas: ' . $e->getMessage());
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
            $fasilitas = Fasilitas::all();
            
            $debugData = [];
            foreach ($fasilitas as $item) {
                $debugData[] = [
                    'id' => $item->id,
                    'nama_fasilitas' => $item->nama_fasilitas,
                    'gambar_fasilitas_raw' => $item->getRawOriginal('gambar_fasilitas'),
                    'gambar_url' => $item->gambar_url,
                    'all_images' => $item->all_gambar_urls,
                    'gambar_exists' => $item->gambar_exists,
                    'deskripsi' => $item->deskripsi,
                    'tanggal_update' => $item->tanggal_update,
                    'is_published' => $item->is_published,
                    'urutan' => $item->urutan,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'storage_info' => $item->gambar_fasilitas ? [
                        'exists_in_storage' => Storage::disk('public')->exists(str_replace('storage/', '', $item->gambar_fasilitas)),
                        'path_in_storage' => $item->gambar_fasilitas,
                        'full_path' => storage_path('app/public/' . str_replace('storage/', '', $item->gambar_fasilitas))
                    ] : null
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $debugData,
                'count' => count($debugData),
                'storage_disk' => 'public',
                'default_image' => asset('images/default-fasilitas.png'),
                'message' => 'Debug data fasilitas'
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
     * Helper function untuk format data fasilitas
     */
    private function formatFasilitas(Fasilitas $fasilitas)
    {
        // Generate slug dari nama fasilitas
        $slug = strtolower(str_replace(' ', '-', $fasilitas->nama_fasilitas));
        
        return [
            'id' => $fasilitas->id,
            'name' => $fasilitas->nama_fasilitas,
            'image' => $fasilitas->gambar_url,
            'thumbnail_url' => $fasilitas->gambar_url,
            'deskripsi' => $fasilitas->deskripsi,
            'short_description' => $fasilitas->deskripsi ? 
                (strlen($fasilitas->deskripsi) > 100 ? substr($fasilitas->deskripsi, 0, 100) . '...' : $fasilitas->deskripsi) 
                : null,
            'tanggal_update' => $fasilitas->tanggal_update ? $fasilitas->tanggal_update->format('d/m/Y') : null,
            'tanggal_update_raw' => $fasilitas->tanggal_update ? $fasilitas->tanggal_update->format('Y-m-d') : null,
            'date_formatted' => $fasilitas->tanggal_update ? $fasilitas->tanggal_update->format('d/m/Y') : 'Belum ada tanggal',
            'is_published' => $fasilitas->is_published,
            'urutan' => $fasilitas->urutan,
            'slug' => $slug,
            'gambar_exists' => $fasilitas->gambar_exists,
            'status_label' => $fasilitas->status_label,
            'status_color' => $fasilitas->status_color
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
                'name' => 'Pojok Baca',
                'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&auto=format&fit=crop',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&auto=format&fit=crop',
                'deskripsi' => 'Area membaca yang nyaman dengan koleksi buku anak-anak',
                'short_description' => 'Area membaca yang nyaman dengan koleksi buku anak-anak',
                'tanggal_update' => '06/04/2025',
                'tanggal_update_raw' => '2025-04-06',
                'date_formatted' => '06/04/2025',
                'is_published' => true,
                'urutan' => 1,
                'slug' => 'pojok-baca',
                'gambar_exists' => true
            ],
            [
                'id' => 2,
                'name' => 'Ruang Kepala Sekolah',
                'image' => 'https://images.unsplash.com/photo-1588072432836-e10032774350?w-800&auto=format&fit=crop',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1588072432836-e10032774350?w-800&auto=format&fit=crop',
                'deskripsi' => 'Ruang kerja kepala sekolah yang representatif',
                'short_description' => 'Ruang kerja kepala sekolah yang representatif',
                'tanggal_update' => '06/04/2025',
                'tanggal_update_raw' => '2025-04-06',
                'date_formatted' => '06/04/2025',
                'is_published' => true,
                'urutan' => 2,
                'slug' => 'ruang-kepala-sekolah',
                'gambar_exists' => true
            ],
            [
                'id' => 3,
                'name' => 'Sentra Persiapan',
                'image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=800&auto=format&fit=crop',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=800&auto=format&fit=crop',
                'deskripsi' => 'Area persiapan belajar dengan fasilitas lengkap',
                'short_description' => 'Area persiapan belajar dengan fasilitas lengkap',
                'tanggal_update' => '06/04/2025',
                'tanggal_update_raw' => '2025-04-06',
                'date_formatted' => '06/04/2025',
                'is_published' => true,
                'urutan' => 3,
                'slug' => 'sentra-persiapan',
                'gambar_exists' => true
            ],
            [
                'id' => 4,
                'name' => 'Taman Bermain',
                'image' => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=800&auto=format&fit=crop',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=800&auto=format&fit=crop',
                'deskripsi' => 'Area bermain yang aman dan edukatif untuk anak',
                'short_description' => 'Area bermain yang aman dan edukatif untuk anak',
                'tanggal_update' => '06/04/2025',
                'tanggal_update_raw' => '2025-04-06',
                'date_formatted' => '06/04/2025',
                'is_published' => true,
                'urutan' => 4,
                'slug' => 'taman-bermain',
                'gambar_exists' => true
            ]
        ];
    }
}