<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthorizationController extends Controller
{
    /**
     * Check if current user can access admin features
     */
    public function checkAdminAccess(Request $request)
    {
        try {
            $user = JWTAuth::user();
            
            if (!$user || !$user->hasAdminPrivileges()) {
                return response()->json([
                    'authorized' => false,
                    'message' => 'Admin access required'
                ], 403);
            }

            return response()->json([
                'authorized' => true,
                'user' => $user,
                'admin_type' => $user->admin_type,
                'level' => $user->getAdminLevel()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'authorized' => false,
                'message' => 'Authentication failed'
            ], 401);
        }
    }

    /**
     * Check if current user can access specific admin level
     */
    public function checkAdminLevel(Request $request, $requiredLevel)
    {
        try {
            $user = JWTAuth::user();
            
            if (!$user || !$user->hasAdminPrivileges()) {
                return response()->json([
                    'authorized' => false,
                    'message' => 'Admin access required'
                ], 403);
            }

            $userLevel = $user->getAdminLevel();
            $requiredLevelNum = $this->getAdminLevelNumber($requiredLevel);

            $authorized = $userLevel >= $requiredLevelNum;

            return response()->json([
                'authorized' => $authorized,
                'user_level' => $userLevel,
                'required_level' => $requiredLevelNum,
                'message' => $authorized ? 'Access granted' : "Requires {$requiredLevel} level or higher"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'authorized' => false,
                'message' => 'Authentication failed'
            ], 401);
        }
    }

    /**
     * Check if current user can access superadmin features
     */
    public function checkSuperAdminAccess(Request $request)
    {
        try {
            $user = JWTAuth::user();
            
            $authorized = $user && $user->admin_type === 'superadmin';

            return response()->json([
                'authorized' => $authorized,
                'message' => $authorized ? 'Superadmin access granted' : 'Superadmin access required'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'authorized' => false,
                'message' => 'Authentication failed'
            ], 401);
        }
    }

    /**
     * Check if current user can manage specific area
     */
    public function checkAreaAccess(Request $request)
    {
        try {
            $user = JWTAuth::user();
            
            if (!$user || !$user->hasAdminPrivileges()) {
                return response()->json([
                    'authorized' => false,
                    'message' => 'Admin access required'
                ], 403);
            }

            $divisionId = $request->input('division_id');
            $districtId = $request->input('district_id');
            $upazilaId = $request->input('upazila_id');

            $authorized = $user->canAccessArea($divisionId, $districtId, $upazilaId);

            return response()->json([
                'authorized' => $authorized,
                'message' => $authorized ? 'Area access granted' : 'Access to this area denied'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'authorized' => false,
                'message' => 'Authentication failed'
            ], 401);
        }
    }

    /**
     * Get admin level number
     */
    private function getAdminLevelNumber($level)
    {
        $levels = [
            'upazila' => 1,
            'district' => 2,
            'divisional' => 3,
            'national' => 4,
            'superadmin' => 5
        ];
        
        return $levels[$level] ?? 0;
    }
} 