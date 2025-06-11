<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AdminMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return response()->json(['error' => 'User not found'], 401);
            }

            // Cache admin privilege check for 5 minutes to improve performance
            $cacheKey = "admin_privileges_{$user->id}";
            $hasAdminPrivileges = Cache::remember($cacheKey, 300, function () use ($user) {
                return $user->hasAdminPrivileges();
            });

            if (!$hasAdminPrivileges) {
                Log::warning('Unauthorized admin access attempt', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                    'route' => $request->route()?->getName(),
                    'url' => $request->fullUrl()
                ]);
                
                return response()->json(['error' => 'Access denied. Admin privileges required.'], 403);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token absent'], 401);
        } catch (\Exception $e) {
            Log::error('Admin middleware error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Authentication error'], 500);
        }

        return $next($request);
    }
} 