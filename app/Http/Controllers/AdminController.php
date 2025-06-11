<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\DataRecord;
use App\Models\DataEditHistory;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
    public function __construct()
    {
        // Apply auth middleware only to API methods, not view methods
        $this->middleware('auth:api')->except(['pendingEditRequests', 'editHistory']);
    }

    /**
     * Get authenticated admin user (optimized)
     */
    public function me()
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // Use caching for better performance
        $cacheKey = "admin_me_data_{$admin->id}";
        $adminData = Cache::remember($cacheKey, 300, function () use ($admin) {
            // Load area relationships for admin
            $admin->load(['division', 'district', 'upazila']);
            
            // Add area names to the response
            $adminData = $admin->toArray();
            if ($admin->division) {
                $adminData['division_name'] = $admin->division->name;
            }
            if ($admin->district) {
                $adminData['district_name'] = $admin->district->name;
            }
            if ($admin->upazila) {
                $adminData['upazila_name'] = $admin->upazila->name;
            }
            
            return $adminData;
        });

        return response()->json($adminData);
    }

    /**
     * Add a new user
     */
    public function addUser(Request $request)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * Get all data submissions with pagination, search and filtering
     */
    public function getSubmissions(Request $request)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $user = $request->get('user', '');

        $query = DataRecord::where('is_edit_request', false)
            ->with('user');

        // Add search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('integer_field_1', 'like', "%{$search}%")
                  ->orWhere('integer_field_2', 'like', "%{$search}%")
                  ->orWhere('integer_field_3', 'like', "%{$search}%")
                  ->orWhere('integer_field_4', 'like', "%{$search}%")
                  ->orWhere('selector_field_1', 'like', "%{$search}%")
                  ->orWhere('selector_field_2', 'like', "%{$search}%")
                  ->orWhere('selector_field_3', 'like', "%{$search}%")
                  ->orWhere('selector_field_4', 'like', "%{$search}%")
                  ->orWhere('comment_field_1', 'like', "%{$search}%")
                  ->orWhere('comment_field_2', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Add date range filter
        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Add user filter
        if (!empty($user)) {
            $query->where('user_id', $user);
        }

        // For simple requests, return non-paginated data
        if ($request->get('simple', false) || !$request->has('per_page')) {
            $submissions = $query->orderBy('created_at', 'desc')->get();
            return response()->json($submissions);
        }

        // Order by latest first and paginate
        $submissions = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($submissions);
    }

    /**
     * Send edit request (refined for new structure)
     */
    public function sendEditRequest(Request $request)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $request->validate([
            'data_record_id' => 'required|exists:data_records,id',
            'admin_notes' => 'nullable|string',
        ]);

        $originalRecord = DataRecord::where('id', $request->data_record_id)
            ->where('is_edit_request', false)
            ->first();

        if (!$originalRecord) {
            return response()->json(['error' => 'Data record not found'], 404);
        }

        // Create edit request record
        $editRequest = DataRecord::create([
            'user_id' => $originalRecord->user_id,
            'is_edit_request' => true,
            'parent_id' => $originalRecord->id,
            'status' => 'pending',
            'admin_id' => $admin->id,
            'admin_notes' => $request->admin_notes,
            'integer_field_1' => $originalRecord->integer_field_1,
            'integer_field_2' => $originalRecord->integer_field_2,
            'integer_field_3' => $originalRecord->integer_field_3,
            'integer_field_4' => $originalRecord->integer_field_4,
            'selector_field_1' => $originalRecord->selector_field_1,
            'selector_field_2' => $originalRecord->selector_field_2,
            'selector_field_3' => $originalRecord->selector_field_3,
            'selector_field_4' => $originalRecord->selector_field_4,
            'comment_field_1' => $originalRecord->comment_field_1,
            'comment_field_2' => $originalRecord->comment_field_2,
        ]);

        // Create edit history entry
        DataEditHistory::create([
            'data_record_id' => $editRequest->id,
            'field_name' => 'edit_request_created',
            'old_value' => null,
            'new_value' => 'Edit request created by admin',
            'changed_by' => $admin->id,
            'action_type' => 'edit_request',
        ]);

        return response()->json([
            'message' => 'Edit request sent successfully',
            'edit_request' => $editRequest->load(['user', 'admin', 'parent'])
        ], 201);
    }

    /**
     * Get edit requests with pagination and filters
     */
    public function getEditRequests(Request $request)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $status = $request->get('status', '');

        $query = DataRecord::where('is_edit_request', true)
            ->with(['user', 'admin', 'parent']);

        // Add search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('admin_notes', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('admin', function($adminQuery) use ($search) {
                      $adminQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Add status filter
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Order by latest first and paginate
        $editRequests = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($editRequests);
    }

    /**
     * Get edit history with advanced filters and pagination
     */
    public function getEditHistory(Request $request)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $status = $request->get('status', '');

        // Use DataRecord for edit history instead of DataEditHistory for more comprehensive data
        $query = DataRecord::where('is_edit_request', true)
            ->with(['user', 'admin', 'parent']);

        // Add search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('admin_notes', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('admin', function($adminQuery) use ($search) {
                      $adminQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Add date range filter
        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Add status filter
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Order by latest first and paginate
        $editHistory = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($editHistory);
    }

    /**
     * Get all users
     */
    public function getUsers()
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $users = User::all();

        return response()->json($users);
    }

    /**
     * Show pending edit requests page
     */
    public function pendingEditRequests()
    {
        return view('admin.edit-requests.pending');
    }

    /**
     * Show edit history page  
     */
    public function editHistory()
    {
        return view('admin.edit-requests.history');
    }

    /**
     * Check if admins already exist for the specified area
     */
    public function checkExistingAdmins(Request $request)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $request->validate([
            'admin_type' => 'required|in:national,divisional,district,upazila',
            'division_id' => 'nullable|integer',
            'district_id' => 'nullable|integer', 
            'upazila_id' => 'nullable|integer',
        ]);

        $query = User::where('role', 'admin')
            ->where('admin_type', $request->admin_type);

        // Add area-specific filters based on admin type
        if ($request->admin_type === 'divisional' && $request->division_id) {
            $query->where('division_id', $request->division_id);
        } elseif ($request->admin_type === 'district' && $request->district_id) {
            $query->where('district_id', $request->district_id);
        } elseif ($request->admin_type === 'upazila' && $request->upazila_id) {
            $query->where('upazila_id', $request->upazila_id);
        }

        $existingAdmins = $query->get();

        return response()->json([
            'exists' => $existingAdmins->count() > 0,
            'admins' => $existingAdmins
        ]);
    }

    /**
     * Create a new admin user
     */
    public function createAdminUser(Request $request)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'admin_type' => 'required|in:national,divisional,district,upazila,superadmin',
            'division_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'upazila_id' => 'nullable|integer',
            'replace_existing' => 'boolean'
        ]);

        // Check if admin can create this type of admin
        $targetAdminType = $request->get('admin_type');
        if (!in_array($targetAdminType, $admin->getAllowedAdminTypes())) {
            return response()->json(['error' => 'You do not have permission to create this type of admin'], 403);
        }

        // Check area restrictions for non-superadmin/national admins
        if (!in_array($admin->admin_type, ['superadmin', 'national'])) {
            $divisionId = $request->get('division_id');
            $districtId = $request->get('district_id');
            $upazilaId = $request->get('upazila_id');

            if (!$admin->canAccessArea($divisionId, $districtId, $upazilaId)) {
                return response()->json(['error' => 'You can only create admins within your area of authority'], 403);
            }
        }

        // If replace_existing is true, remove existing admins for this area
        if ($request->get('replace_existing', false)) {
            $deleteQuery = User::where('role', 'admin')
                ->where('admin_type', $request->admin_type);

            if ($request->admin_type === 'divisional' && $request->division_id) {
                $deleteQuery->where('division_id', $request->division_id);
            } elseif ($request->admin_type === 'district' && $request->district_id) {
                $deleteQuery->where('district_id', $request->district_id);
            } elseif ($request->admin_type === 'upazila' && $request->upazila_id) {
                $deleteQuery->where('upazila_id', $request->upazila_id);
            }

            $deleteQuery->delete();
        }

        // Create the new admin user
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Always admin role
            'admin_type' => $request->admin_type,
            'created_by' => $admin->id, // Track who created this admin
        ];

        if (!in_array($request->admin_type, ['national', 'superadmin'])) {
            if ($request->division_id) {
                $userData['division_id'] = $request->division_id;
            }
            if ($request->district_id) {
                $userData['district_id'] = $request->district_id;  
            }
            if ($request->upazila_id) {
                $userData['upazila_id'] = $request->upazila_id;
            }
        }

        $newAdmin = User::create($userData);

        return response()->json([
            'message' => 'Admin user created successfully',
            'admin' => $newAdmin
        ], 201);
    }

    /**
     * Get admin users with pagination and filtering
     */
    public function getAdminUsers(Request $request)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $adminType = $request->get('admin_type', '');

            // Simplified query first to debug
            $query = User::where('role', 'admin')
                ->with(['division', 'district', 'upazila', 'createdBy']);

            // Apply hierarchical filtering based on current admin's level
            $this->applyHierarchicalFilter($query, $admin);

            // Add search functionality
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('mobile', 'like', "%{$search}%");
                });
            }

            // Add admin type filter
            if (!empty($adminType)) {
                $query->where('admin_type', $adminType);
            }

            // Order by latest first and paginate
            $admins = $query->orderBy('created_at', 'desc')->paginate($perPage);

            // Transform the data to include area names
            $admins->getCollection()->transform(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'admin_type' => $user->admin_type,
                    'status' => $user->status,
                    'division_id' => $user->division_id,
                    'district_id' => $user->district_id,
                    'upazila_id' => $user->upazila_id,
                    'division_name' => $user->division ? $user->division->name : null,
                    'district_name' => $user->district ? $user->district->name : null,
                    'upazila_name' => $user->upazila ? $user->upazila->name : null,
                    'created_by_name' => $user->createdBy ? $user->createdBy->name : 'System',
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ];
            });

            return response()->json($admins);

        } catch (\Exception $e) {
            \Log::error('Error in getAdminUsers: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Failed to fetch admin users',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an admin user
     */
    public function updateAdminUser(Request $request, $id)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $adminUser = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'mobile' => 'sometimes|required|string|max:20',
            'password' => 'sometimes|required|string|min:6',
            'admin_type' => 'sometimes|required|in:national,divisional,district,upazila,superadmin',
            'status' => 'sometimes|required|in:active,inactive,suspended',
            'division_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'upazila_id' => 'nullable|integer',
        ]);

        $updateData = $request->only(['name', 'email', 'mobile', 'admin_type', 'status', 'division_id', 'district_id', 'upazila_id']);

        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $adminUser->update($updateData);

        return response()->json([
            'message' => 'Admin user updated successfully',
            'admin' => $adminUser->fresh()
        ]);
    }

    /**
     * Delete an admin user
     */
    public function deleteAdminUser($id)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $adminUser = User::where('role', 'admin')->findOrFail($id);
        
        // Prevent self-deletion
        if ($adminUser->id === $admin->id) {
            return response()->json(['error' => 'Cannot delete your own admin account'], 403);
        }

        $adminUser->delete();

        return response()->json([
            'message' => 'Admin user deleted successfully'
        ]);
    }

    /**
     * Apply hierarchical filtering to admin queries
     */
    private function applyHierarchicalFilter($query, $admin)
    {
        // Superadmin can see all admins
        if ($admin->admin_type === 'superadmin') {
            return;
        }

        // National admin can see all except superadmin
        if ($admin->admin_type === 'national') {
            $query->where('users.admin_type', '!=', 'superadmin');
            return;
        }

        // Divisional admin can only see district and upazila in their division
        if ($admin->admin_type === 'divisional') {
            $query->whereIn('users.admin_type', ['district', 'upazila'])
                  ->where('users.division_id', $admin->division_id);
            return;
        }

        // District admin can only see upazila in their district
        if ($admin->admin_type === 'district') {
            $query->where('users.admin_type', 'upazila')
                  ->where('users.district_id', $admin->district_id);
            return;
        }

        // Upazila admin cannot see other admins
        if ($admin->admin_type === 'upazila') {
            $query->where('users.id', 0); // No results
            return;
        }
    }

    /**
     * Get allowed admin types for current user
     */
    public function getAllowedAdminTypes()
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        return response()->json([
            'allowed_types' => $admin->getAllowedAdminTypes()
        ]);
    }

    /**
     * Change admin password
     */
    public function changePassword(Request $request)
    {
        $admin = JWTAuth::user();
        
        if (!$admin->hasAdminPrivileges()) {
            return response()->json(['error' => 'Access denied'], 403);
        }
        
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $admin->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        // Update password
        $admin->update([
            'password' => Hash::make($request->new_password),
            'password_updated_at' => now()
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }
}
