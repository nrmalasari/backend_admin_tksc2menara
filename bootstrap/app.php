<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',        // HANYA web.php
        // api: __DIR__.'/../routes/api.php',      // COMMENT OUT atau HAPUS
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // DISABLE CSRF UNTUK SEMUA ROUTE
        $middleware->validateCsrfTokens(except: ['*']);
        
        // Atau kalau mau lebih spesifik:
        // $middleware->validateCsrfTokens(except: ['api/*', 'api-backup/*']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();