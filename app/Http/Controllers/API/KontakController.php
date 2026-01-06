<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\KontakMail;
use App\Mail\KontakConfirmationMail;
use Illuminate\Support\Facades\RateLimiter;

class KontakController extends Controller
{
    /**
     * Submit pesan kontak dari website
     */
    public function store(Request $request)
    {
        try {
            Log::info('=== SUBMIT KONTAK API CALLED ===');
            Log::info('Data diterima:', $request->all());
            
            // Rate limiting
            $key = 'kontak:' . ($request->ip() ?: 'unknown');
            if (RateLimiter::tooManyAttempts($key, 5)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak permintaan. Silakan coba lagi nanti.'
                ], 429);
            }
            
            // Validasi
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'telepon' => 'nullable|string|max:20',
                'subject' => 'nullable|string|max:255',
                'pesan' => 'required|string|min:10|max:5000',
            ], [
                'nama.required' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'pesan.required' => 'Pesan wajib diisi',
                'pesan.min' => 'Pesan minimal 10 karakter',
                'pesan.max' => 'Pesan maksimal 5000 karakter',
            ]);
            
            if ($validator->fails()) {
                Log::warning('Validasi gagal:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Cek spam: pesan pendek berulang
            $pesan = $request->input('pesan');
            if (strlen($pesan) < 15 && preg_match('/(.)\1{5,}/', $pesan)) {
                RateLimiter::hit($key);
                return response()->json([
                    'success' => false,
                    'message' => 'Pesan terdeteksi sebagai spam.'
                ], 400);
            }
            
            // Simpan data
            $kontak = Kontak::create([
                'nama' => $request->input('nama'),
                'email' => $request->input('email'),
                'telepon' => $request->input('telepon'),
                'subject' => $request->input('subject'),
                'pesan' => $request->input('pesan'),
                'status' => 'terkirim',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            Log::info('Kontak berhasil disimpan, ID:', ['id' => $kontak->id]);
            
            // HAPUS BAGIAN EMAIL JIKA TIDAK DIBUTUHKAN
            // Atau comment untuk sementara:
            /*
            try {
                // Kirim email konfirmasi ke pengirim
                Mail::to($kontak->email)->send(new KontakConfirmationMail($kontak));
                Log::info('Email konfirmasi terkirim ke:', ['email' => $kontak->email]);
                
                // Kirim notifikasi ke admin
                $adminEmail = config('mail.admin_email', 'admin@tksc2menara.sch.id');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new KontakMail($kontak));
                    Log::info('Notifikasi terkirim ke admin:', ['email' => $adminEmail]);
                }
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email:', ['error' => $e->getMessage()]);
                // Tetap lanjut meski email gagal
            }
            */
            
            // Tambahkan hit rate limiter
            RateLimiter::hit($key);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda telah berhasil dikirim. Kami akan membalas segera.',
                'data' => $kontak->toApiFormat()
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Error submit kontak:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server. Silakan coba lagi nanti.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Get all kontak untuk admin (dengan auth)
     */
    public function index(Request $request)
    {
        try {
            // Verifikasi admin
            if (!$request->user() || !$request->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            Log::info('Admin mengambil data kontak');
            
            $query = Kontak::query();
            
            // Filter berdasarkan status
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }
            
            // Filter berdasarkan tanggal
            if ($request->has('start_date')) {
                $query->whereDate('created_at', '>=', $request->input('start_date'));
            }
            
            if ($request->has('end_date')) {
                $query->whereDate('created_at', '<=', $request->input('end_date'));
            }
            
            // Filter berdasarkan search
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('pesan', 'like', "%{$search}%");
                });
            }
            
            // Pagination
            $perPage = $request->input('per_page', 20);
            $kontaks = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            // Format response
            $data = $kontaks->map(function ($kontak) {
                return $kontak->toApiFormat();
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'current_page' => $kontaks->currentPage(),
                    'last_page' => $kontaks->lastPage(),
                    'per_page' => $kontaks->perPage(),
                    'total' => $kontaks->total(),
                    'from' => $kontaks->firstItem(),
                    'to' => $kontaks->lastItem(),
                ],
                'message' => 'Data kontak berhasil diambil'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error mengambil data kontak:', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kontak'
            ], 500);
        }
    }
    
    /**
     * Update status kontak (dibaca/dibalas)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            // Verifikasi admin
            if (!$request->user() || !$request->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            $kontak = Kontak::find($id);
            
            if (!$kontak) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kontak tidak ditemukan'
                ], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:dibaca,dibalas,spam',
                'tanggapan' => 'required_if:status,dibalas|nullable|string|max:5000',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $kontak->status = $request->input('status');
            
            if ($request->input('status') === 'dibalas') {
                $kontak->tanggapan = $request->input('tanggapan');
                $kontak->ditanggapi_oleh = $request->user()->id;
                $kontak->ditanggapi_pada = now();
            }
            
            $kontak->save();
            
            return response()->json([
                'success' => true,
                'data' => $kontak->toApiFormat(),
                'message' => 'Status kontak berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error update status kontak:', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status kontak'
            ], 500);
        }
    }
    
    /**
     * Test endpoint untuk debugging
     */
    public function testSubmit(Request $request)
    {
        try {
            Log::info('=== TEST KONTAK SUBMIT ===');
            
            $testData = [
                'nama' => 'John Doe',
                'email' => 'test@example.com',
                'telepon' => '081234567890',
                'subject' => 'Test Message',
                'pesan' => 'Ini adalah pesan test untuk memverifikasi sistem kontak berfungsi dengan baik.'
            ];
            
            $kontak = Kontak::create(array_merge($testData, [
                'status' => 'terkirim',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Test Client',
            ]));
            
            return response()->json([
                'success' => true,
                'message' => 'Test kontak berhasil dibuat',
                'data' => $kontak->toApiFormat()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test kontak gagal: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get test data for frontend
     */
    public function testData()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'form_fields' => [
                    'nama' => ['required' => true, 'type' => 'text', 'placeholder' => 'Masukkan Nama Anda'],
                    'email' => ['required' => true, 'type' => 'email', 'placeholder' => 'Masukkan Email Anda'],
                    'telepon' => ['required' => false, 'type' => 'tel', 'placeholder' => 'Masukkan Nomor Telepon Anda'],
                    'pesan' => ['required' => true, 'type' => 'textarea', 'placeholder' => 'Tuliskan Pesan Anda di sini...']
                ],
                'validation_rules' => [
                    'nama' => 'required|min:3|max:255',
                    'email' => 'required|email|max:255',
                    'pesan' => 'required|min:10|max:5000'
                ],
                'rate_limit' => '5 requests per minute',
                'endpoints' => [
                    'POST /api/kontak' => 'Submit pesan kontak',
                    'GET /api/kontak/test' => 'Test endpoint',
                    'GET /api/kontak/test-data' => 'Get test data'
                ]
            ]
        ]);
    }
}