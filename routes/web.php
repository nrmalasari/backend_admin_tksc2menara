<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegistPendaftarController;
use App\Http\Controllers\API\PembayaranController;
use App\Http\Controllers\API\SiswaPendaftarController;
use App\Http\Controllers\API\RincianPembayaranController;
use App\Http\Controllers\API\SiswaController;
use Illuminate\Http\Request;

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
                'GET /api/siswa-pendaftar/test' => 'Test get all siswa pendaftar',
                'POST /api/siswa-pendaftar/test' => 'Test create siswa pendaftar'
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
        
        // Anda bisa tambahkan route lain jika diperlukan:
        // Route::post('/', [RincianPembayaranController::class, 'store']);
        // Route::put('/{id}', [RincianPembayaranController::class, 'update']);
        // Route::delete('/{id}', [RincianPembayaranController::class, 'destroy']);
    });
    
    // 7. Siswa Routes (publik - tanpa auth)
    Route::prefix('siswa')->group(function () {
        Route::get('/', [SiswaController::class, 'index']);
        Route::get('/aktif', [SiswaController::class, 'getAktif']);
        Route::get('/tahun-ajaran/{id}', [SiswaController::class, 'getByTahunAjaran']);
        Route::get('/{id}', [SiswaController::class, 'show']);
        
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
    
    // 8. Test POST endpoint
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
            'POST /api/test-post' => 'Test POST request',
            'GET /api/siswa-pendaftar/test' => 'Test get all siswa',
            'POST /api/siswa-pendaftar/test' => 'Test create siswa',
            'GET /api/siswa/test/all' => 'Test get all siswa (debug)'
        ]
    ], 404);
});