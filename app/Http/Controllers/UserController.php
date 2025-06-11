<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataRecord;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Store a new data record
     */
    public function storeDataRecord(Request $request)
    {
        $request->validate([
            'integer_field_1' => 'required|integer',
            'integer_field_2' => 'required|integer',
            'integer_field_3' => 'required|integer',
            'integer_field_4' => 'required|integer',
            'selector_field_1' => 'required|string',
            'selector_field_2' => 'required|string',
            'selector_field_3' => 'required|string',
            'selector_field_4' => 'required|string',
            'comment_field_1' => 'required|string',
            'comment_field_2' => 'required|string',
        ]);

        $user = JWTAuth::user();

        $dataRecord = DataRecord::create([
            'user_id' => $user->id,
            'integer_field_1' => $request->integer_field_1,
            'integer_field_2' => $request->integer_field_2,
            'integer_field_3' => $request->integer_field_3,
            'integer_field_4' => $request->integer_field_4,
            'selector_field_1' => $request->selector_field_1,
            'selector_field_2' => $request->selector_field_2,
            'selector_field_3' => $request->selector_field_3,
            'selector_field_4' => $request->selector_field_4,
            'comment_field_1' => $request->comment_field_1,
            'comment_field_2' => $request->comment_field_2,
        ]);

        return response()->json([
            'message' => 'Data record created successfully',
            'data' => $dataRecord
        ], 201);
    }

    /**
     * Get user's data records with pagination and search
     */
    public function getDataRecords(Request $request)
    {
        $user = JWTAuth::user();
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        
        $query = DataRecord::where('user_id', $user->id)
            ->where('is_edit_request', false);
        
        // Add search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('integer_field_1', 'like', "%{$search}%")
                  ->orWhere('integer_field_2', 'like', "%{$search}%")
                  ->orWhere('integer_field_3', 'like', "%{$search}%")
                  ->orWhere('integer_field_4', 'like', "%{$search}%")
                  ->orWhere('selector_field_1', 'like', "%{$search}%")
                  ->orWhere('selector_field_2', 'like', "%{$search}%")
                  ->orWhere('selector_field_3', 'like', "%{$search}%")
                  ->orWhere('selector_field_4', 'like', "%{$search}%")
                  ->orWhere('comment_field_1', 'like', "%{$search}%")
                  ->orWhere('comment_field_2', 'like', "%{$search}%");
            });
        }
        
        // For simple requests, return non-paginated data
        if ($request->get('simple', false) || !$request->has('per_page')) {
            $dataRecords = $query->orderBy('created_at', 'desc')->get();
            return response()->json($dataRecords);
        }
        
        // Order by latest first and paginate
        $dataRecords = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($dataRecords);
    }

    /**
     * Get pending edit requests for the user with pagination and search
     */
    public function getEditRequests(Request $request)
    {
        $user = JWTAuth::user();
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        
        $query = DataRecord::where('user_id', $user->id)
            ->where('is_edit_request', true)
            ->where('status', 'pending')  // Only show pending requests
            ->with(['admin', 'parent']);
        
        // Add search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('admin_notes', 'like', "%{$search}%")
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
                  ->orWhereHas('admin', function($adminQuery) use ($search) {
                      $adminQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // For simple requests, return non-paginated data
        if ($request->get('simple', false) || !$request->has('per_page')) {
            $editRequests = $query->orderBy('created_at', 'asc')->get();
            return response()->json($editRequests);
        }
        
        // Order by oldest first and paginate
        $editRequests = $query->orderBy('created_at', 'asc')->paginate($perPage);

        return response()->json($editRequests);
    }

    /**
     * Get edit history for the user with pagination and search
     */
    public function getEditHistory(Request $request)
    {
        $user = JWTAuth::user();
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        
        $query = DataRecord::where('user_id', $user->id)
            ->where('is_edit_request', true)
            ->where('status', 'completed')  // Only show completed requests
            ->with(['admin', 'parent']);
        
        // Add search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('admin_notes', 'like', "%{$search}%")
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
        
        // For simple requests, return non-paginated data
        if ($request->get('simple', false) || !$request->has('per_page')) {
            $editHistory = $query->orderBy('updated_at', 'desc')->get();
            return response()->json($editHistory);
        }
        
        // Order by latest first and paginate
        $editHistory = $query->orderBy('updated_at', 'desc')->paginate($perPage);

        return response()->json($editHistory);
    }

    /**
     * Update data record from edit request
     */
    public function updateDataRecord(Request $request, $editRequestId)
    {
        $user = JWTAuth::user();
        
        $editRequest = DataRecord::where('id', $editRequestId)
            ->where('user_id', $user->id)
            ->where('is_edit_request', true)
            ->where('status', 'pending')
            ->with('parent')
            ->first();

        if (!$editRequest) {
            return response()->json(['error' => 'Edit request not found or already completed'], 404);
        }

        $request->validate([
            'integer_field_1' => 'required|integer',
            'integer_field_2' => 'required|integer',
            'integer_field_3' => 'required|integer',
            'integer_field_4' => 'required|integer',
            'selector_field_1' => 'required|string',
            'selector_field_2' => 'required|string',
            'selector_field_3' => 'required|string',
            'selector_field_4' => 'required|string',
            'comment_field_1' => 'required|string',
            'comment_field_2' => 'required|string',
        ]);

        // Update the original data record
        if ($editRequest->parent) {
            $editRequest->parent->update([
                'integer_field_1' => $request->integer_field_1,
                'integer_field_2' => $request->integer_field_2,
                'integer_field_3' => $request->integer_field_3,
                'integer_field_4' => $request->integer_field_4,
                'selector_field_1' => $request->selector_field_1,
                'selector_field_2' => $request->selector_field_2,
                'selector_field_3' => $request->selector_field_3,
                'selector_field_4' => $request->selector_field_4,
                'comment_field_1' => $request->comment_field_1,
                'comment_field_2' => $request->comment_field_2,
            ]);
        }

        // Update the edit request record with new data and mark as completed
        $editRequest->update([
            'integer_field_1' => $request->integer_field_1,
            'integer_field_2' => $request->integer_field_2,
            'integer_field_3' => $request->integer_field_3,
            'integer_field_4' => $request->integer_field_4,
            'selector_field_1' => $request->selector_field_1,
            'selector_field_2' => $request->selector_field_2,
            'selector_field_3' => $request->selector_field_3,
            'selector_field_4' => $request->selector_field_4,
            'comment_field_1' => $request->comment_field_1,
            'comment_field_2' => $request->comment_field_2,
            'status' => 'completed'
        ]);

        return response()->json([
            'message' => 'Data record updated successfully',
            'data' => $editRequest->parent ?: $editRequest
        ]);
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $user = JWTAuth::user();
        
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
            'password_updated_at' => now()
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }
}
