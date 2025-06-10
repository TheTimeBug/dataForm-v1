<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataRecord;
use App\Models\EditRequest;
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
        
        $query = DataRecord::where('user_id', $user->id);
        
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
        
        // Order by latest first and paginate
        $dataRecords = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($dataRecords);
    }

    /**
     * Get edit requests for the user with pagination and search
     */
    public function getEditRequests(Request $request)
    {
        $user = JWTAuth::user();
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        
        $query = EditRequest::where('user_id', $user->id)
            ->with(['dataRecord', 'admin']);
        
        // Add search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('admin_notes', 'like', "%{$search}%")
                  ->orWhereHas('admin', function($adminQuery) use ($search) {
                      $adminQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('dataRecord', function($dataQuery) use ($search) {
                      $dataQuery->where('integer_field_1', 'like', "%{$search}%")
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
            });
        }
        
        // Order by oldest first and paginate
        $editRequests = $query->orderBy('created_at', 'asc')->paginate($perPage);

        return response()->json($editRequests);
    }

    /**
     * Update data record from edit request
     */
    public function updateDataRecord(Request $request, $editRequestId)
    {
        $user = JWTAuth::user();
        
        $editRequest = EditRequest::where('id', $editRequestId)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('dataRecord')
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

        // Update the data record
        $editRequest->dataRecord->update([
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

        // Mark edit request as completed
        $editRequest->update(['status' => 'completed']);

        return response()->json([
            'message' => 'Data record updated successfully',
            'data' => $editRequest->dataRecord
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
