@extends('admin.layout')

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
                <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    <span id="profileMobile">Loading...</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Administrator</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Member Since</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    <span id="profileCreatedAt">Loading...</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    <span id="profileStatus" class="px-2 py-1 text-xs font-medium rounded-full">Loading...</span>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <p class="text-sm text-gray-500 mb-4">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Profile information is read-only. Contact super administrator to make changes.
            </p>
        </div>
    </div>

    <!-- Admin Authority & Hierarchy -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center mb-6">
            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <h2 class="text-xl font-semibold text-gray-800">Administrative Authority</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Type</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    <span id="adminType" class="px-2 py-1 text-xs font-medium rounded-full">Loading...</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Authority Level</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                    <div class="flex items-center">
                        <div id="authorityLevel" class="h-2 bg-blue-500 rounded-full mr-3" style="width: 0%"></div>
                        <span id="authorityText" class="text-sm">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Area of Authority -->
        <div id="areaSection" class="mt-6 hidden">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Area of Authority</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div id="divisionInfo" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Division</label>
                    <div class="w-full px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg text-blue-800">
                        <span id="divisionName">-</span>
                    </div>
                </div>
                <div id="districtInfo" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                    <div class="w-full px-3 py-2 bg-green-50 border border-green-200 rounded-lg text-green-800">
                        <span id="districtName">-</span>
                    </div>
                </div>
                <div id="upazilaInfo" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upazila</label>
                    <div class="w-full px-3 py-2 bg-purple-50 border border-purple-200 rounded-lg text-purple-800">
                        <span id="upazilaName">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Permissions & Capabilities</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-800 mb-3">Can Manage Admin Types</h4>
                    <div id="managableAdminTypes" class="space-y-2">
                        <p class="text-sm text-gray-600">Loading...</p>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-800 mb-3">System Capabilities</h4>
                    <div id="systemCapabilities" class="space-y-2">
                        <p class="text-sm text-gray-600">Loading...</p>
                    </div>
                </div>
            </div>
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

    <!-- Success/Error Messages -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>
@endsection

