@extends('user.layout')

@section('title', 'Profile')

@section('content')
    <!-- Profile Information -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Profile Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    <span id="profileName">Loading...</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    <span id="profileEmail">Loading...</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    <span id="profileRole">User</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Member Since</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    <span id="profileCreatedAt">Loading...</span>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <p class="text-sm text-gray-500 mb-4">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Profile information is read-only. Contact administrator to make changes.
            </p>
        </div>
    </div>

    <!-- Password Change Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Security Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Manage your account security</p>
            </div>
            <button onclick="openPasswordModal()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-3.586l5.293-5.293A6 6 0 0119 9z"></path>
                </svg>
                Change Password
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-medium text-gray-800 mb-2">Password Security</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Use a strong, unique password
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Include numbers and special characters
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Minimum 8 characters long
                    </li>
                </ul>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-medium text-gray-800 mb-2">Account Activity</h3>
                <p class="text-sm text-gray-600">
                    Last password change: <span class="font-medium" id="lastPasswordChange">Loading...</span>
                </p>
                <p class="text-sm text-gray-600 mt-2">
                    We recommend changing your password regularly to keep your account secure.
                </p>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Change Password</h3>
                <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="passwordChangeForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter current password">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" name="new_password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter new password">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Confirm new password">
                </div>
                
                <div class="flex justify-end space-x-4 pt-4">
                    <button type="button" onclick="closePasswordModal()" 
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit" id="changePasswordBtn"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Load profile data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadProfileData();
    });

    async function loadProfileData() {
        try {
            const response = await axios.get('/api/me');
            const user = response.data;
            
            document.getElementById('profileName').textContent = user.name;
            document.getElementById('profileEmail').textContent = user.email;
            document.getElementById('profileRole').textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);
            
            if (user.created_at) {
                const createdDate = new Date(user.created_at);
                document.getElementById('profileCreatedAt').textContent = createdDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
            
            // Update password last updated
            const lastPasswordElement = document.getElementById('lastPasswordChange');
            
            if (user.password_updated_at) {
                const passwordDate = new Date(user.password_updated_at);
                const formattedDate = passwordDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit'
                });
                lastPasswordElement.textContent = formattedDate;
            } else {
                lastPasswordElement.textContent = 'Never changed';
            }
            
        } catch (error) {
            console.error('Failed to load profile data:', error);
            showMessage('Failed to load profile data', 'error');
        }
    }

    function openPasswordModal() {
        document.getElementById('passwordModal').classList.remove('hidden');
        document.getElementById('passwordModal').classList.add('flex');
        document.getElementById('passwordChangeForm').reset();
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.add('hidden');
        document.getElementById('passwordModal').classList.remove('flex');
    }

    // Handle password change form submission
    document.getElementById('passwordChangeForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const changePasswordBtn = document.getElementById('changePasswordBtn');
        changePasswordBtn.textContent = 'Changing...';
        changePasswordBtn.disabled = true;
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Validate that new password fields match
            if (data.new_password !== data.new_password_confirmation) {
                throw new Error('New password fields must match');
            }
            
            // Validate password strength
            if (data.new_password.length < 8) {
                throw new Error('New password must be at least 8 characters long');
            }
            
            await axios.post('/api/user/change-password', {
                current_password: data.current_password,
                new_password: data.new_password,
                new_password_confirmation: data.new_password_confirmation
            });
            
            showMessage('Password changed successfully!', 'success');
            closePasswordModal();
            // Reload profile data to update password timestamps
            loadProfileData();
            
        } catch (error) {
            const message = error.message || error.response?.data?.message || 'Failed to change password';
            showMessage(message, 'error');
        } finally {
            changePasswordBtn.textContent = 'Change Password';
            changePasswordBtn.disabled = false;
        }
    });
</script>
@endsection 