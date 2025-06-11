<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerformanceMonitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $executionTime = round(($endTime - $startTime) * 1000, 2); // Convert to milliseconds
        $memoryUsage = round(($endMemory - $startMemory) / 1024 / 1024, 2); // Convert to MB

        // Log slow requests (over 1 second)
        if ($executionTime > 1000) {
            Log::warning('Slow API request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time_ms' => $executionTime,
                'memory_usage_mb' => $memoryUsage,
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'route' => $request->route()?->getName(),
            ]);
        }

        // Add performance headers to response for debugging
        if (config('app.debug')) {
            $response->headers->set('X-Response-Time', $executionTime . 'ms');
            $response->headers->set('X-Memory-Usage', $memoryUsage . 'MB');
            $response->headers->set('X-DB-Queries', \DB::getQueryLog() ? count(\DB::getQueryLog()) : 0);
        }

        return $response;
    }
} 