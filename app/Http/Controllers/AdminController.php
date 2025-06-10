<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DataRecord;
use App\Models\EditRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
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
     * Send data for edit
     */
    public function sendForEdit(Request $request)
    {
        $admin = JWTAuth::user();
        
        if ($admin->role !== 'admin') {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $request->validate([
            'data_record_id' => 'required|exists:data_records,id',
            'admin_notes' => 'nullable|string',
        ]);

        $dataRecord = DataRecord::find($request->data_record_id);

        $editRequest = EditRequest::create([
            'data_record_id' => $request->data_record_id,
            'user_id' => $dataRecord->user_id,
            'admin_id' => $admin->id,
            'status' => 'pending',
            'admin_notes' => $request->admin_notes,
        ]);

        return response()->json([
            'message' => 'Edit request sent successfully',
            'edit_request' => $editRequest->load(['dataRecord', 'user'])
        ], 201);
    }

    /**
     * Get all edit requests
     */
    public function getEditRequests()
    {
        $admin = JWTAuth::user();
        
        if ($admin->role !== 'admin') {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $editRequests = EditRequest::with(['dataRecord', 'user', 'admin'])->get();

        return response()->json($editRequests);
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
}
