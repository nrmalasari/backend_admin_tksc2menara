<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegistPendaftarController;
use App\Http\Controllers\API\PembayaranController;
use App\Http\Controllers\API\SiswaPendaftarController;
use App\Http\Controllers\API\RincianPembayaranController;
use App\Http\Controllers\API\SiswaController;
use App\Http\Controllers\API\GuruController;
use App\Http\Controllers\API\BeritaAcaraController;
use App\Http\Controllers\API\FasilitasController;
use App\Http\Controllers\API\StrukturOrganisasiController;
use App\Http\Controllers\API\SambutanKepalaSekolahController;
use App\Http\Controllers\API\StatsController;
use App\Http\Controllers\API\TahunAjaranController; // Tambahkan ini
use Illuminate\Http\Request;
// ==================== API ROUTES ====================
// ==================== ROUTE PROXY UNTUK FILE RAHASIA ====================
// Route ini menangkap permintaan gambar: domain.com/lihat-file/nama-file.jpg
Route::get('/lihat-file/{filename}', function ($filename) {
    
    // 1. CEK: Apakah user login?
    if (!Auth::check()) {
        abort(403, 'Anda harus login untuk melihat file ini.');
    }

    // 2. CEK: Apakah file ada di disk 'rahasia'?
    if (!Storage::disk('rahasia')->exists($filename)) {
        abort(404, 'File tidak ditemukan.');
    }

    // 3. TAMPILKAN FILE
    $path = storage_path('app/secure-files/' . $filename);
    
    // Cek mime type untuk response yang tepat
    $mimeType = mime_content_type($path);
    
    return response()->file($path, [
        'Content-Type' => $mimeType,
    ]);

})->name('ambil.file.rahasia'); // HAPUS middleware, kita handle manual

// ==================== FILE ACCESS ROUTES (LEGACY) ====================
Route::prefix('api')->group(function () {
    // Route untuk mengakses file dengan auth check
    Route::get('/secure-files/{filename}', function ($filename) {
        try {
            // Untuk API, cek token atau authorization header
            $authHeader = request()->header('Authorization');
            if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Token tidak valid'
                ], 401);
            }
            
            $path = storage_path('app/secure-files/' . $filename);
            
            // Cek jika file ada di disk 'rahasia'
            if (!Storage::disk('rahasia')->exists($filename)) {
                // Fallback ke public untuk backward compatibility
                $path = storage_path('app/public/secure-files/' . $filename);
                
                if (!file_exists($path)) {
                    return response()->json([
                        'error' => 'File not found',
                        'filename' => $filename
                    ], 404);
                }
            }
            
            // Tentukan content type
            $mimeType = mime_content_type($path);
            
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error accessing file',
                'message' => $e->getMessage()
            ], 500);
        }
    });
});


