@extends('admin.layout')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                <p class="text-gray-600 mt-1">Manage regular users (Functionality will be added later)</p>
            </div>
        </div>
    </div>

    <!-- Coming Soon Section -->
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <div class="max-w-md mx-auto">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Coming Soon</h2>
            <p class="text-gray-600 mb-6">User management functionality will be implemented here. This will include regular user registration, profile management, and user permissions.</p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-blue-800 text-sm">For now, use the <strong>Admins</strong> section to manage administrative users.</p>
            </div>
        </div>
    </div>
</div>
@endsection 