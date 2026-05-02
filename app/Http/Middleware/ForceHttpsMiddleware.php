<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS for production
        if (app()->environment('production') && !$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }
        
        // Set URL scheme to HTTPS
        URL::forceScheme('https');
        
        // Handle response
        $response = $next($request);
        
        // Fix Livewire URLs in response
        if ($this->shouldFixUrls($request)) {
            $content = $response->getContent();
            if ($content) {
                $content = str_replace(
                    'http://admin.tksc2menara.dpdns.org',
                    'https://admin.tksc2menara.dpdns.org',
                    $content
                );
                $response->setContent($content);
            }
        }
        
        return $response;
    }
    
    private function shouldFixUrls(Request $request): bool
    {
        return $request->is('livewire/*') || 
               $request->hasHeader('X-Livewire') ||
               str_contains($request->header('Content-Type', ''), 'application/json');
    }
}