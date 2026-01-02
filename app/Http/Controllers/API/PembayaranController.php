<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\RegistPendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    /**
     * Mendapatkan user ID dari request
     */
    private function getUserId(Request $request)
    {
        $authHeader = $request->header('Authorization');
        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader);
            if (is_numeric($token)) {
                return $token;
            }
        }
        
        if ($request->filled('user_id')) {
            return $request->input('user_id');
        }
        
        if ($request->filled('user')) {
            return $request->query('user');
        }
        
        return $request->input('regist_pendaftar_id', null);
    }

    /**
     * Mendapatkan semua pembayaran user
     */
    public function index(Request $request)
    {
        $userId = $this->getUserId($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan. Silakan login terlebih dahulu.'
            ], 401);
        }
        
        try {
            $pembayarans = Pembayaran::where('regist_pendaftar_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($pembayaran) {
                    return [
                        'id' => $pembayaran->id,
                        'tanggal_pembayaran' => $pembayaran->tanggal_pembayaran,
                        'nama' => $pembayaran->nama,
                        'nama_bank' => $pembayaran->nama_bank,
                        'no_rek' => $pembayaran->no_rek_decrypted,
                        'metode_pembayaran' => $pembayaran->metode_pembayaran,
                        'jumlah_pembayaran' => $pembayaran->jumlah_pembayaran,
                        'formatted_jumlah' => $pembayaran->formatted_jumlah,
                        'bukti_pembayaran' => $pembayaran->bukti_pembayaran_url,
                        'status_pembayaran' => $pembayaran->status_pembayaran,
                        'status_text' => $pembayaran->status_text,
                        'status_color' => $pembayaran->status_color,
                        'catatan_admin' => $pembayaran->catatan_admin,
                        'created_at' => $pembayaran->created_at->format('d/m/Y H:i'),
                        'updated_at' => $pembayaran->updated_at->format('d/m/Y H:i'),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $pembayarans,
                'message' => 'Data pembayaran berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data pembayaran: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan pembayaran baru
     */
    public function store(Request $request)
    {
        Log::info('=== STORE METHOD CALLED ===');
        Log::info('Headers:', $request->headers->all());
        Log::info('All Input Data:', $request->all());
        Log::info('Files:', $request->files->all());
        
        $userId = $this->getUserId($request);
        Log::info('User ID from token:', ['user_id' => $userId]);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan. Silakan login terlebih dahulu.'
            ], 401);
        }
        
        // Validasi untuk SEMUA metode (transfer dan manual)
        $rules = [
            'nama' => 'required|string|max:255',
            'metode_pembayaran' => 'required|string|in:transfer,manual',
            'tanggal_pembayaran' => 'required|date',
            'jumlah_pembayaran' => 'required|numeric|min:1000', // HARUS ADA untuk SEMUA metode
            'jenis_pembayaran' => 'nullable|string|max:255', // Field baru untuk jenis pembayaran
        ];
        
        // Cek metode pembayaran dari request
        $metodePembayaran = $request->input('metode_pembayaran');
        Log::info('Metode pembayaran dari request:', ['metode' => $metodePembayaran]);
        Log::info('Jumlah pembayaran dari request:', ['jumlah' => $request->input('jumlah_pembayaran')]);
        
        // Tambahkan validasi khusus untuk transfer
        if ($metodePembayaran === 'transfer') {
            $rules['nama_bank'] = 'required|string|max:100';
            $rules['no_rek'] = 'required|string|max:255';
            $rules['bukti_pembayaran'] = 'required|image|mimes:jpeg,png,jpg,gif,webp,pdf|max:5120';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Validasi gagal:', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }
        
        try {
            // Cek apakah user ada
            $user = RegistPendaftar::find($userId);
            if (!$user) {
                Log::error('User tidak ditemukan:', ['user_id' => $userId]);
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }
            
            Log::info('User ditemukan:', ['user' => $user->username]);
            
            // Siapkan data untuk SEMUA metode
            $pembayaranData = [
                'regist_pendaftar_id' => $userId,
                'nama' => $request->input('nama'),
                'metode_pembayaran' => $metodePembayaran,
                'tanggal_pembayaran' => $request->input('tanggal_pembayaran'),
                'jumlah_pembayaran' => $request->input('jumlah_pembayaran'), // HARUS DISET untuk SEMUA
                'status_pembayaran' => 'menunggu',
                'jenis_pembayaran' => $request->input('jenis_pembayaran', null),
            ];
            
            // Untuk metode transfer
            if ($metodePembayaran === 'transfer') {
                $pembayaranData['nama_bank'] = $request->input('nama_bank');
                $pembayaranData['no_rek'] = $request->input('no_rek');
                
                // Handle file upload
                if ($request->hasFile('bukti_pembayaran')) {
                    $file = $request->file('bukti_pembayaran');
                    
                    // Generate nama file unik
                    $filename = 'bukti_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    
                    // Simpan ke storage dengan path relatif
                    $path = $file->storeAs('bukti-pembayaran', $filename, 'public');
                    
                    // Simpan path relatif ke database
                    $pembayaranData['bukti_pembayaran'] = 'bukti-pembayaran/' . $filename;
                    
                    Log::info('File uploaded via API:', [
                        'filename' => $filename,
                        'path' => $path,
                        'db_value' => $pembayaranData['bukti_pembayaran']
                    ]);
                }
            } 
            // Untuk metode manual
            else if ($metodePembayaran === 'manual') {
                $pembayaranData['nama_bank'] = 'Pembayaran di Kantor';
                $pembayaranData['no_rek'] = '';
                $pembayaranData['bukti_pembayaran'] = null;
                
                Log::info('Data manual yang akan disimpan:', [
                    'jumlah_pembayaran' => $pembayaranData['jumlah_pembayaran'],
                    'jenis_pembayaran' => $pembayaranData['jenis_pembayaran']
                ]);
            }
            
            Log::info('Data untuk disimpan:', $pembayaranData);
            
            $pembayaran = Pembayaran::create($pembayaranData);
            Log::info('Pembayaran berhasil dibuat:', [
                'id' => $pembayaran->id,
                'metode' => $pembayaran->metode_pembayaran,
                'jumlah' => $pembayaran->jumlah_pembayaran
            ]);
            
            // Response berbeda berdasarkan metode
            $responseMessage = $metodePembayaran === 'manual' 
                ? 'Pembayaran manual berhasil disimpan. Silakan lakukan pembayaran di kantor sesuai instruksi.'
                : 'Pembayaran transfer berhasil disimpan. Silakan tunggu verifikasi dari admin.';
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pembayaran->id,
                    'nama' => $pembayaran->nama,
                    'metode_pembayaran' => $pembayaran->metode_pembayaran,
                    'status_pembayaran' => $pembayaran->status_pembayaran,
                    'status_text' => $pembayaran->status_text,
                    'tanggal_pembayaran' => $pembayaran->tanggal_pembayaran,
                    'nama_bank' => $pembayaran->nama_bank,
                    'no_rek' => $pembayaran->no_rek_decrypted,
                    'jumlah_pembayaran' => $pembayaran->jumlah_pembayaran,
                    'formatted_jumlah' => $pembayaran->formatted_jumlah,
                    'jenis_pembayaran' => $pembayaran->jenis_pembayaran,
                    'bukti_pembayaran' => $pembayaran->bukti_pembayaran_url,
                    'catatan_admin' => $pembayaran->catatan_admin,
                    'status_color' => $pembayaran->status_color,
                    'created_at' => $pembayaran->created_at->format('d/m/Y H:i'),
                ],
                'message' => $responseMessage
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Error menyimpan pembayaran:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail pembayaran
     */
    public function show(Request $request, $id)
    {
        $userId = $this->getUserId($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan'
            ], 401);
        }
        
        $pembayaran = Pembayaran::where('regist_pendaftar_id', $userId)
            ->where('id', $id)
            ->first();
        
        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pembayaran->id,
                'tanggal_pembayaran' => $pembayaran->tanggal_pembayaran,
                'nama' => $pembayaran->nama,
                'nama_bank' => $pembayaran->nama_bank,
                'no_rek' => $pembayaran->no_rek_decrypted,
                'metode_pembayaran' => $pembayaran->metode_pembayaran,
                'jumlah_pembayaran' => $pembayaran->jumlah_pembayaran,
                'formatted_jumlah' => $pembayaran->formatted_jumlah,
                'jenis_pembayaran' => $pembayaran->jenis_pembayaran,
                'bukti_pembayaran' => $pembayaran->bukti_pembayaran_url,
                'status_pembayaran' => $pembayaran->status_pembayaran,
                'status_text' => $pembayaran->status_text,
                'status_color' => $pembayaran->status_color,
                'catatan_admin' => $pembayaran->catatan_admin,
                'created_at' => $pembayaran->created_at->format('d/m/Y H:i'),
                'updated_at' => $pembayaran->updated_at->format('d/m/Y H:i'),
            ],
            'message' => 'Detail pembayaran berhasil diambil'
        ]);
    }

    /**
     * Update status pembayaran (untuk admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_pembayaran' => 'required|in:menunggu,diproses,diverifikasi,ditolak',
            'catatan_admin' => 'nullable|string|max:500',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }
        
        try {
            $pembayaran = Pembayaran::find($id);
            
            if (!$pembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan'
                ], 404);
            }
            
            $updateData = [
                'status_pembayaran' => $request->input('status_pembayaran'),
                'catatan_admin' => $request->input('catatan_admin'),
            ];
            
            // Handle file upload jika admin upload bukti (khusus untuk manual)
            if ($request->hasFile('bukti_pembayaran')) {
                // Hapus file lama jika ada
                if ($pembayaran->bukti_pembayaran) {
                    Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                }
                
                $file = $request->file('bukti_pembayaran');
                $filename = time() . '_admin_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('bukti-pembayaran', $filename, 'public');
                $updateData['bukti_pembayaran'] = 'bukti-pembayaran/' . $filename;
                Log::info('Admin uploaded file:', ['filename' => $filename, 'path' => $path]);
            }
            
            $pembayaran->update($updateData);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pembayaran->id,
                    'bukti_pembayaran' => $pembayaran->bukti_pembayaran_url,
                    'status_pembayaran' => $pembayaran->status_pembayaran,
                    'catatan_admin' => $pembayaran->catatan_admin,
                ],
                'message' => 'Status pembayaran berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error update status pembayaran: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update pembayaran (untuk user)
     */
    public function update(Request $request, $id)
    {
        $userId = $this->getUserId($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan'
            ], 401);
        }
        
        $pembayaran = Pembayaran::where('regist_pendaftar_id', $userId)
            ->where('id', $id)
            ->first();
        
        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }
        
        // Hanya bisa update jika status masih menunggu
        if ($pembayaran->status_pembayaran !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak dapat diubah karena sudah diproses'
            ], 400);
        }
        
        // Validasi untuk update - HARUS INCLUDE jumlah_pembayaran untuk SEMUA
        $rules = [
            'nama' => 'required|string|max:255',
            'tanggal_pembayaran' => 'required|date',
            'jumlah_pembayaran' => 'required|numeric|min:1000', // HARUS ADA untuk SEMUA
            'jenis_pembayaran' => 'nullable|string|max:255',
        ];
        
        if ($pembayaran->metode_pembayaran === 'transfer') {
            $rules['nama_bank'] = 'required|string|max:100';
            $rules['no_rek'] = 'required|string|max:255';
            $rules['bukti_pembayaran'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }
        
        try {
            // Menggunakan array seperti di store method
            $updateData = [
                'nama' => $request->input('nama'),
                'tanggal_pembayaran' => $request->input('tanggal_pembayaran'),
                'jumlah_pembayaran' => $request->input('jumlah_pembayaran'), // HARUS DISET untuk SEMUA
                'jenis_pembayaran' => $request->input('jenis_pembayaran', $pembayaran->jenis_pembayaran),
            ];
            
            if ($pembayaran->metode_pembayaran === 'transfer') {
                $updateData['nama_bank'] = $request->input('nama_bank');
                $updateData['no_rek'] = $request->input('no_rek');
                
                // Handle file upload untuk transfer
                if ($request->hasFile('bukti_pembayaran')) {
                    // Hapus file lama jika ada
                    if ($pembayaran->bukti_pembayaran) {
                        Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                    }
                    
                    $file = $request->file('bukti_pembayaran');
                    $filename = time() . '_update_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('bukti-pembayaran', $filename, 'public');
                    $updateData['bukti_pembayaran'] = 'bukti-pembayaran/' . $filename;
                    Log::info('File updated via API:', ['filename' => $filename, 'path' => $path]);
                }
            }
            // Untuk metode manual, set nilai default
            else if ($pembayaran->metode_pembayaran === 'manual') {
                $updateData['nama_bank'] = 'Pembayaran di Kantor';
                $updateData['no_rek'] = '';
            }
            
            Log::info('Data update untuk metode ' . $pembayaran->metode_pembayaran . ':', $updateData);
            
            $pembayaran->update($updateData);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pembayaran->id,
                    'nama' => $pembayaran->nama,
                    'metode_pembayaran' => $pembayaran->metode_pembayaran,
                    'status_pembayaran' => $pembayaran->status_pembayaran,
                    'status_text' => $pembayaran->status_text,
                    'tanggal_pembayaran' => $pembayaran->tanggal_pembayaran,
                    'nama_bank' => $pembayaran->nama_bank,
                    'no_rek' => $pembayaran->no_rek_decrypted,
                    'jumlah_pembayaran' => $pembayaran->jumlah_pembayaran,
                    'formatted_jumlah' => $pembayaran->formatted_jumlah,
                    'jenis_pembayaran' => $pembayaran->jenis_pembayaran,
                    'bukti_pembayaran' => $pembayaran->bukti_pembayaran_url,
                    'catatan_admin' => $pembayaran->catatan_admin,
                    'status_color' => $pembayaran->status_color,
                    'updated_at' => $pembayaran->updated_at->format('d/m/Y H:i'),
                ],
                'message' => 'Pembayaran berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error update pembayaran: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus pembayaran
     */
    public function destroy(Request $request, $id)
    {
        $userId = $this->getUserId($request);
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan'
            ], 401);
        }
        
        $pembayaran = Pembayaran::where('regist_pendaftar_id', $userId)
            ->where('id', $id)
            ->first();
        
        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }
        
        // Hanya bisa hapus jika status masih menunggu
        if ($pembayaran->status_pembayaran !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak dapat dihapus karena sudah diproses'
            ], 400);
        }
        
        try {
            // Hapus file bukti jika ada
            if ($pembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                Log::info('File deleted via API:', ['filename' => $pembayaran->bukti_pembayaran]);
            }
            
            $pembayaran->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error delete pembayaran: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method untuk testing - get semua pembayaran tanpa auth
     */
    public function testIndex()
    {
        try {
            Log::info('=== TEST INDEX METHOD CALLED ===');
            
            $pembayarans = Pembayaran::orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($pembayaran) {
                    return [
                        'id' => $pembayaran->id,
                        'regist_pendaftar_id' => $pembayaran->regist_pendaftar_id,
                        'nama' => $pembayaran->nama,
                        'metode_pembayaran' => $pembayaran->metode_pembayaran,
                        'status_pembayaran' => $pembayaran->status_pembayaran,
                        'tanggal_pembayaran' => $pembayaran->tanggal_pembayaran,
                        'nama_bank' => $pembayaran->nama_bank,
                        'no_rek' => $pembayaran->no_rek_decrypted,
                        'jumlah_pembayaran' => $pembayaran->jumlah_pembayaran,
                        'jenis_pembayaran' => $pembayaran->jenis_pembayaran,
                        'bukti_pembayaran' => $pembayaran->bukti_pembayaran_url,
                        'bukti_pembayaran_raw' => $pembayaran->bukti_pembayaran,
                        'created_at' => $pembayaran->created_at->format('d/m/Y H:i'),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $pembayarans,
                'message' => 'Data pembayaran test berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Test index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data test: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method untuk testing - create pembayaran tanpa auth
     */
    public function testStore(Request $request)
    {
        try {
            Log::info('=== TEST STORE METHOD CALLED ===');
            Log::info('Test request data:', $request->all());
            
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'metode_pembayaran' => 'required|string|in:transfer,manual',
                'tanggal_pembayaran' => 'required|date',
                'jumlah_pembayaran' => 'required|numeric|min:1000',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validasi gagal'
                ], 422);
            }
            
            // Cari user pertama atau buat dummy
            $user = RegistPendaftar::first();
            if (!$user) {
                // Buat dummy user untuk testing
                $user = RegistPendaftar::create([
                    'username' => 'testuser',
                    'email' => 'test@example.com',
                    'password' => bcrypt('password123')
                ]);
            }
            
            $pembayaranData = [
                'regist_pendaftar_id' => $user->id,
                'nama' => $request->input('nama'),
                'metode_pembayaran' => $request->input('metode_pembayaran'),
                'tanggal_pembayaran' => $request->input('tanggal_pembayaran'),
                'jumlah_pembayaran' => $request->input('jumlah_pembayaran'), // SELALU DISET
                'status_pembayaran' => 'menunggu',
                'nama_bank' => $request->input('metode_pembayaran') === 'transfer' ? 'Test Bank' : 'Pembayaran di Kantor',
                'no_rek' => $request->input('metode_pembayaran') === 'transfer' ? '1234567890' : '',
                'bukti_pembayaran' => null,
            ];
            
            $pembayaran = Pembayaran::create($pembayaranData);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pembayaran->id,
                    'metode_pembayaran' => $pembayaran->metode_pembayaran,
                    'jumlah_pembayaran' => $pembayaran->jumlah_pembayaran,
                    'formatted_jumlah' => $pembayaran->formatted_jumlah,
                    'bukti_pembayaran_url' => $pembayaran->bukti_pembayaran_url,
                ],
                'message' => 'Pembayaran test berhasil disimpan'
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Test store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pembayaran test: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Method untuk debug - lihat semua data pembayaran
     */
    public function debugAll()
    {
        try {
            $pembayarans = Pembayaran::with('registPendaftar')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($pembayaran) {
                    return [
                        'id' => $pembayaran->id,
                        'username' => $pembayaran->registPendaftar?->username,
                        'nama' => $pembayaran->nama,
                        'metode' => $pembayaran->metode_pembayaran,
                        'jumlah' => $pembayaran->jumlah_pembayaran,
                        'jenis_pembayaran' => $pembayaran->jenis_pembayaran,
                        'bukti_raw' => $pembayaran->bukti_pembayaran,
                        'bukti_url' => $pembayaran->bukti_pembayaran_url,
                        'file_exists' => $pembayaran->bukti_exists ?? false,
                        'status' => $pembayaran->status_pembayaran,
                        'created_at' => $pembayaran->created_at->format('d/m/Y H:i'),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $pembayarans,
                'message' => 'Debug data pembayaran'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}