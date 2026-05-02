<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckLogin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        
        // Hanya proses untuk file di api/secure-files/
        if (strpos($path, 'storage/api/secure-files/') === 0) {
            $filename = basename($path);
            
            // LOG UNTUK DEBUG
            Log::info('🔐 CheckLogin - File access attempt: ' . $filename);
            Log::info('🔐 CheckLogin - Is user logged in? ' . (Auth::check() ? 'YES' : 'NO'));
            Log::info('🔐 CheckLogin - User ID: ' . (Auth::id() ?? 'NULL'));
            
            // CEK SANGAT SIMPLE: APAKAH USER LOGIN?
            if (!Auth::check()) {
                Log::warning('🔐 CheckLogin - BLOCKED: User is NOT logged in!');
                
                // RETURN 403 ATAU GAMBAR DEFAULT
                return $this->blockAccess();
            }
            
            Log::info('🔐 CheckLogin - ALLOWED: User is logged in');
        }
        
        return $next($request);
    }
    
    /**
     * Return blocked access
     */
    private function blockAccess()
    {
        // Return gambar lock/kunci
        // Kamu bisa buat gambar lock.jpg di public/images/
        
        $lockImagePath = public_path('images/lock.jpg');
        
        if (file_exists($lockImagePath)) {
            return response()->file($lockImagePath, [
                'Content-Type' => 'image/jpeg',
                'Cache-Control' => 'no-cache, no-store'
            ]);
        }
        
        // Jika gambar lock tidak ada, return 403
        return response('Access Denied - Please login to view this file', 403)
            ->header('Content-Type', 'text/plain');
    }
}