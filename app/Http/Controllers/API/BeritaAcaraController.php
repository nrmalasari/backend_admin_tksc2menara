<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BeritaAcara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class BeritaAcaraController extends Controller
{
    /**
     * Mendapatkan semua berita acara (publik)
     */
    public function index(Request $request)
    {
        try {
            Log::info('=== GET BERITA ACARA API CALLED ===');
            
            $query = BeritaAcara::with(['media'])
                ->published()
                ->orderBy('tanggal_acara', 'desc');
            
            // Filter pencarian
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }
            
            // Filter berdasarkan kategori/tag
            if ($request->has('tag')) {
                $tag = $request->tag;
                // PERBAIKAN: Handle JSON atau string tags
                $query->where(function($q) use ($tag) {
                    $q->whereJsonContains('tags', $tag)
                      ->orWhere('tags', 'like', '%"' . $tag . '"%')
                      ->orWhere('tags', 'like', '%' . $tag . '%');
                });
            }
            
            $berita = $query->get()->map(function ($item) {
                return $this->formatBerita($item);
            });
            
            return response()->json([
                'success' => true,
                'data' => $berita,
                'message' => 'Data berita acara berhasil diambil',
                'count' => $berita->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data berita acara: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data berita acara: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan berita acara terbaru (untuk homepage)
     */
    public function getLatest(Request $request)
    {
        try {
            Log::info('=== GET LATEST BERITA ACARA API CALLED ===');
            
            $limit = $request->get('limit', 6);
            
            $berita = BeritaAcara::with(['media'])
                ->published()
                ->orderBy('tanggal_acara', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return $this->formatBerita($item);
                });
            
            return response()->json([
                'success' => true,
                'data' => $berita,
                'message' => 'Data berita terbaru berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil berita terbaru: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil berita terbaru: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan detail berita acara berdasarkan slug
     */
    public function showBySlug($slug)
    {
        try {
            Log::info('=== GET BERITA ACARA BY SLUG API CALLED ===', ['slug' => $slug]);
            
            // Cari berita berdasarkan kolom slug
            $berita = BeritaAcara::with(['media'])
                ->published()
                ->bySlug($slug)
                ->first();
            
            if (!$berita) {
                Log::warning('Berita tidak ditemukan untuk slug:', ['slug' => $slug]);
                
                // Untuk debugging, tampilkan semua slug yang ada
                $allSlugs = BeritaAcara::published()->pluck('slug')->toArray();
                Log::info('Available slugs:', $allSlugs);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Berita tidak ditemukan',
                    'debug' => [
                        'requested_slug' => $slug,
                        'available_slugs' => $allSlugs
                    ]
                ], 404);
            }
            
            Log::info('Berita ditemukan:', [
                'id' => $berita->id,
                'judul' => $berita->judul,
                'slug' => $berita->slug
            ]);
            
            // Get related news
            $relatedNews = $berita->relatedNews(3)->map(function ($item) {
                return $this->formatBerita($item, true);
            });
            
            return response()->json([
                'success' => true,
                'data' => $this->formatBeritaDetail($berita),
                'related_news' => $relatedNews,
                'message' => 'Detail berita berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil detail berita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail berita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan detail berita acara berdasarkan ID
     */
    public function show($id)
    {
        try {
            $berita = BeritaAcara::with(['media'])
                ->published()
                ->find($id);
            
            if (!$berita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Berita tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $this->formatBeritaDetail($berita),
                'message' => 'Detail berita berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil detail berita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail berita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Find berita by slug atau ID (flexible)
     */
    public function findBerita($identifier)
    {
        try {
            Log::info('=== FIND BERITA API CALLED ===', ['identifier' => $identifier]);
            
            // Cari dulu berdasarkan slug
            $berita = BeritaAcara::with(['media'])
                ->published()
                ->bySlug($identifier)
                ->first();
            
            // Jika tidak ditemukan berdasarkan slug, coba berdasarkan ID
            if (!$berita && is_numeric($identifier)) {
                $berita = BeritaAcara::with(['media'])
                    ->published()
                    ->find($identifier);
            }
            
            if (!$berita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Berita tidak ditemukan',
                    'identifier' => $identifier
                ], 404);
            }
            
            // Get related news
            $relatedNews = $berita->relatedNews(3)->map(function ($item) {
                return $this->formatBerita($item, true);
            });
            
            return response()->json([
                'success' => true,
                'data' => $this->formatBeritaDetail($berita),
                'related_news' => $relatedNews,
                'message' => 'Detail berita berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mencari berita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencari berita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan semua tag/kategori berita
     */
    public function getTags()
    {
        try {
            Log::info('=== GET BERITA TAGS API CALLED ===');
            
            $allTags = BeritaAcara::published()
                ->pluck('tags')
                ->map(function ($tags) {
                    // PERBAIKAN: Handle format tags yang berbeda-beda
                    if (is_null($tags) || empty($tags)) {
                        return [];
                    }
                    
                    if (is_string($tags)) {
                        // Coba decode JSON
                        $decoded = json_decode($tags, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            return $decoded;
                        }
                        
                        // Jika bukan JSON, anggap sebagai string yang dipisahkan koma
                        return array_filter(
                            array_map('trim', explode(',', $tags))
                        );
                    }
                    
                    // Jika sudah array, langsung kembalikan
                    return is_array($tags) ? $tags : [];
                })
                ->flatten()
                ->filter(function($tag) {
                    // Hapus tag yang kosong
                    return !empty($tag) && $tag !== '' && $tag !== 'null' && $tag !== 'NULL';
                })
                ->map(function($tag) {
                    // Normalisasi tag - hapus spasi di awal/akhir
                    return trim($tag);
                })
                ->unique()
                ->values()
                ->toArray();
            
            // Sort tags alphabetically
            sort($allTags);
            
            return response()->json([
                'success' => true,
                'data' => $allTags,
                'message' => 'Tag berita berhasil diambil',
                'count' => count($allTags)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil tag berita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil tag berita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format data berita untuk list
     */
    private function formatBerita($berita, $simple = false)
    {
        // Format tanggal secara manual di Controller untuk menghindari conflict
        $date = $berita->tanggal_acara ? Carbon::parse($berita->tanggal_acara) : null;
        
        // PERBAIKAN: Handle tags dengan berbagai format
        $tags = $this->normalizeTags($berita->tags);
        
        if ($simple) {
            return [
                'id' => $berita->id,
                'title' => $berita->judul,
                'judul' => $berita->judul, // Tambahkan juga untuk kompatibilitas
                'slug' => $berita->slug,
                'date' => $date ? $date->format('d/m/Y') : null,
                'date_formatted' => $date ? $date->format('d/m/Y') : null,
                'date_full' => $date ? $date->translatedFormat('d F Y') : null,
                'image' => $berita->thumbnail_url,
                'thumbnail_url' => $berita->thumbnail_url,
                'short_description' => $berita->deskripsi ? str($berita->deskripsi)->limit(50) : null,
                'tags' => $tags, // PERBAIKAN: Sudah dinormalisasi
            ];
        }
        
        return [
            'id' => $berita->id,
            'title' => $berita->judul,
            'judul' => $berita->judul, // Tambahkan juga untuk kompatibilitas
            'slug' => $berita->slug,
            'date' => $date ? $date->translatedFormat('d F Y') : null,
            'date_formatted' => $date ? $date->format('d/m/Y') : null,
            'date_raw' => $date ? $date->format('Y-m-d') : null,
            'description' => $berita->deskripsi,
            'short_description' => $berita->deskripsi ? str($berita->deskripsi)->limit(100) : null,
            'image' => $berita->thumbnail_url,
            'thumbnail_url' => $berita->thumbnail_url,
            'tags' => $tags, // PERBAIKAN: Sudah dinormalisasi
            'status' => $berita->publikasi,
            'status_label' => $this->getStatusLabel($berita->publikasi),
            'total_images' => $berita->media->count(),
            'has_gallery' => $berita->media->where('is_thumbnail', false)->count() > 0,
            'images' => $berita->media->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->url,
                    'keterangan' => $media->keterangan,
                    'is_thumbnail' => $media->is_thumbnail,
                    'urutan' => $media->urutan,
                ];
            })->toArray(),
            'created_at' => $berita->created_at ? $berita->created_at->format('d/m/Y H:i') : null,
            'updated_at' => $berita->updated_at ? $berita->updated_at->format('d/m/Y H:i') : null,
        ];
    }

    /**
     * Format data berita untuk detail
     */
    private function formatBeritaDetail($berita)
    {
        // Format tanggal secara manual di Controller
        $date = $berita->tanggal_acara ? Carbon::parse($berita->tanggal_acara) : null;
        
        // PERBAIKAN: Handle tags dengan berbagai format
        $tags = $this->normalizeTags($berita->tags);
        
        return [
            'id' => $berita->id,
            'title' => $berita->judul,
            'judul' => $berita->judul,
            'slug' => $berita->slug,
            'date' => $date ? $date->translatedFormat('d F Y') : null,
            'date_formatted' => $date ? $date->format('d/m/Y') : null,
            'date_raw' => $date ? $date->format('Y-m-d') : null,
            'time_ago' => $date ? $date->diffForHumans() : null,
            'description' => $berita->deskripsi,
            'content' => $berita->deskripsi,
            'excerpt' => $berita->deskripsi ? str(strip_tags($berita->deskripsi))->limit(160) : null,
            'image' => $berita->thumbnail_url,
            'thumbnail_url' => $berita->thumbnail_url,
            'tags' => $tags, // PERBAIKAN: Sudah dinormalisasi
            'status' => $berita->publikasi,
            'status_label' => $this->getStatusLabel($berita->publikasi),
            'total_images' => $berita->media->count(),
            'has_gallery' => $berita->media->where('is_thumbnail', false)->count() > 0,
            'images' => $berita->media->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->url,
                    'keterangan' => $media->keterangan,
                    'is_thumbnail' => $media->is_thumbnail,
                    'urutan' => $media->urutan,
                ];
            })->toArray(),
            'gallery_images' => $berita->media->where('is_thumbnail', false)->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->url,
                    'keterangan' => $media->keterangan,
                    'urutan' => $media->urutan,
                ];
            })->toArray(),
            'url' => url('/berita/' . $berita->slug),
            'meta_title' => $berita->judul . ' | TK SC2 Menara Parepare',
            'meta_keywords' => $this->generateMetaKeywords($berita),
            'created_at' => $berita->created_at ? $berita->created_at->format('d/m/Y H:i') : null,
            'updated_at' => $berita->updated_at ? $berita->updated_at->format('d/m/Y H:i') : null,
        ];
    }

    /**
     * Normalize tags from various formats
     */
    private function normalizeTags($tags)
    {
        if (is_null($tags) || empty($tags)) {
            return [];
        }
        
        if (is_string($tags)) {
            // Coba decode JSON
            $decoded = json_decode($tags, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_filter(
                    array_map('trim', $decoded),
                    function($tag) {
                        return !empty($tag) && $tag !== '' && $tag !== 'null' && $tag !== 'NULL';
                    }
                );
            }
            
            // Jika bukan JSON, anggap sebagai string yang dipisahkan koma
            return array_filter(
                array_map('trim', explode(',', $tags)),
                function($tag) {
                    return !empty($tag) && $tag !== '' && $tag !== 'null' && $tag !== 'NULL';
                }
            );
        }
        
        // Jika sudah array, langsung kembalikan setelah difilter
        if (is_array($tags)) {
            return array_filter(
                array_map('trim', $tags),
                function($tag) {
                    return !empty($tag) && $tag !== '' && $tag !== 'null' && $tag !== 'NULL';
                }
            );
        }
        
        // Fallback
        return [];
    }

    /**
     * Get status label helper
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'publik' => 'Publik',
            'draft' => 'Draft',
            'arsip' => 'Arsip',
        ];

        return $labels[$status] ?? 'Draft';
    }

    /**
     * Generate meta keywords
     */
    private function generateMetaKeywords($berita)
    {
        $keywords = ['TK SC2 Menara', 'TK Parepare', 'Pendidikan Anak'];
        
        // PERBAIKAN: Gunakan tags yang sudah dinormalisasi
        $tags = $this->normalizeTags($berita->tags);
        if (!empty($tags)) {
            $keywords = array_merge($keywords, $tags);
        }
        
        return implode(', ', array_unique($keywords));
    }

    /**
     * Debug endpoint untuk melihat semua data
     */
    public function debugAll()
    {
        try {
            Log::info('=== DEBUG ALL BERITA API CALLED ===');
            
            $allBerita = BeritaAcara::with(['media'])->get();
            
            $formatted = $allBerita->map(function ($item) {
                $date = $item->tanggal_acara ? Carbon::parse($item->tanggal_acara) : null;
                
                // PERBAIKAN: Normalize tags untuk debug
                $tags = $this->normalizeTags($item->tags);
                
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'slug' => $item->slug,
                    'publikasi' => $item->publikasi,
                    'publikasi_label' => $this->getStatusLabel($item->publikasi),
                    'tanggal_acara' => $date ? $date->format('d/m/Y') : null,
                    'deskripsi_length' => strlen($item->deskripsi ?? ''),
                    'tags_raw' => $item->tags, // Tampilkan tags asli
                    'tags_normalized' => $tags, // Tampilkan tags yang sudah dinormalisasi
                    'tags_count' => count($tags),
                    'media_count' => $item->media->count(),
                    'thumbnail_url' => $item->thumbnail_url,
                    'created_at' => $item->created_at ? $item->created_at->format('d/m/Y H:i') : null,
                    'updated_at' => $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : null,
                ];
            });
            
            return response()->json([
                'success' => true,
                'total' => $allBerita->count(),
                'published' => $allBerita->where('publikasi', 'publik')->count(),
                'draft' => $allBerita->where('publikasi', 'draft')->count(),
                'arsip' => $allBerita->where('publikasi', 'arsip')->count(),
                'data' => $formatted,
                'message' => 'Debug data berita'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error debug berita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error debug: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint untuk melihat semua data (simple version)
     */
    public function testAll()
    {
        try {
            Log::info('=== TEST GET ALL BERITA ACARA API CALLED ===');
            
            $berita = BeritaAcara::with(['media'])
                ->published()
                ->orderBy('tanggal_acara', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $date = $item->tanggal_acara ? Carbon::parse($item->tanggal_acara) : null;
                    
                    // PERBAIKAN: Normalize tags
                    $tags = $this->normalizeTags($item->tags);
                    
                    return [
                        'id' => $item->id,
                        'judul' => $item->judul,
                        'slug' => $item->slug,
                        'tanggal_acara' => $date ? $date->format('d/m/Y') : null,
                        'thumbnail_url' => $item->thumbnail_url,
                        'total_images' => $item->media->count(),
                        'tags_raw' => $item->tags,
                        'tags_normalized' => $tags,
                        'status' => $item->publikasi,
                    ];
                });
            
            return response()->json([
                'success' => true,
                'count' => $berita->count(),
                'data' => $berita
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error test berita acara: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal test berita acara: ' . $e->getMessage()
            ], 500);
        }
    }
}