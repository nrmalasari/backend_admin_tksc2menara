<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\RegistPendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RegistPendaftarController extends Controller
{
    /**
     * Register new pendaftar
     */
    public function register(Request $request)
    {
        Log::info('Register attempt', [
            'ip' => $request->ip(),
            'data' => $request->except('password', 'password_confirmation')
        ]);
        
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:50|unique:regist_pendaftars,username',
            'email' => 'required|email|unique:regist_pendaftars,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Creating regist pendaftar...');
            
            // Data sensitif untuk dienkripsi
            $sensitiveData = [
                'registration_info' => [
                    'ip' => $request->ip() ?: '127.0.0.1',
                    'user_agent' => $request->userAgent() ?: 'Unknown',
                    'timestamp' => now()->toISOString(),
                    'browser' => $request->header('User-Agent'),
                ]
            ];

            // Create pendaftar
            $pendaftar = RegistPendaftar::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password, // akan dienkripsi otomatis
                'encrypted_data' => $sensitiveData,
                'registration_ip' => $request->ip() ?: '127.0.0.1',
                'user_agent' => $request->userAgent() ?: 'Unknown',
            ]);

            Log::info('RegistPendaftar created successfully', [
                'id' => $pendaftar->id,
                'username' => $pendaftar->username,
                'email' => $pendaftar->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Password telah dienkripsi dengan AES-256-CBC.',
                'data' => [
                    'id' => $pendaftar->id,
                    'username' => $pendaftar->username,
                    'email' => $pendaftar->email,
                    'encrypted_password' => substr($pendaftar->password, 0, 50) . '...', // Tampilkan sebagian
                    'created_at' => $pendaftar->created_at->format('Y-m-d H:i:s'),
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login pendaftar
     */
    public function login(Request $request)
    {
        Log::info('Login attempt', [
            'username' => $request->username,
            'ip' => $request->ip()
        ]);
        
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Cari pendaftar
            $pendaftar = RegistPendaftar::where('username', $request->username)
                ->orWhere('email', $request->username)
                ->first();

            if (!$pendaftar) {
                Log::warning('Pendaftar not found', ['username' => $request->username]);
                return response()->json([
                    'success' => false,
                    'message' => 'Username/email tidak ditemukan'
                ], 404);
            }

            // Verifikasi password
            if (!$pendaftar->verifyPassword($request->password)) {
                Log::warning('Password verification failed', ['id' => $pendaftar->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah'
                ], 401);
            }

            Log::info('Login successful', [
                'id' => $pendaftar->id,
                'username' => $pendaftar->username
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'id' => $pendaftar->id,
                    'username' => $pendaftar->username,
                    'email' => $pendaftar->email,
                    'registration_date' => $pendaftar->created_at->format('Y-m-d'),
                    'encryption_status' => 'AES-256-CBC Active'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test encryption/decryption
     */
    public function testEncryption()
    {
        try {
            $testModel = new RegistPendaftar();
            $password = 'test123';
            
            $encrypted = $testModel->encryptAES($password);
            $decrypted = $testModel->decryptAES($encrypted);
            
            return response()->json([
                'success' => true,
                'test' => [
                    'original' => $password,
                    'encrypted' => $encrypted,
                    'decrypted' => $decrypted,
                    'match' => $password === $decrypted,
                    'encryption' => 'AES-256-CBC',
                    'key_length' => strlen($testModel->getEncryptionKey()),
                    'iv_length' => strlen($testModel->getEncryptionIV())
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}