// ==================== API ROUTES ====================
Route::prefix('api')->group(function () {
    
    // 1. Test API Connection
    Route::get('/test', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'API is working!',
            'timestamp' => now()->toISOString(),
            'endpoints' => [
                'GET /api/test' => 'Test API connection',
                'GET /api/test-aes' => 'Test AES encryption',
                'POST /api/pendaftar/register' => 'Register new user',
                'POST /api/pendaftar/login' => 'Login user',
                'GET /api/siswa-pendaftar' => 'Get siswa pendaftar data',
                'POST /api/siswa-pendaftar' => 'Create siswa pendaftar',
                'PUT /api/siswa-pendaftar/{id}' => 'Update siswa pendaftar',
                'DELETE /api/siswa-pendaftar/{id}' => 'Delete siswa pendaftar',
                'GET /api/pembayaran' => 'Get payment history',
                'POST /api/pembayaran' => 'Create new payment',
                'GET /api/pembayaran/{id}' => 'Get payment detail',
                'PUT /api/pembayaran/{id}' => 'Update payment',
                'DELETE /api/pembayaran/{id}' => 'Delete payment',
                'GET /api/pembayaran/test/all' => 'Test get all payments',
                'POST /api/pembayaran/test/create' => 'Test create payment',
                'POST /api/test-post' => 'Test POST request',
                'GET /api/pembayaran/debug/all' => 'Debug all payments',
                'GET /api/pembayaran/check-files/{id}' => 'Check payment files',
                'PUT /api/pembayaran/{id}/status' => 'Update payment status',
                'GET /api/rincian-pembayaran' => 'Get rincian pembayaran',
                'GET /api/rincian-pembayaran/summary' => 'Get summary rincian',
                'GET /api/rincian-pembayaran/{id}' => 'Get detail rincian',
                'GET /api/siswa' => 'Get semua siswa (publik)',
                'GET /api/siswa/aktif' => 'Get siswa aktif saja',
                'GET /api/siswa/tahun-ajaran/{id}' => 'Get siswa by tahun ajaran',
                'GET /api/siswa/{id}' => 'Get detail siswa',
                'GET /api/siswa/test/all' => 'Test get all siswa',
                'GET /api/guru' => 'Get semua guru (publik)',
                'GET /api/guru/aktif' => 'Get guru aktif saja',
                'GET /api/guru/{id}' => 'Get detail guru',
                'GET /api/guru/test/all' => 'Test get all guru',
                'GET /api/siswa-pendaftar/test' => 'Test get all siswa pendaftar',
                'POST /api/siswa-pendaftar/test' => 'Test create siswa pendaftar',
                'GET /api/berita-acara' => 'Get semua berita acara',
                'GET /api/berita-acara/latest' => 'Get berita terbaru',
                'GET /api/berita-acara/tags' => 'Get semua tag/kategori',
                'GET /api/berita-acara/slug/{slug}' => 'Get berita by slug',
                'GET /api/berita-acara/find/{identifier}' => 'Find berita by slug or ID',
                'GET /api/berita-acara/{id}' => 'Get berita by ID',
                'GET /api/berita-acara/test/all' => 'Test get all berita',
                'GET /api/fasilitas' => 'Get semua fasilitas (publik)',
                'GET /api/fasilitas/latest' => 'Get fasilitas terbaru',
                'GET /api/fasilitas/summary' => 'Get summary fasilitas',
                'GET /api/fasilitas/{id}' => 'Get detail fasilitas',
                'GET /api/fasilitas/test/all' => 'Test get all fasilitas',
                'GET /api/fasilitas/debug/all' => 'Debug all fasilitas',
                'GET /api/struktur-organisasi' => 'Get semua struktur organisasi',
                'GET /api/struktur-organisasi/latest' => 'Get struktur organisasi terbaru',
                'GET /api/struktur-organisasi/active' => 'Get struktur organisasi aktif',
                'GET /api/struktur-organisasi/summary' => 'Get summary struktur organisasi',
                'GET /api/struktur-organisasi/{id}' => 'Get detail struktur organisasi',
                'GET /api/struktur-organisasi/test/all' => 'Test get all struktur organisasi',
                'GET /api/struktur-organisasi/debug/all' => 'Debug all struktur organisasi',
                'GET /api/sambutan' => 'Get semua sambutan kepala sekolah',
                'GET /api/sambutan/latest' => 'Get sambutan terbaru',
                'GET /api/sambutan/active' => 'Get sambutan aktif',
                'GET /api/sambutan/summary' => 'Get summary sambutan',
                'GET /api/sambutan/{id}' => 'Get detail sambutan',
                'POST /api/sambutan' => 'Create sambutan baru',
                'PUT /api/sambutan/{id}' => 'Update sambutan',
                'DELETE /api/sambutan/{id}' => 'Delete sambutan',
                'POST /api/sambutan/{id}/restore' => 'Restore sambutan',
                'DELETE /api/sambutan/{id}/force' => 'Force delete sambutan',
                'GET /api/sambutan/test/all' => 'Test get all sambutan',
                'GET /api/sambutan/debug/all' => 'Debug all sambutan',
                'GET /api/stats' => 'Get all stats',
                'GET /api/stats/home' => 'Get home stats',
                'GET /api/stats/category/{category}' => 'Get stats by category',
                'GET /api/guru/stats' => 'Get guru stats',
                'GET /api/siswa/stats' => 'Get siswa stats',
                'GET /api/tahun-ajaran' => 'Get semua tahun ajaran',
                'GET /api/tahun-ajaran/latest' => 'Get tahun ajaran terbaru',
                'GET /api/tahun-ajaran/active' => 'Get tahun ajaran aktif',
                'GET /api/tahun-ajaran/{id}' => 'Get detail tahun ajaran',
                'GET /api/tahun-ajaran/stats' => 'Get stats tahun ajaran',
                'GET /api/tahun-ajaran/test/all' => 'Test get all tahun ajaran',
                'GET /lihat-file/{filename}' => 'View secure file (WEB ONLY)',
                'GET /api/secure-files/{filename}' => 'View secure file (API)',
            ]
        ]);
    });
    
    // 2. Test AES Encryption
    Route::get('/test-aes', function () {
        try {
            if (class_exists('App\\Models\\SiswaPendaftar')) {
                $testData = new App\Models\SiswaPendaftar();
                $password = 'password123';
                $encrypted = $testData->encryptAES($password);
                $decrypted = $testData->decryptAES($encrypted);
                
                return response()->json([
                    'success' => true,
                    'original' => $password,
                    'encrypted' => $encrypted,
                    'decrypted' => $decrypted,
                    'match' => $password === $decrypted,
                    'encryption' => 'AES-256-CBC',
                    'key_length' => 32,
                    'message' => 'Encryption test successful'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Model SiswaPendaftar not found'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Encryption test failed'
            ], 500);
        }
    });
    
    // 3. Pendaftar Routes
    Route::prefix('pendaftar')->group(function () {
        // Register new user
        Route::post('/register', [RegistPendaftarController::class, 'register']);
        
        // Login user
        Route::post('/login', [RegistPendaftarController::class, 'login']);
    });
    
    // 4. Siswa Pendaftar Routes
    Route::prefix('siswa-pendaftar')->group(function () {
        Route::get('/', [SiswaPendaftarController::class, 'index']);
        Route::post('/', [SiswaPendaftarController::class, 'store']);
        Route::put('/{id}', [SiswaPendaftarController::class, 'update']);
        Route::delete('/{id}', [SiswaPendaftarController::class, 'destroy']);
        
        // Test routes untuk debugging
        Route::get('/test', [SiswaPendaftarController::class, 'testIndex']);
        Route::post('/test', [SiswaPendaftarController::class, 'testStore']);

        // Route untuk debugging
        Route::get('/debug/all', [SiswaPendaftarController::class, 'debugAll']);
        Route::get('/check-files/{id}', [SiswaPendaftarController::class, 'checkFiles']);
    });
    
    // 5. Pembayaran Routes
    Route::prefix('pembayaran')->group(function () {
        // Routes utama dengan auth
        Route::get('/', [PembayaranController::class, 'index']);
        Route::post('/', [PembayaranController::class, 'store']);
        Route::get('/{id}', [PembayaranController::class, 'show']);
        Route::put('/{id}', [PembayaranController::class, 'update']);
        Route::delete('/{id}', [PembayaranController::class, 'destroy']);
        
        // Route untuk update status (admin)
        Route::put('/{id}/status', [PembayaranController::class, 'updateStatus']);
        
        // Route untuk testing tanpa authentication
        Route::get('/test/all', [PembayaranController::class, 'testIndex']);
        Route::post('/test/create', [PembayaranController::class, 'testStore']);
        
        // Route untuk debugging
        Route::get('/debug/all', [PembayaranController::class, 'debugAll']);
        
        // Route untuk cek file
        Route::get('/check-files/{id}', [PembayaranController::class, 'checkFiles']);
    });
    
    // 6. Rincian Pembayaran Routes
    Route::prefix('rincian-pembayaran')->group(function () {
        Route::get('/', [RincianPembayaranController::class, 'index']);
        Route::get('/summary', [RincianPembayaranController::class, 'summary']);
        Route::get('/{id}', [RincianPembayaranController::class, 'show']);
    });
    
    // 7. Siswa Routes (publik - tanpa auth)
    Route::prefix('siswa')->group(function () {
        Route::get('/', [SiswaController::class, 'index']);
        Route::get('/aktif', [SiswaController::class, 'getAktif']);
        Route::get('/tahun-ajaran/{id}', [SiswaController::class, 'getByTahunAjaran']);
        Route::get('/{id}', [SiswaController::class, 'show']);
        
        // Stats route untuk siswa
        Route::get('/stats', [SiswaController::class, 'getStats']);
        
        // Test route untuk debugging
        Route::get('/test/all', function () {
            $siswas = App\Models\Siswa::with(['tahunAjaran', 'kelas'])
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->take(5)
                ->get();
            
            return response()->json([
                'success' => true,
                'count' => $siswas->count(),
                'data' => $siswas->map(function ($siswa) {
                    return [
                        'id' => $siswa->id,
                        'nama' => $siswa->nama_lengkap,
                        'nis' => $siswa->nis,
                        'foto_url' => $siswa->foto_url,
                        'tahun_ajaran' => $siswa->tahunAjaran?->nama_tahun_ajaran,
                        'kelas' => $siswa->kelas?->nama_kelas,
                    ];
                })
            ]);
        });
    });
    
    // 8. Guru Routes (publik - tanpa auth)
    Route::prefix('guru')->group(function () {
        Route::get('/', [GuruController::class, 'index']);
        Route::get('/aktif', [GuruController::class, 'getAktif']);
        Route::get('/{id}', [GuruController::class, 'show']);
        
        // Stats route untuk guru
        Route::get('/stats', [GuruController::class, 'getStats']);
        
        // Test route untuk debugging
        Route::get('/test/all', function () {
            $gurus = App\Models\Guru::where('status', 'aktif')
                ->orderByRaw("CASE WHEN jabatan LIKE '%kepala%' THEN 1 ELSE 2 END")
                ->orderBy('nama_lengkap')
                ->take(5)
                ->get();
            
            return response()->json([
                'success' => true,
                'count' => $gurus->count(),
                'data' => $gurus->map(function ($guru) {
                    return [
                        'id' => $guru->id_guru,
                        'nama_lengkap' => $guru->nama_lengkap,
                        'jabatan' => $guru->jabatan,
                        'foto_url' => $guru->foto_url,
                        'guru_kelas' => $guru->guru_kelas,
                        'pendidikan_terakhir' => $guru->pendidikan_terakhir,
                        'bidang_studi' => $guru->bidang_studi,
                        'status' => $guru->status,
                    ];
                })
            ]);
        });
    });
    
    // 9. Berita Acara Routes (publik - tanpa auth)
    Route::prefix('berita-acara')->group(function () {
        Route::get('/', [BeritaAcaraController::class, 'index']);
        Route::get('/latest', [BeritaAcaraController::class, 'getLatest']);
        Route::get('/tags', [BeritaAcaraController::class, 'getTags']);
        Route::get('/slug/{slug}', [BeritaAcaraController::class, 'showBySlug']);
        Route::get('/find/{identifier}', [BeritaAcaraController::class, 'findBerita']);
        Route::get('/{id}', [BeritaAcaraController::class, 'show']);
        Route::get('/debug/all', [BeritaAcaraController::class, 'debugAll']);
        Route::get('/test/all', [BeritaAcaraController::class, 'testAll']);
    });
    
    // 10. Fasilitas Routes (publik - tanpa auth)
    Route::prefix('fasilitas')->group(function () {
        Route::get('/', [FasilitasController::class, 'index']);
        Route::get('/latest', [FasilitasController::class, 'getLatest']);
        Route::get('/summary', [FasilitasController::class, 'summary']);
        Route::get('/{id}', [FasilitasController::class, 'show']);
        Route::get('/slug/{slug}', [FasilitasController::class, 'showBySlug']);
        
        // Test dan debug routes
        Route::get('/test/all', [FasilitasController::class, 'testAll']);
        Route::get('/debug/all', [FasilitasController::class, 'debugAll']);
    });
    
    // 11. Struktur Organisasi Routes (publik - tanpa auth)
    Route::prefix('struktur-organisasi')->group(function () {
        Route::get('/', [StrukturOrganisasiController::class, 'index']);
        Route::get('/latest', [StrukturOrganisasiController::class, 'getLatest']);
        Route::get('/active', [StrukturOrganisasiController::class, 'getActive']);
        Route::get('/summary', [StrukturOrganisasiController::class, 'summary']);
        Route::get('/{id}', [StrukturOrganisasiController::class, 'show']);
        
        // Test dan debug routes
        Route::get('/test/all', [StrukturOrganisasiController::class, 'testAll']);
        Route::get('/debug/all', [StrukturOrganisasiController::class, 'debugAll']);
    });
    
    // 12. Sambutan Kepala Sekolah Routes
    Route::prefix('sambutan')->group(function () {
        // GET routes
        Route::get('/', [SambutanKepalaSekolahController::class, 'index'])->name('sambutan.index');
        Route::get('/latest', [SambutanKepalaSekolahController::class, 'getLatest'])->name('sambutan.latest');
        Route::get('/active', [SambutanKepalaSekolahController::class, 'getActive'])->name('sambutan.active');
        Route::get('/summary', [SambutanKepalaSekolahController::class, 'summary'])->name('sambutan.summary');
        Route::get('/{id}', [SambutanKepalaSekolahController::class, 'show'])->name('sambutan.show');
        
        // CRUD routes
        Route::post('/', [SambutanKepalaSekolahController::class, 'store'])->name('sambutan.store');
        Route::put('/{id}', [SambutanKepalaSekolahController::class, 'update'])->name('sambutan.update');
        Route::delete('/{id}', [SambutanKepalaSekolahController::class, 'destroy'])->name('sambutan.destroy');
        
        // Restore routes
        Route::post('/{id}/restore', [SambutanKepalaSekolahController::class, 'restore'])->name('sambutan.restore');
        Route::delete('/{id}/force', [SambutanKepalaSekolahController::class, 'forceDelete'])->name('sambutan.forceDelete');
        
        // Debug routes
        Route::get('/test/all', [SambutanKepalaSekolahController::class, 'testAll'])->name('sambutan.test.all');
        Route::get('/debug/all', [SambutanKepalaSekolahController::class, 'debugAll'])->name('sambutan.debug.all');
    });
    
    // 13. Stats API Routes
    Route::prefix('stats')->group(function () {
        Route::get('/', [StatsController::class, 'index'])->name('stats.index');
        Route::get('/home', [StatsController::class, 'getHomeStats'])->name('stats.home');
        Route::get('/category/{category}', [StatsController::class, 'getStatsByCategory'])->name('stats.category');
    });
    
    // 14. Tahun Ajaran Routes (publik - tanpa auth)
    Route::prefix('tahun-ajaran')->group(function () {
        Route::get('/', [TahunAjaranController::class, 'index'])->name('tahun-ajaran.index');
        Route::get('/latest', [TahunAjaranController::class, 'getLatest'])->name('tahun-ajaran.latest');
        Route::get('/active', [TahunAjaranController::class, 'getActive'])->name('tahun-ajaran.active');
        Route::get('/{id}', [TahunAjaranController::class, 'show'])->name('tahun-ajaran.show');
        Route::get('/stats', [TahunAjaranController::class, 'getStats'])->name('tahun-ajaran.stats');
        
        // Test route untuk debugging
        Route::get('/test/all', [TahunAjaranController::class, 'testAll'])->name('tahun-ajaran.test.all');
    });
    
    // 15. Test POST endpoint
    Route::post('/test-post', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'POST request successful',
            'data_received' => $request->all(),
            'headers' => $request->headers->all(),
            'timestamp' => now()->toISOString()
        ]);
    });
});

