<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil path yang diakses
        $path = $request->path();
        
        // Jika mengakses file di storage/api/secure-files/
        if (strpos($path, 'storage/api/secure-files/') === 0) {
            
            // SIMPLE CHECK: APAKAH USER LOGIN?
            if (!Auth::check()) {
                // User TIDAK login - BLOKIR!
                return $this->showLockImage();
            }
            
            // User sudah login - IZINKAN
        }
        
        return $next($request);
    }
    
    private function showLockImage()
    {
        // Coba cari gambar lock di public
        $lockImages = [
            public_path('images/lock.jpg'),
            public_path('images/locked.png'),
            public_path('lock.jpg'),
        ];
        
        foreach ($lockImages as $imagePath) {
            if (file_exists($imagePath)) {
                return response()->file($imagePath, [
                    'Content-Type' => 'image/jpeg',
                    'Cache-Control' => 'no-store, no-cache'
                ]);
            }
        }
        
        // Jika tidak ada gambar lock, buat response text
        return response('<h1>File Terkunci</h1><p>Silakan login untuk mengakses file ini.</p>', 403)
            ->header('Content-Type', 'text/html');
    }
}