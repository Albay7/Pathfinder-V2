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
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust proxies for Railway deployment
        $middleware->trustProxies(at: '*');
        
        // Add security headers middleware
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        
        // Force HTTPS in production
        if (env('APP_ENV') === 'production') {
            $middleware->redirectTo(fn () => request()->secure() ? null : request()->getSchemeAndHttpHost());
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
