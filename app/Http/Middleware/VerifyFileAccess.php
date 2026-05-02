<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\SiswaPendaftar;
use App\Models\Pembayaran;

class VerifyFileAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Hanya proses untuk route file khusus
        if ($request->route()->named('secure.file.show')) {
            $userId = $request->route('userId');
            $filename = $request->route('filename');
            
            Log::info('🔐 VerifyFileAccess - Checking: user=' . $userId . ', file=' . $filename);
            
            // 1. Verifikasi user yang request
            $requestUserId = $this->getRequestUserId($request);
            
            if (!$requestUserId) {
                Log::warning('🔐 VerifyFileAccess - No authenticated user');
                return $this->accessDenied();
            }
            
            // 2. Verifikasi user yang request sama dengan user di URL
            if ($requestUserId != $userId) {
                Log::warning('🔐 VerifyFileAccess - User mismatch. Request: ' . $requestUserId . ', URL: ' . $userId);
                return $this->accessDenied();
            }
            
            // 3. Verifikasi kepemilikan file
            if (!$this->verifyFileOwnership($userId, $filename)) {
                Log::warning('🔐 VerifyFileAccess - User ' . $userId . ' has no access to ' . $filename);
                return $this->accessDenied();
            }
            
            Log::info('🔐 VerifyFileAccess - Access granted for user ' . $userId);
        }
        
        return $next($request);
    }
    
    /**
     * Get user ID from request
     */
    private function getRequestUserId(Request $request)
    {
        // Dari Authorization header (API)
        $authHeader = $request->header('Authorization');
        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader);
            if (is_numeric($token) && (int)$token > 0) {
                return (int)$token;
            }
        }
        
        // Dari session (Web)
        if (Auth::check()) {
            return Auth::id();
        }
        
        // Untuk frontend website (tampilan umum), kita izinkan
        // Cek jika request dari frontend (bukan dashboard/user area)
        $referer = $request->header('referer');
        $userAgent = $request->userAgent();
        
        // Jika dari frontend website (bukan admin/dashboard), izinkan
        if ($referer) {
            // Frontend website biasanya tidak ada /admin, /dashboard, /user
            $isFrontend = !str_contains($referer, '/admin') && 
                          !str_contains($referer, '/dashboard') &&
                          !str_contains($referer, '/user') &&
                          !str_contains($referer, 'login');
            
            if ($isFrontend) {
                Log::info('🔐 VerifyFileAccess - Request from frontend website, allowing access');
                return 'frontend'; // Special value for frontend access
            }
        }
        
        return null;
    }
    
    /**
     * Verify file ownership
     */
    private function verifyFileOwnership($userId, $filename)
    {
        // Jika frontend access, cek apakah file milik siswa yang sudah diverifikasi
        if ($userId === 'frontend') {
            return $this->checkFrontendAccess($filename);
        }
        
        // Untuk user biasa, cek kepemilikan
        return SiswaPendaftar::where('regist_pendaftar_id', $userId)
            ->where(function($query) use ($filename) {
                $query->where('akte_kelahiran_path', 'LIKE', '%' . $filename)
                      ->orWhere('kartu_keluarga_path', 'LIKE', '%' . $filename)
                      ->orWhere('kia_path', 'LIKE', '%' . $filename)
                      ->orWhere('bpjs_path', 'LIKE', '%' . $filename);
            })
            ->exists();
    }
    
    /**
     * Check if file can be accessed from frontend
     */
    private function checkFrontendAccess($filename)
    {
        // File bisa diakses di frontend jika:
        // 1. Milik siswa yang sudah diverifikasi/diterima
        // 2. Atau file public lainnya
        
        $siswa = SiswaPendaftar::where(function($query) use ($filename) {
                $query->where('akte_kelahiran_path', 'LIKE', '%' . $filename)
                      ->orWhere('kartu_keluarga_path', 'LIKE', '%' . $filename)
                      ->orWhere('kia_path', 'LIKE', '%' . $filename)
                      ->orWhere('bpjs_path', 'LIKE', '%' . $filename);
            })
            ->first();
        
        if ($siswa) {
            // Hanya izinkan jika siswa sudah diverifikasi/diterima
            $allowedStatuses = ['diverifikasi', 'diproses', 'diterima', 'selesai'];
            return in_array($siswa->status, $allowedStatuses);
        }
        
        return false;
    }
    
    /**
     * Access denied response
     */
    private function accessDenied()
    {
        return response()->json([
            'success' => false,
            'message' => 'Access denied to file',
            'error' => 'FILE_ACCESS_DENIED'
        ], 403);
    }
}