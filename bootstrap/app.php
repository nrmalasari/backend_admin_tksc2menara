<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: ['*']);
        
        // Register middleware baru
        $middleware->alias([
            'is.login' => \App\Http\Middleware\IsLogin::class,
        ]);
        
        // Tambahkan ke semua request
        $middleware->web(append: [
            \App\Http\Middleware\IsLogin::class,
        ]);
        
        $middleware->api(append: [
            \App\Http\Middleware\IsLogin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();