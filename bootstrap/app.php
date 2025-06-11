<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin_level' => \App\Http\Middleware\AdminLevelMiddleware::class,
            'auth_web' => \App\Http\Middleware\AuthenticateWeb::class,
            'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'super_admin_web' => \App\Http\Middleware\SuperAdminWebMiddleware::class,
            'performance' => \App\Http\Middleware\PerformanceMonitor::class,
        ]);
        
        // Add performance monitoring to all API routes
        $middleware->api([
            'performance',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
