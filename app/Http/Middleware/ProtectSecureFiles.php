<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SiswaPendaftar;
use App\Models\Pembayaran;
use App\Models\RegistPendaftar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class ProtectSecureFiles
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        
        // Cek jika request mengakses file di api/secure-files/
        if (strpos($path, 'storage/api/secure-files/') !== false || 
            strpos($path, 'api/secure-files/') !== false ||
            strpos($path, 'storage/secure-files/') !== false) {
            
            Log::info('ProtectSecureFiles middleware triggered for path: ' . $path);
            
            // Ekstrak filename dari path
            $filename = basename($path);
            
            // Cek file type berdasarkan prefix
            if (strpos($filename, 'akte_') === 0) {
                $type = 'akte';
                $prefix = 'akte_';
            } elseif (strpos($filename, 'kk_') === 0) {
                $type = 'kk';
                $prefix = 'kk_';
            } elseif (strpos($filename, 'kia_') === 0) {
                $type = 'kia';
                $prefix = 'kia_';
            } elseif (strpos($filename, 'bpjs_') === 0) {
                $type = 'bpjs';
                $prefix = 'bpjs_';
            } elseif (strpos($filename, 'bukti_') === 0) {
                $type = 'bukti';
                $prefix = 'bukti_';
            } else {
                // Jika bukan file yang dikenal, izinkan akses
                Log::warning('Unknown file type accessed: ' . $filename);
                return $next($request);
            }
            
            // Cek jika user login (dari session atau token)
            $userId = $this->getUserId($request);
            
            Log::info('User ID from request:', ['user_id' => $userId, 'path' => $path]);
            
            if (!$userId) {
                Log::warning('Unauthorized file access attempt (no user ID) for file: ' . $filename);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to file. Please login.',
                    'filename' => $filename,
                    'error_code' => 'NO_SESSION_OR_TOKEN'
                ], 401);
            }
            
            // Cek hak akses user terhadap file ini
            $hasAccess = $this->checkFileAccess($userId, $filename, $type);
            
            if (!$hasAccess) {
                Log::warning('Access denied for file', [
                    'user_id' => $userId,
                    'filename' => $filename,
                    'type' => $type
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this file.',
                    'filename' => $filename
                ], 403);
            }
            
            Log::info('File access granted', [
                'user_id' => $userId,
                'filename' => $filename,
                'type' => $type
            ]);
        }
        
        return $next($request);
    }
    
    /**
     * Mendapatkan user ID dari berbagai sumber
     */
    private function getUserId(Request $request)
    {
        // 1. Cek dari session (web) - PRIORITAS UTAMA
        if (Auth::check()) {
            $userId = Auth::id();
            Log::debug('User ID from Auth::check(): ' . $userId);
            return $userId;
        }
        
        // 2. Cek dari API token (Bearer token)
        $authHeader = $request->header('Authorization');
        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader);
            if (is_numeric($token)) {
                $userId = (int)$token;
                Log::debug('User ID from Authorization header: ' . $userId);
                return $userId;
            }
        }
        
        // 3. Cek dari token di query parameter
        if ($request->filled('token')) {
            try {
                $token = $request->input('token');
                $decrypted = Crypt::decryptString($token);
                $parts = explode('|', $decrypted);
                
                if (count($parts) >= 3) {
                    $userId = (int)$parts[2]; // User ID ada di posisi ke-3
                    Log::debug('User ID from token parameter: ' . $userId);
                    return $userId;
                }
            } catch (\Exception $e) {
                Log::error('Token decryption error: ' . $e->getMessage());
            }
        }
        
        // 4. Cek dari cookie/session
        if ($request->hasCookie('laravel_session')) {
            // Coba dapatkan session
            $session = $request->session();
            if ($session && $session->has('user_id')) {
                $userId = $session->get('user_id');
                Log::debug('User ID from session cookie: ' . $userId);
                return $userId;
            }
        }
        
        Log::debug('No user ID found in any source');
        return null;
    }
    
    /**
     * Cek apakah user memiliki akses ke file
     */
    private function checkFileAccess($userId, $filename, $type)
    {
        try {
            // Cek berdasarkan tipe file
            switch ($type) {
                case 'akte':
                case 'kk':
                case 'kia':
                case 'bpjs':
                    // Cek di data SiswaPendaftar
                    return SiswaPendaftar::where('regist_pendaftar_id', $userId)
                        ->where(function($query) use ($filename, $type) {
                            switch ($type) {
                                case 'akte':
                                    return $query->where('akte_kelahiran_path', 'LIKE', '%' . $filename);
                                case 'kk':
                                    return $query->where('kartu_keluarga_path', 'LIKE', '%' . $filename);
                                case 'kia':
                                    return $query->where('kia_path', 'LIKE', '%' . $filename);
                                case 'bpjs':
                                    return $query->where('bpjs_path', 'LIKE', '%' . $filename);
                            }
                        })
                        ->exists();
                    
                case 'bukti':
                    // Cek di data Pembayaran
                    return Pembayaran::where('regist_pendaftar_id', $userId)
                        ->where('bukti_pembayaran_path', 'LIKE', '%' . $filename)
                        ->exists();
                    
                default:
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('Error checking file access: ' . $e->getMessage());
            return false;
        }
    }
}