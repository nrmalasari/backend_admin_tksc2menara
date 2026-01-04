<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SambutanKepalaSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SambutanKepalaSekolahController extends Controller
{
    /**
     * Mendapatkan semua sambutan kepala sekolah
     */
    public function index(Request $request)
    {
        try {
            Log::info('Mengambil data sambutan kepala sekolah', [
                'params' => $request->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);
            
            // Query dasar - ambil semua sambutan yang belum dihapus
            $query = SambutanKepalaSekolah::query();
            
            // Tambah dengan data yang dihapus juga jika diperlukan
            if ($request->has('with_trashed') && $request->input('with_trashed') === 'true') {
                $query->withTrashed();
            }
            
            // Urutkan berdasarkan yang terbaru
            $query->orderBy('created_at', 'desc');
            
            // Dapatkan data
            $sambutans = $query->get()->map(function ($sambutan) {
                return $this->formatSambutan($sambutan);
            });
            
            Log::info('Data sambutan kepala sekolah berhasil diambil', [
                'total' => $sambutans->count()
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $sambutans,
                'message' => 'Data sambutan kepala sekolah berhasil diambil',
                'total' => $sambutans->count(),
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data sambutan kepala sekolah: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            // Fallback ke data dummy jika error
            $dummyData = $this->getDummyData();
            
            return response()->json([
                'success' => true,
                'data' => $dummyData,
                'message' => 'Data sambutan kepala sekolah berhasil diambil (fallback mode)',
                'total' => count($dummyData),
                'timestamp' => now()->toISOString()
            ]);
        }
    }

    /**
     * Mendapatkan sambutan terbaru
     */
    public function getLatest(Request $request)
    {
        try {
            $limit = $request->input('limit', 1); // Default 1 untuk sambutan
            
            Log::info('Mengambil sambutan kepala sekolah terbaru', [
                'limit' => $limit
            ]);
            
            $sambutans = SambutanKepalaSekolah::orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($sambutan) {
                    return $this->formatSambutan($sambutan);
                });
            
            return response()->json([
                'success' => true,
                'data' => $sambutans,
                'message' => 'Sambutan kepala sekolah terbaru berhasil diambil',
                'total' => $sambutans->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil sambutan kepala sekolah terbaru: ' . $e->getMessage());
            
            // Fallback
            $dummyData = $this->getDummyData();
            $limitedData = array_slice($dummyData, 0, min(count($dummyData), 1));
            
            return response()->json([
                'success' => true,
                'data' => $limitedData,
                'message' => 'Sambutan kepala sekolah terbaru berhasil diambil (fallback mode)',
                'total' => count($limitedData)
            ]);
        }
    }

    /**
     * Mendapatkan sambutan terbaru yang aktif (default untuk website)
     */
    public function getActive(Request $request)
    {
        try {
            Log::info('Mengambil sambutan kepala sekolah terbaru untuk website');
            
            $sambutan = SambutanKepalaSekolah::orderBy('created_at', 'desc')
                ->first();
            
            if (!$sambutan) {
                // Jika tidak ada data, kembalikan null
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Tidak ada sambutan kepala sekolah'
                ]);
            }
            
            $data = $this->formatSambutan($sambutan);
            
            // Tambah informasi tambahan untuk frontend
            $data['deleted_at'] = $sambutan->deleted_at ? $sambutan->deleted_at->format('d/m/Y H:i') : null;
            $data['is_deleted'] = !is_null($sambutan->deleted_at);
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Sambutan kepala sekolah berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil sambutan kepala sekolah: ' . $e->getMessage());
            
            // Fallback ke dummy data pertama
            $dummyData = $this->getDummyData();
            $activeData = !empty($dummyData) ? $dummyData[0] : null;
            
            return response()->json([
                'success' => true,
                'data' => $activeData,
                'message' => 'Sambutan kepala sekolah berhasil diambil (fallback mode)'
            ]);
        }
    }

    /**
     * Mendapatkan detail sambutan kepala sekolah
     */
    public function show(Request $request, $id)
    {
        try {
            Log::info('Mengambil detail sambutan kepala sekolah', [
                'id' => $id
            ]);
            
            $sambutan = SambutanKepalaSekolah::withTrashed()->find($id);
            
            if (!$sambutan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sambutan kepala sekolah tidak ditemukan'
                ], 404);
            }
            
            $data = $this->formatSambutan($sambutan);
            
            // Tambah field tambahan untuk detail
            $data['created_at'] = $sambutan->created_at->format('d/m/Y H:i');
            $data['updated_at'] = $sambutan->updated_at->format('d/m/Y H:i');
            $data['deleted_at'] = $sambutan->deleted_at ? $sambutan->deleted_at->format('d/m/Y H:i') : null;
            $data['foto_exists'] = $sambutan->foto_exists;
            $data['is_deleted'] = !is_null($sambutan->deleted_at);
            
            // Data asli dari database
            $data['sambutan_raw'] = $sambutan->sambutan;
            $data['foto_raw'] = $sambutan->foto;
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Detail sambutan kepala sekolah berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil detail sambutan kepala sekolah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail sambutan kepala sekolah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan jumlah sambutan (summary)
     */
    public function summary(Request $request)
    {
        try {
            $total = SambutanKepalaSekolah::withTrashed()->count();
            $active = SambutanKepalaSekolah::count(); // Hanya yang belum dihapus
            $deleted = $total - $active;
            $latest = SambutanKepalaSekolah::orderBy('created_at', 'desc')->first();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_sambutan' => $total,
                    'sambutan_aktif' => $active,
                    'sambutan_dihapus' => $deleted,
                    'terakhir_update' => $latest ? $latest->updated_at->format('d/m/Y') : '-',
                    'foto_tersedia' => SambutanKepalaSekolah::whereNotNull('foto')->count()
                ],
                'message' => 'Summary sambutan kepala sekolah berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil summary sambutan kepala sekolah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil summary sambutan kepala sekolah'
            ], 500);
        }
    }

    /**
     * Create sambutan baru
     */
    public function store(Request $request)
    {
        try {
            Log::info('Membuat sambutan kepala sekolah baru', [
                'request_data' => $request->except(['foto']) // Jangan log file
            ]);
            
            $validated = $request->validate([
                'sambutan' => 'required|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $sambutan = new SambutanKepalaSekolah();
            $sambutan->sambutan = $validated['sambutan'];
            
            // Handle upload foto jika ada
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('sambutan-kepala-sekolah', 'public');
                $sambutan->foto = $path;
            }
            
            $sambutan->save();
            
            $data = $this->formatSambutan($sambutan);
            
            Log::info('Sambutan kepala sekolah berhasil dibuat', [
                'id' => $sambutan->id
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Sambutan kepala sekolah berhasil dibuat'
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Error membuat sambutan kepala sekolah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat sambutan kepala sekolah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update sambutan
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Update sambutan kepala sekolah', [
                'id' => $id,
                'request_data' => $request->except(['foto'])
            ]);
            
            $sambutan = SambutanKepalaSekolah::withTrashed()->find($id);
            
            if (!$sambutan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sambutan kepala sekolah tidak ditemukan'
                ], 404);
            }
            
            $validated = $request->validate([
                'sambutan' => 'required|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $sambutan->sambutan = $validated['sambutan'];
            
            // Handle upload foto jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($sambutan->foto && Storage::disk('public')->exists($sambutan->foto)) {
                    Storage::disk('public')->delete($sambutan->foto);
                }
                
                $path = $request->file('foto')->store('sambutan-kepala-sekolah', 'public');
                $sambutan->foto = $path;
            }
            
            $sambutan->save();
            
            $data = $this->formatSambutan($sambutan);
            
            Log::info('Sambutan kepala sekolah berhasil diupdate', [
                'id' => $id
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Sambutan kepala sekolah berhasil diupdate'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error update sambutan kepala sekolah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal update sambutan kepala sekolah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete sambutan (soft delete)
     */
    public function destroy($id)
    {
        try {
            Log::info('Menghapus sambutan kepala sekolah', [
                'id' => $id
            ]);
            
            $sambutan = SambutanKepalaSekolah::find($id);
            
            if (!$sambutan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sambutan kepala sekolah tidak ditemukan'
                ], 404);
            }
            
            $sambutan->delete();
            
            Log::info('Sambutan kepala sekolah berhasil dihapus', [
                'id' => $id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sambutan kepala sekolah berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error menghapus sambutan kepala sekolah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus sambutan kepala sekolah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore sambutan yang dihapus
     */
    public function restore($id)
    {
        try {
            Log::info('Restore sambutan kepala sekolah', [
                'id' => $id
            ]);
            
            $sambutan = SambutanKepalaSekolah::withTrashed()->find($id);
            
            if (!$sambutan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sambutan kepala sekolah tidak ditemukan'
                ], 404);
            }
            
            $sambutan->restore();
            
            $data = $this->formatSambutan($sambutan);
            
            Log::info('Sambutan kepala sekolah berhasil direstore', [
                'id' => $id
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Sambutan kepala sekolah berhasil direstore'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error restore sambutan kepala sekolah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal restore sambutan kepala sekolah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Force delete sambutan
     */
    public function forceDelete($id)
    {
        try {
            Log::info('Force delete sambutan kepala sekolah', [
                'id' => $id
            ]);
            
            $sambutan = SambutanKepalaSekolah::withTrashed()->find($id);
            
            if (!$sambutan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sambutan kepala sekolah tidak ditemukan'
                ], 404);
            }
            
            // Hapus foto jika ada
            if ($sambutan->foto && Storage::disk('public')->exists($sambutan->foto)) {
                Storage::disk('public')->delete($sambutan->foto);
            }
            
            $sambutan->forceDelete();
            
            Log::info('Sambutan kepala sekolah berhasil dihapus permanen', [
                'id' => $id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sambutan kepala sekolah berhasil dihapus permanen'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error force delete sambutan kepala sekolah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen sambutan kepala sekolah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint untuk debugging
     */
    public function testAll(Request $request)
    {
        try {
            Log::info('Test endpoint sambutan kepala sekolah dipanggil');
            
            $sambutans = SambutanKepalaSekolah::withTrashed()->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $sambutans->map(function ($sambutan) {
                    return [
                        'id' => $sambutan->id,
                        'sambutan' => strip_tags(substr($sambutan->sambutan, 0, 100)) . '...',
                        'sambutan_length' => strlen($sambutan->sambutan),
                        'foto' => $sambutan->foto,
                        'foto_url' => $sambutan->foto_url,
                        'foto_exists' => $sambutan->foto_exists,
                        'created_at' => $sambutan->created_at->format('d/m/Y H:i'),
                        'updated_at' => $sambutan->updated_at->format('d/m/Y H:i'),
                        'deleted_at' => $sambutan->deleted_at ? $sambutan->deleted_at->format('d/m/Y H:i') : null,
                        'is_deleted' => !is_null($sambutan->deleted_at)
                    ];
                }),
                'count' => $sambutans->count(),
                'active_count' => $sambutans->whereNull('deleted_at')->count(),
                'deleted_count' => $sambutans->whereNotNull('deleted_at')->count(),
                'message' => 'Test endpoint berhasil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error test endpoint sambutan kepala sekolah: ' . $e->getMessage());
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
            $sambutans = SambutanKepalaSekolah::withTrashed()->get();
            
            $debugData = [];
            foreach ($sambutans as $item) {
                $debugData[] = [
                    'id' => $item->id,
                    'sambutan' => $item->sambutan,
                    'foto_raw' => $item->foto,
                    'foto_url' => $item->foto_url,
                    'foto_exists' => $item->foto_exists,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'deleted_at' => $item->deleted_at,
                    'is_deleted' => !is_null($item->deleted_at),
                    'storage_info' => $item->foto ? [
                        'exists_in_storage' => Storage::disk('public')->exists($item->foto),
                        'path_in_storage' => $item->foto,
                        'full_path' => storage_path('app/public/' . $item->foto),
                        'url' => asset('storage/' . $item->foto)
                    ] : null
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $debugData,
                'count' => count($debugData),
                'storage_disk' => 'public',
                'default_image' => asset('images/default-profile.png'),
                'message' => 'Debug data sambutan kepala sekolah'
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
     * Helper function untuk format data sambutan
     */
    private function formatSambutan(SambutanKepalaSekolah $sambutan)
    {
        return [
            'id' => $sambutan->id,
            'teks_sambutan' => $sambutan->sambutan,
            'sambutan_pendek' => strip_tags(substr($sambutan->sambutan, 0, 150)) . '...',
            'foto' => $sambutan->foto_url,
            'foto_url' => $sambutan->foto_url,
            'thumbnail_url' => $sambutan->foto_url,
            'created_at' => $sambutan->created_at->format('d/m/Y H:i'),
            'updated_at' => $sambutan->updated_at->format('d/m/Y H:i'),
            'created_date' => $sambutan->created_at->format('d/m/Y'),
            'updated_date' => $sambutan->updated_at->format('d/m/Y'),
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
                'teks_sambutan' => 'Assalamu\'alaikum warahmatullahi wabarakatuh, Selamat datang di website resmi TK SC2 Menara Parepare. Website ini kami hadirkan sebagai sarana informasi bagi orang tua dan masyarakat, serta untuk menampilkan berbagai kegiatan dan pencapaian anak-anak didik kami.<br><br>Sebagai lembaga pendidikan anak usia dini, kami berkomitmen untuk memberikan pendidikan yang seimbang antara nilai-nilai akademik, karakter, dan spiritual. Dengan pendekatan yang menyenangkan, kami berharap dapat membantu mengembangkan potensi terbaik dari setiap anak.<br><br>Kami mengucapkan terima kasih atas kepercayaan orang tua yang telah mempercayakan pendidikan anak-anaknya kepada kami. Semoga dengan kerja sama yang baik, kita dapat menciptakan generasi yang berakhlak mulia dan cerdas.<br><br>Wassalamu\'alaikum warahmatullahi wabarakatuh.',
                'sambutan_pendek' => 'Assalamu\'alaikum warahmatullahi wabarakatuh, Selamat datang di website resmi TK SC2 Menara Parepare...',
                'foto' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&auto=format&fit=crop',
                'foto_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&auto=format&fit=crop',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&auto=format&fit=crop',
                'created_at' => now()->format('d/m/Y H:i'),
                'updated_at' => now()->format('d/m/Y H:i'),
                'created_date' => now()->format('d/m/Y'),
                'updated_date' => now()->format('d/m/Y'),
            ]
        ];
    }
}