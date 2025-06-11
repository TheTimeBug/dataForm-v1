<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminLevelMiddleware
{
    /**
     * Handle an incoming request with admin level requirements.
     * 
     * @param string $level minimum required admin level: superadmin, national, divisional, district, upazila
     */
    public function handle(Request $request, Closure $next, string $level = null): Response
    {
        $user = Auth::guard('api')->user();
        
        if (!$user || !$user->hasAdminPrivileges()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied. Admin privileges required.'
                ], 403);
            }
            
            return redirect()->route('unauthorized');
        }

        // If no specific level required, just check admin privileges
        if (!$level) {
            return $next($request);
        }

        // Check admin level hierarchy
        $requiredLevel = $this->getAdminLevel($level);
        $userLevel = $user->getAdminLevel();

        if ($userLevel < $requiredLevel) {
            $message = "Access denied. {$this->getAdminTypeLabel($level)} privileges or higher required.";
            
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 403);
            }
            
            return redirect()->route('unauthorized');
        }

        return $next($request);
    }

    /**
     * Get numeric level for admin type
     */
    private function getAdminLevel(string $adminType): int
    {
        $levels = [
            'upazila' => 1,
            'district' => 2,
            'divisional' => 3,
            'national' => 4,
            'superadmin' => 5
        ];
        
        return $levels[$adminType] ?? 0;
    }

    /**
     * Get human-readable label for admin type
     */
    private function getAdminTypeLabel(string $adminType): string
    {
        $labels = [
            'upazila' => 'Upazila Admin',
            'district' => 'District Admin',
            'divisional' => 'Divisional Admin',
            'national' => 'National Admin',
            'superadmin' => 'Super Admin'
        ];
        
        return $labels[$adminType] ?? 'Admin';
    }
} 