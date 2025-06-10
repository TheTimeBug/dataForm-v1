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
     * Get all data submissions
     */
    public function getSubmissions()
    {
        $admin = JWTAuth::user();
        
        if ($admin->role !== 'admin') {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $submissions = DataRecord::with('user')->get();

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
}
