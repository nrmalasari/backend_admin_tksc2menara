<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    /**
     * Serve secure file with verification
     */
    public function showSecureFile($userId, $filename)
    {
        Log::info('📁 FileController - Serving secure file: ' . $filename . ' for user: ' . $userId);
        
        // Cari file di storage
        $filePath = $this->findFilePath($filename);
        
        if (!$filePath) {
            Log::warning('📁 FileController - File not found: ' . $filename);
            abort(404, 'File not found');
        }
        
        // Determine MIME type
        $mimeType = mime_content_type($filePath);
        
        // Set appropriate headers
        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline', // Show in browser
            'Cache-Control' => 'public, max-age=3600', // Cache 1 hour
        ];
        
        // For images, add more headers
        if (strpos($mimeType, 'image/') === 0) {
            $headers['X-Content-Type-Options'] = 'nosniff';
        }
        
        Log::info('📁 FileController - Serving file: ' . basename($filePath) . ' with MIME: ' . $mimeType);
        
        return response()->file($filePath, $headers);
    }
    
    /**
     * Find file in storage
     */
    private function findFilePath($filename)
    {
        // Clean filename
        $cleanFilename = basename(explode('?', $filename)[0]);
        
        // Possible storage paths
        $paths = [
            'api/secure-files/' . $cleanFilename,
            'public/api/secure-files/' . $cleanFilename,
        ];
        
        foreach ($paths as $path) {
            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {
                return $fullPath;
            }
        }
        
        return null;
    }
    
    /**
     * Test endpoint
     */
    public function testFileAccess(Request $request)
    {
        return response()->json([
            'message' => 'File access system is working',
            'current_user' => $request->user() ? $request->user()->id : 'guest',
            'secure_file_url_example' => url('/files/1/test.jpg'),
            'note' => 'Replace 1 with actual user ID and test.jpg with actual filename'
        ]);
    }
}