// ==================== WEB ROUTES ====================
Route::get('/', function () {
    return view('welcome');
});

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return response()->json([
        'error' => 'Route not found',
        'available_routes' => [
            'GET /' => 'Home page',
            'GET /lihat-file/{filename}' => 'View secure file (requires login)',
            'GET /api/test' => 'Test API',
            'GET /api/test-aes' => 'Test AES encryption',
            'POST /api/pendaftar/register' => 'Register user',
            'POST /api/pendaftar/login' => 'Login user',
            'GET /api/siswa-pendaftar' => 'Get siswa pendaftar data',
            'POST /api/siswa-pendaftar' => 'Create siswa pendaftar',
            'PUT /api/siswa-pendaftar/{id}' => 'Update siswa pendaftar',
            'DELETE /api/siswa-pendaftar/{id}' => 'Delete siswa pendaftar',
            'GET /api/pembayaran' => 'Get payment history',
            'POST /api/pembayaran' => 'Create new payment',
            'GET /api/pembayaran/{id}' => 'Get payment detail',
            'PUT /api/pembayaran/{id}' => 'Update payment',
            'DELETE /api/pembayaran/{id}' => 'Delete payment',
            'PUT /api/pembayaran/{id}/status' => 'Update payment status',
            'GET /api/pembayaran/test/all' => 'Test get all payments',
            'POST /api/pembayaran/test/create' => 'Test create payment',
            'GET /api/pembayaran/debug/all' => 'Debug all payments',
            'GET /api/pembayaran/check-files/{id}' => 'Check payment files',
            'GET /api/rincian-pembayaran' => 'Get rincian pembayaran',
            'GET /api/rincian-pembayaran/summary' => 'Get summary rincian',
            'GET /api/rincian-pembayaran/{id}' => 'Get detail rincian',
            'GET /api/siswa' => 'Get semua siswa (publik)',
            'GET /api/siswa/aktif' => 'Get siswa aktif saja',
            'GET /api/siswa/tahun-ajaran/{id}' => 'Get siswa by tahun ajaran',
            'GET /api/siswa/{id}' => 'Get detail siswa',
            'GET /api/siswa/stats' => 'Get siswa stats',
            'GET /api/siswa/test/all' => 'Test get all siswa (debug)',
            'GET /api/guru' => 'Get semua guru (publik)',
            'GET /api/guru/aktif' => 'Get guru aktif saja',
            'GET /api/guru/{id}' => 'Get detail guru',
            'GET /api/guru/stats' => 'Get guru stats',
            'GET /api/guru/test/all' => 'Test get all guru',
            'GET /api/berita-acara' => 'Get semua berita acara',
            'GET /api/berita-acara/latest' => 'Get berita terbaru',
            'GET /api/berita-acara/tags' => 'Get semua tag/kategori',
            'GET /api/berita-acara/slug/{slug}' => 'Get berita by slug',
            'GET /api/berita-acara/find/{identifier}' => 'Find berita by slug or ID',
            'GET /api/berita-acara/{id}' => 'Get berita by ID',
            'GET /api/berita-acara/test/all' => 'Test get all berita',
            'GET /api/fasilitas' => 'Get semua fasilitas (publik)',
            'GET /api/fasilitas/latest' => 'Get fasilitas terbaru',
            'GET /api/fasilitas/summary' => 'Get summary fasilitas',
            'GET /api/fasilitas/{id}' => 'Get detail fasilitas',
            'GET /api/fasilitas/test/all' => 'Test get all fasilitas',
            'GET /api/fasilitas/debug/all' => 'Debug all fasilitas',
            'GET /api/struktur-organisasi' => 'Get semua struktur organisasi',
            'GET /api/struktur-organisasi/latest' => 'Get struktur organisasi terbaru',
            'GET /api/struktur-organisasi/active' => 'Get struktur organisasi aktif',
            'GET /api/struktur-organisasi/summary' => 'Get summary struktur organisasi',
            'GET /api/struktur-organisasi/{id}' => 'Get detail struktur organisasi',
            'GET /api/struktur-organisasi/test/all' => 'Test get all struktur organisasi',
            'GET /api/struktur-organisasi/debug/all' => 'Debug all struktur organisasi',
            'GET /api/sambutan' => 'Get semua sambutan kepala sekolah',
            'GET /api/sambutan/latest' => 'Get sambutan terbaru',
            'GET /api/sambutan/active' => 'Get sambutan aktif',
            'GET /api/sambutan/summary' => 'Get summary sambutan',
            'GET /api/sambutan/{id}' => 'Get detail sambutan',
            'POST /api/sambutan' => 'Create sambutan baru',
            'PUT /api/sambutan/{id}' => 'Update sambutan',
            'DELETE /api/sambutan/{id}' => 'Delete sambutan',
            'POST /api/sambutan/{id}/restore' => 'Restore sambutan',
            'DELETE /api/sambutan/{id}/force' => 'Force delete sambutan',
            'GET /api/sambutan/test/all' => 'Test get all sambutan',
            'GET /api/sambutan/debug/all' => 'Debug all sambutan',
            'GET /api/stats' => 'Get all stats',
            'GET /api/stats/home' => 'Get home stats',
            'GET /api/stats/category/{category}' => 'Get stats by category',
            'GET /api/tahun-ajaran' => 'Get semua tahun ajaran',
            'GET /api/tahun-ajaran/latest' => 'Get tahun ajaran terbaru',
            'GET /api/tahun-ajaran/active' => 'Get tahun ajaran aktif',
            'GET /api/tahun-ajaran/{id}' => 'Get detail tahun ajaran',
            'GET /api/tahun-ajaran/stats' => 'Get stats tahun ajaran',
            'GET /api/tahun-ajaran/test/all' => 'Test get all tahun ajaran',
            'POST /api/test-post' => 'Test POST request',
            'GET /api/siswa-pendaftar/test' => 'Test get all siswa',
            'POST /api/siswa-pendaftar/test' => 'Test create siswa'
        ]
    ], 404);
});
