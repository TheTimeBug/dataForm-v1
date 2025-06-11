<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
     * Add a new user
     */
    public function addUser(Request $request)
    {
        $admin = JWTAuth::user();
        
        if ($admin->role !== 'admin') {
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
        
        if ($admin->role !== 'admin') {
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
        
        if ($admin->role !== 'admin') {
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
        
        if ($admin->role !== 'admin') {
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
        
        if ($admin->role !== 'admin') {
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
        
        if ($admin->role !== 'admin') {
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
        
        if ($admin->role !== 'admin') {
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
        
        if ($admin->role !== 'admin') {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'admin_type' => 'required|in:national,divisional,district,upazila',
            'division_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'upazila_id' => 'nullable|integer',
            'replace_existing' => 'boolean'
        ]);

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
            'role' => 'admin',
            'admin_type' => $request->admin_type,
        ];

        if ($request->admin_type !== 'national') {
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
        
        if ($admin->role !== 'admin') {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $adminType = $request->get('admin_type', '');

        $query = User::where('role', 'admin')
            ->leftJoin('divisions', 'users.division_id', '=', 'divisions.id')
            ->leftJoin('districts', 'users.district_id', '=', 'districts.id')
            ->leftJoin('upazilas', 'users.upazila_id', '=', 'upazilas.id')
            ->select(
                'users.*',
                'divisions.name as division_name',
                'districts.name as district_name', 
                'upazilas.name as upazila_name'
            );

        // Add search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.mobile', 'like', "%{$search}%")
                  ->orWhere('divisions.name', 'like', "%{$search}%")
                  ->orWhere('districts.name', 'like', "%{$search}%")
                  ->orWhere('upazilas.name', 'like', "%{$search}%");
            });
        }

        // Add admin type filter
        if (!empty($adminType)) {
            $query->where('users.admin_type', $adminType);
        }

        // Order by latest first and paginate
        $admins = $query->orderBy('users.created_at', 'desc')->paginate($perPage);

        return response()->json($admins);
    }

    /**
     * Update an admin user
     */
    public function updateAdminUser(Request $request, $id)
    {
        $admin = JWTAuth::user();
        
        if ($admin->role !== 'admin') {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $adminUser = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'mobile' => 'sometimes|required|string|max:20',
            'password' => 'sometimes|required|string|min:6',
            'admin_type' => 'sometimes|required|in:national,divisional,district,upazila',
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
        
        if ($admin->role !== 'admin') {
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
}