@section('scripts')
<script>
    // Load profile data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadAdminProfileData();
    });

    async function loadAdminProfileData() {
        try {
            const token = localStorage.getItem('admin_token');
            
            if (!token) {
                throw new Error('No authentication token found. Please login again.');
            }
            
            const response = await axios.get('/api/admin/me');
            const admin = response.data;
            
            if (!admin) {
                throw new Error('No admin data received from API');
            }
            
            // Basic info
            document.getElementById('profileName').textContent = admin.name || 'N/A';
            document.getElementById('profileEmail').textContent = admin.email || 'N/A';
            document.getElementById('profileMobile').textContent = admin.mobile || 'Not provided';
            
            // Status
            const statusElement = document.getElementById('profileStatus');
            const statusClass = getStatusColor(admin.status || 'active');
            statusElement.className = `px-2 py-1 text-xs font-medium rounded-full ${statusClass}`;
            statusElement.textContent = (admin.status || 'active').charAt(0).toUpperCase() + (admin.status || 'active').slice(1);
            
            // Created date
            if (admin.created_at) {
                const createdDate = new Date(admin.created_at);
                document.getElementById('profileCreatedAt').textContent = createdDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } else {
                document.getElementById('profileCreatedAt').textContent = 'N/A';
            }
            
            // Admin type and authority
            displayAdminAuthority(admin);
            
            // Area information
            displayAreaAuthority(admin);
            
            // Permissions
            displayPermissions(admin);
            
            // Password last updated
            const lastPasswordElement = document.getElementById('lastPasswordChange');
            if (admin.password_updated_at) {
                const passwordDate = new Date(admin.password_updated_at);
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
            console.error('Failed to load admin profile data:', error);
            
            let errorMessage = 'Unknown error occurred';
            
            if (error.response) {
                errorMessage = `API Error (${error.response.status}): ${error.response.data?.message || error.response.statusText}`;
            } else if (error.request) {
                errorMessage = 'Network error: Could not reach the server';
            } else {
                errorMessage = error.message || 'Unknown error occurred';
            }
            
            showMessage('Failed to load profile data: ' + errorMessage, 'error');
            
            // Show error state in UI
            document.getElementById('profileName').textContent = 'Error loading data';
            document.getElementById('profileEmail').textContent = 'Please refresh the page';
            document.getElementById('profileMobile').textContent = 'Contact administrator';
        }
    }

    function displayAdminAuthority(admin) {
        const adminTypeElement = document.getElementById('adminType');
        const adminTypeClass = getAdminTypeColor(admin.admin_type);
        adminTypeElement.className = `px-2 py-1 text-xs font-medium rounded-full ${adminTypeClass}`;
        
        const typeLabels = {
            'superadmin': 'Super Admin',
            'national': 'National',
            'divisional': 'Divisional',
            'district': 'District',
            'upazila': 'Upazila'
        };
        adminTypeElement.textContent = typeLabels[admin.admin_type] || 'Admin';
        
        // Authority level visualization
        const levels = {
            'upazila': { level: 1, width: '20%', text: 'Level 1 - Local Authority' },
            'district': { level: 2, width: '40%', text: 'Level 2 - District Authority' },
            'divisional': { level: 3, width: '60%', text: 'Level 3 - Divisional Authority' },
            'national': { level: 4, width: '80%', text: 'Level 4 - National Authority' },
            'superadmin': { level: 5, width: '100%', text: 'Level 5 - Supreme Authority' }
        };
        
        const authorityInfo = levels[admin.admin_type] || { level: 0, width: '0%', text: 'Unknown Level' };
        document.getElementById('authorityLevel').style.width = authorityInfo.width;
        document.getElementById('authorityText').textContent = authorityInfo.text;
    }

    function displayAreaAuthority(admin) {
        const areaSection = document.getElementById('areaSection');
        
        if (admin.admin_type === 'superadmin' || admin.admin_type === 'national') {
            areaSection.classList.add('hidden');
            return;
        }
        
        areaSection.classList.remove('hidden');
        
        // Division
        if (admin.division_name && (admin.admin_type === 'divisional' || admin.admin_type === 'district' || admin.admin_type === 'upazila')) {
            document.getElementById('divisionInfo').classList.remove('hidden');
            document.getElementById('divisionName').textContent = admin.division_name;
        }
        
        // District
        if (admin.district_name && (admin.admin_type === 'district' || admin.admin_type === 'upazila')) {
            document.getElementById('districtInfo').classList.remove('hidden');
            document.getElementById('districtName').textContent = admin.district_name;
        }
        
        // Upazila
        if (admin.upazila_name && admin.admin_type === 'upazila') {
            document.getElementById('upazilaInfo').classList.remove('hidden');
            document.getElementById('upazilaName').textContent = admin.upazila_name;
        }
    }

    function displayPermissions(admin) {
        // Manageable admin types
        const managableTypes = getAllowedAdminTypes(admin.admin_type);
        const managableElement = document.getElementById('managableAdminTypes');
        
        if (managableTypes.length > 0) {
            managableElement.innerHTML = managableTypes.map(type => {
                const typeLabels = {
                    'superadmin': 'Super Admin',
                    'national': 'National',
                    'divisional': 'Divisional',
                    'district': 'District',
                    'upazila': 'Upazila'
                };
                return `<span class="inline-block px-2 py-1 text-xs font-medium rounded-full ${getAdminTypeColor(type)} mr-2 mb-1">${typeLabels[type]}</span>`;
            }).join('');
        } else {
            managableElement.innerHTML = '<p class="text-sm text-gray-500">No admin management permissions</p>';
        }
        
        // System capabilities
        const capabilities = getSystemCapabilities(admin.admin_type);
        const capabilitiesElement = document.getElementById('systemCapabilities');
        capabilitiesElement.innerHTML = capabilities.map(capability => 
            `<div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                ${capability}
            </div>`
        ).join('');
    }

    function getAllowedAdminTypes(adminType) {
        switch (adminType) {
            case 'superadmin':
                return ['superadmin', 'national', 'divisional', 'district', 'upazila'];
            case 'national':
                return ['divisional', 'district', 'upazila'];
            case 'divisional':
                return ['district', 'upazila'];
            case 'district':
                return ['upazila'];
            case 'upazila':
                return [];
            default:
                return [];
        }
    }

    function getSystemCapabilities(adminType) {
        const baseCapabilities = ['View submissions', 'Manage edit requests', 'Change password'];
        
        switch (adminType) {
            case 'superadmin':
                return [...baseCapabilities, 'Manage all admins', 'System configuration', 'Full data access', 'Library management'];
            case 'national':
                return [...baseCapabilities, 'Manage regional admins', 'National data access', 'Library management'];
            case 'divisional':
                return [...baseCapabilities, 'Manage division admins', 'Divisional data access'];
            case 'district':
                return [...baseCapabilities, 'Manage district admins', 'District data access'];
            case 'upazila':
                return [...baseCapabilities, 'Local area data access'];
            default:
                return baseCapabilities;
        }
    }

    function getAdminTypeColor(type) {
        switch(type) {
            case 'superadmin': return 'bg-red-100 text-red-800';
            case 'national': return 'bg-purple-100 text-purple-800';
            case 'divisional': return 'bg-blue-100 text-blue-800';
            case 'district': return 'bg-green-100 text-green-800';
            case 'upazila': return 'bg-yellow-100 text-yellow-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function getStatusColor(status) {
        switch(status) {
            case 'active': return 'bg-green-100 text-green-800';
            case 'inactive': return 'bg-yellow-100 text-yellow-800';
            case 'suspended': return 'bg-red-100 text-red-800';
            default: return 'bg-green-100 text-green-800';
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
            
            await axios.post('/api/admin/change-password', {
                current_password: data.current_password,
                new_password: data.new_password,
                new_password_confirmation: data.new_password_confirmation
            });
            
            showMessage('Password changed successfully!', 'success');
            closePasswordModal();
            // Reload profile data to update password timestamps
            loadAdminProfileData();
            
        } catch (error) {
            const message = error.message || error.response?.data?.message || 'Failed to change password';
            showMessage(message, 'error');
        } finally {
            changePasswordBtn.textContent = 'Change Password';
            changePasswordBtn.disabled = false;
        }
    });

    // Show message function
    function showMessage(message, type = 'info') {
        const messageContainer = document.getElementById('messageContainer');
        const messageDiv = document.createElement('div');
        
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        
        messageDiv.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
        messageDiv.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        messageContainer.appendChild(messageDiv);
        
        // Animate in
        setTimeout(() => {
            messageDiv.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentElement) {
                messageDiv.classList.add('translate-x-full');
                setTimeout(() => {
                    if (messageDiv.parentElement) {
                        messageDiv.remove();
                    }
                }, 300);
            }
        }, 5000);
    }
</script>
@endsection 