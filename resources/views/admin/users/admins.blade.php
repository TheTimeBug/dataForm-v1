@extends('admin.layout')

@section('title', 'Admin Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Management</h1>
                <p class="text-gray-600 mt-1">Manage admin users by administrative areas</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span id="adminsCount">Loading...</span>
                </div>
                <button onclick="openAddAdminModal()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Admin
                </button>
            </div>
        </div>
    </div>

    <!-- Admins List Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">All Admins</h2>
            <button onclick="loadAdmins()" 
                class="bg-green-100 text-green-800 px-4 py-2 rounded-lg hover:bg-green-200 transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>

        <!-- Filter Controls -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1">
                <input type="text" id="adminsSearchInput" placeholder="Search admins..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-3">
                <select id="adminTypeFilter" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="national">National</option>
                    <option value="divisional">Divisional</option>
                    <option value="district">District</option>
                    <option value="upazila">Upazila</option>
                </select>
                <select id="adminsPerPageSelect" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>

        <!-- Admins Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="adminsTable" class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="adminsEmptyState" class="hidden text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Admins Found</h3>
            <p class="text-gray-500">No admin users match your search criteria.</p>
        </div>

        <!-- Pagination -->
        <div id="adminsPaginationContainer" class="mt-6">
        </div>
    </div>
</div>

<!-- Add New Admin Modal -->
<div id="addAdminModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-4/5 h-4/5 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Add New Admin</h3>
            <button onclick="closeAddAdminModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="addAdminForm" class="space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                    <input type="text" name="name" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mobile *</label>
                    <input type="tel" name="mobile" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Temporary Password *</label>
                    <div class="relative">
                        <input type="password" name="password" id="tempPassword" required
                            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="button" onclick="generatePassword()" 
                            class="absolute right-2 top-2 text-blue-600 hover:text-blue-800 text-sm">
                            Generate
                        </button>
                    </div>
                </div>
            </div>

            <!-- Administrative Level -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Type *</label>
                    <select name="admin_type" id="adminType" required onchange="updateAreaSelectors()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Admin Type</option>
                        <option value="national">National</option>
                        <option value="divisional">Divisional</option>
                        <option value="district">District</option>
                        <option value="upazila">Upazila</option>
                    </select>
                </div>

                <!-- Area Selection (Dynamic based on admin type) -->
                <div id="areaSelectors" class="hidden space-y-4">
                    <!-- Division Selector -->
                    <div id="divisionSelector" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Division *</label>
                        <select name="division_id" id="divisionSelect" onchange="loadDistricts()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Division</option>
                        </select>
                    </div>

                    <!-- District Selector -->
                    <div id="districtSelector" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">District *</label>
                        <select name="district_id" id="districtSelect" onchange="loadUpazilas()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select District</option>
                        </select>
                    </div>

                    <!-- Upazila Selector -->
                    <div id="upazilaSelector" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upazila *</label>
                        <select name="upazila_id" id="upazilaSelect"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Upazila</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <button type="button" onclick="closeAddAdminModal()" 
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                    Cancel
                </button>
                <button type="submit" id="addAdminBtn"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    Add Admin
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Existing Admins Conflict Modal -->
<div id="conflictModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-2xl max-h-96 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Existing Admins Found</h3>
            <button onclick="closeConflictModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-4">
            <p class="text-gray-600 mb-4">There are already admins assigned to this area. Please choose an action:</p>
            
            <!-- Existing Admins List -->
            <div id="existingAdminsList" class="bg-gray-50 rounded-lg p-4 mb-4 max-h-40 overflow-y-auto">
                <!-- Will be populated dynamically -->
            </div>
        </div>
        
        <div class="flex justify-end space-x-4">
            <button type="button" onclick="closeConflictModal()" 
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                Cancel
            </button>
            <button type="button" onclick="replaceExistingAdmins()" 
                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200">
                Replace All
            </button>
            <button type="button" onclick="addAlongWithExisting()"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Add Along With Existing
            </button>
        </div>
    </div>
</div>

<!-- Edit Admin Modal -->
<div id="editAdminModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-4/5 h-4/5 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Edit Admin</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="editAdminForm" class="space-y-6">
            <input type="hidden" id="editAdminId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="editName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="editEmail" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                    <input type="text" id="editMobile" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Type</label>
                    <select id="editAdminType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="handleEditAdminTypeChange()" required>
                        <option value="">Select Admin Type</option>
                        <option value="national">National</option>
                        <option value="divisional">Divisional</option>
                        <option value="district">District</option>
                        <option value="upazila">Upazila</option>
                    </select>
                </div>
                
                <div id="editDivisionGroup" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Division</label>
                    <select id="editDivisionSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="loadEditDistricts()">
                        <option value="">Select Division</option>
                    </select>
                </div>
                
                <div id="editDistrictGroup" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                    <select id="editDistrictSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="loadEditUpazilas()">
                        <option value="">Select District</option>
                    </select>
                </div>
                
                <div id="editUpazilaGroup" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upazila</label>
                    <select id="editUpazilaSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Upazila</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="editStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                    Update Admin
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Suspend/Unsuspend Confirmation Modal -->
<div id="suspendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800" id="suspendModalTitle">Confirm Action</h3>
            <button onclick="closeSuspendModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-6">
            <p class="text-gray-600" id="suspendModalMessage">Are you sure you want to perform this action?</p>
        </div>
        
        <div class="flex justify-end space-x-4">
            <button type="button" onclick="closeSuspendModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancel
            </button>
            <button type="button" onclick="confirmSuspendAction()" id="suspendConfirmBtn" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-lg hover:bg-orange-700">
                Suspend
            </button>
        </div>
    </div>
</div>

<script>
    // Admin management functionality
    let adminsCurrentPage = 1;
    let adminsCurrentSearch = '';
    let adminsCurrentType = '';
    let adminsCurrentPerPage = 10;
    let adminsSearchTimeout;
    let pendingAdminData = null;
    let existingAdmins = [];

    document.addEventListener('DOMContentLoaded', function() {
        loadAdmins();
        setupAdminsFilters();
        setupAddAdminForm();
        loadDivisions();
    });

    function setupAdminsFilters() {
        // Search input
        const searchInput = document.getElementById('adminsSearchInput');
        searchInput?.addEventListener('input', function() {
            clearTimeout(adminsSearchTimeout);
            adminsSearchTimeout = setTimeout(() => {
                adminsCurrentSearch = this.value;
                adminsCurrentPage = 1;
                loadAdmins();
            }, 500);
        });

        // Type filter
        const typeFilter = document.getElementById('adminTypeFilter');
        typeFilter?.addEventListener('change', function() {
            adminsCurrentType = this.value;
            adminsCurrentPage = 1;
            loadAdmins();
        });

        // Per page filter
        const perPageSelect = document.getElementById('adminsPerPageSelect');
        perPageSelect?.addEventListener('change', function() {
            adminsCurrentPerPage = parseInt(this.value);
            adminsCurrentPage = 1;
            loadAdmins();
        });
    }

    function setupAddAdminForm() {
        const form = document.getElementById('addAdminForm');
        form?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Check for existing admins in the selected area
            await checkForExistingAdmins(data);
        });
    }

    // Modal Functions
    function openAddAdminModal() {
        document.getElementById('addAdminModal').classList.remove('hidden');
        document.getElementById('addAdminModal').classList.add('flex');
        // Reset form
        document.getElementById('addAdminForm').reset();
        updateAreaSelectors();
    }

    function closeAddAdminModal() {
        document.getElementById('addAdminModal').classList.add('hidden');
        document.getElementById('addAdminModal').classList.remove('flex');
        // Reset form
        document.getElementById('addAdminForm').reset();
        updateAreaSelectors();
    }

    async function checkForExistingAdmins(adminData) {
        try {
            const response = await axios.post('/api/admin/check-existing-admins', adminData);
            
            if (response.data.exists) {
                // Show conflict modal
                existingAdmins = response.data.admins;
                pendingAdminData = adminData;
                showConflictModal(response.data.admins);
            } else {
                // No conflicts, proceed with creation
                await createAdmin(adminData);
            }
            
        } catch (error) {
            console.error('Error checking existing admins:', error);
            showMessage('Error checking existing admins', 'error');
        }
    }

    function showConflictModal(admins) {
        const modal = document.getElementById('conflictModal');
        const adminsList = document.getElementById('existingAdminsList');
        
        adminsList.innerHTML = admins.map(admin => `
            <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-xs font-medium text-blue-800">${admin.name.charAt(0).toUpperCase()}</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${admin.name}</p>
                        <p class="text-xs text-gray-500">${admin.email} • ${admin.mobile}</p>
                    </div>
                </div>
                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">${admin.admin_type}</span>
            </div>
        `).join('');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeConflictModal() {
        document.getElementById('conflictModal').classList.add('hidden');
        document.getElementById('conflictModal').classList.remove('flex');
        pendingAdminData = null;
        existingAdmins = [];
    }

    async function replaceExistingAdmins() {
        if (pendingAdminData) {
            pendingAdminData.replace_existing = true;
            await createAdmin(pendingAdminData);
            closeConflictModal();
        }
    }

    async function addAlongWithExisting() {
        if (pendingAdminData) {
            pendingAdminData.replace_existing = false;
            await createAdmin(pendingAdminData);
            closeConflictModal();
        }
    }

    async function createAdmin(adminData) {
        const addAdminBtn = document.getElementById('addAdminBtn');
        addAdminBtn.textContent = 'Adding...';
        addAdminBtn.disabled = true;
        
        try {
            await axios.post('/api/admin/create-admin-user', adminData);
            
            showMessage('Admin created successfully!', 'success');
            closeAddAdminModal();
            loadAdmins();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to create admin';
            showMessage(message, 'error');
        } finally {
            addAdminBtn.textContent = 'Add Admin';
            addAdminBtn.disabled = false;
        }
    }

    function updateAreaSelectors() {
        const adminType = document.getElementById('adminType').value;
        const areaSelectors = document.getElementById('areaSelectors');
        const divisionSelector = document.getElementById('divisionSelector');
        const districtSelector = document.getElementById('districtSelector');
        const upazilaSelector = document.getElementById('upazilaSelector');
        
        // Hide all selectors first
        areaSelectors.classList.add('hidden');
        divisionSelector.classList.add('hidden');
        districtSelector.classList.add('hidden');
        upazilaSelector.classList.add('hidden');
        
        // Reset form requirements
        document.getElementById('divisionSelect').required = false;
        document.getElementById('districtSelect').required = false;
        document.getElementById('upazilaSelect').required = false;
        
        if (adminType === 'national') {
            // No area selection needed for national admin
            return;
        }
        
        areaSelectors.classList.remove('hidden');
        
        if (adminType === 'divisional') {
            divisionSelector.classList.remove('hidden');
            document.getElementById('divisionSelect').required = true;
        } else if (adminType === 'district') {
            divisionSelector.classList.remove('hidden');
            districtSelector.classList.remove('hidden');
            document.getElementById('divisionSelect').required = true;
            document.getElementById('districtSelect').required = true;
        } else if (adminType === 'upazila') {
            divisionSelector.classList.remove('hidden');
            districtSelector.classList.remove('hidden');
            upazilaSelector.classList.remove('hidden');
            document.getElementById('divisionSelect').required = true;
            document.getElementById('districtSelect').required = true;
            document.getElementById('upazilaSelect').required = true;
        }
    }

    async function loadDivisions() {
        try {
            const response = await axios.get('/api/admin/library/divisions');
            const divisions = response.data;
            
            const divisionSelect = document.getElementById('divisionSelect');
            divisionSelect.innerHTML = '<option value="">Select Division</option>';
            
            divisions.forEach(division => {
                divisionSelect.innerHTML += `<option value="${division.id}">${division.name}</option>`;
            });
            
        } catch (error) {
            console.error('Failed to load divisions:', error);
        }
    }

    async function loadDistricts() {
        const divisionId = document.getElementById('divisionSelect').value;
        if (!divisionId) return;
        
        try {
            const response = await axios.get(`/api/admin/library/districts/${divisionId}`);
            const districts = response.data;
            
            const districtSelect = document.getElementById('districtSelect');
            districtSelect.innerHTML = '<option value="">Select District</option>';
            
            districts.forEach(district => {
                districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
            });
            
            // Reset upazila selector
            document.getElementById('upazilaSelect').innerHTML = '<option value="">Select Upazila</option>';
            
        } catch (error) {
            console.error('Failed to load districts:', error);
        }
    }

    async function loadUpazilas() {
        const districtId = document.getElementById('districtSelect').value;
        if (!districtId) return;
        
        try {
            const response = await axios.get(`/api/admin/library/upazilas/${districtId}`);
            const upazilas = response.data;
            
            const upazilaSelect = document.getElementById('upazilaSelect');
            upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
            
            upazilas.forEach(upazila => {
                upazilaSelect.innerHTML += `<option value="${upazila.id}">${upazila.name}</option>`;
            });
            
        } catch (error) {
            console.error('Failed to load upazilas:', error);
        }
    }

    function generatePassword() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let password = '';
        for (let i = 0; i < 8; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('tempPassword').value = password;
    }

    async function loadAdmins(page = 1) {
        try {
            adminsCurrentPage = page;
            const params = new URLSearchParams({
                page: adminsCurrentPage,
                per_page: adminsCurrentPerPage,
                search: adminsCurrentSearch,
                admin_type: adminsCurrentType
            });

            const response = await axios.get(`/api/admin/admin-users?${params}`);
            const data = response.data;
            
            displayAdmins(data.data);
            displayAdminsPagination(data);
            updateAdminsCount(data.total);
            
        } catch (error) {
            console.error('Failed to load admins:', error);
        }
    }

    function displayAdmins(admins) {
        const tbody = document.getElementById('adminsTable');
        const emptyState = document.getElementById('adminsEmptyState');
        
        if (admins.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        tbody.innerHTML = admins.map(admin => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-purple-800">${admin.name.charAt(0).toUpperCase()}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${admin.name}</div>
                            <div class="text-sm text-gray-500">ID: ${admin.id}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${admin.email}</div>
                    <div class="text-sm text-gray-500">${admin.mobile || 'N/A'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${getAdminTypeColor(admin.admin_type)}">
                        ${admin.admin_type ? admin.admin_type.charAt(0).toUpperCase() + admin.admin_type.slice(1) : 'Admin'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${getAreaInfo(admin)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${getStatusColor(admin.status)}">
                        ${admin.status ? admin.status.charAt(0).toUpperCase() + admin.status.slice(1) : 'Active'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="text-sm text-gray-900">${new Date(admin.created_at).toLocaleDateString()}</div>
                    <div class="text-sm text-gray-500">${new Date(admin.created_at).toLocaleTimeString()}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button onclick="editAdmin(${JSON.stringify(admin).replace(/"/g, '&quot;')})" 
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition duration-200">
                        Edit
                    </button>
                    <button onclick="toggleSuspend(${admin.id}, '${admin.name}', '${admin.status || 'active'}')" 
                        class="${admin.status === 'suspended' ? 'bg-green-600 hover:bg-green-700' : 'bg-orange-600 hover:bg-orange-700'} text-white px-3 py-1 rounded transition duration-200">
                        ${admin.status === 'suspended' ? 'Unsuspend' : 'Suspend'}
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function getAdminTypeColor(type) {
        switch(type) {
            case 'national': return 'bg-red-100 text-red-800';
            case 'divisional': return 'bg-purple-100 text-purple-800';
            case 'district': return 'bg-blue-100 text-blue-800';
            case 'upazila': return 'bg-green-100 text-green-800';
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

    function getAreaInfo(admin) {
        if (admin.admin_type === 'national') return 'National Level';
        
        let area = '';
        if (admin.division_name) area += admin.division_name;
        if (admin.district_name) area += (area ? ' → ' : '') + admin.district_name;
        if (admin.upazila_name) area += (area ? ' → ' : '') + admin.upazila_name;
        
        return area || 'N/A';
    }

    function displayAdminsPagination(data) {
        const container = document.getElementById('adminsPaginationContainer');
        
        if (data.last_page <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let paginationHTML = `
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <div class="text-sm text-gray-700 mb-3 sm:mb-0">
                    Showing ${((data.current_page - 1) * data.per_page) + 1} to ${Math.min(data.current_page * data.per_page, data.total)} of ${data.total} results
                </div>
                <div class="flex items-center space-x-2">
        `;
        
        if (data.current_page > 1) {
            paginationHTML += `<button onclick="loadAdmins(${data.current_page - 1})" 
                class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Previous</button>`;
        }
        
        for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
            if (i === data.current_page) {
                paginationHTML += `<button class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-lg">${i}</button>`;
            } else {
                paginationHTML += `<button onclick="loadAdmins(${i})" 
                    class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">${i}</button>`;
            }
        }
        
        if (data.current_page < data.last_page) {
            paginationHTML += `<button onclick="loadAdmins(${data.current_page + 1})" 
                class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Next</button>`;
        }
        
        paginationHTML += `</div></div>`;
        container.innerHTML = paginationHTML;
    }

    function updateAdminsCount(total) {
        const countElement = document.getElementById('adminsCount');
        if (countElement) {
            countElement.textContent = `${total} total admins`;
        }
    }

    // Edit Admin Functions
    async function editAdmin(adminData) {
        const admin = typeof adminData === 'string' ? JSON.parse(adminData.replace(/&quot;/g, '"')) : adminData;
        
        // Populate basic form fields first
        document.getElementById('editAdminId').value = admin.id;
        document.getElementById('editName').value = admin.name;
        document.getElementById('editEmail').value = admin.email;
        document.getElementById('editMobile').value = admin.mobile || '';
        document.getElementById('editAdminType').value = admin.admin_type || '';
        document.getElementById('editStatus').value = admin.status || 'active';
        
        // Show modal first
        document.getElementById('editAdminModal').classList.remove('hidden');
        document.getElementById('editAdminModal').classList.add('flex');
        
        // Load area data and set selections in proper sequence
        await loadEditAreaData(admin);
        
        // Show appropriate area selectors based on admin type
        handleEditAdminTypeChange();
    }

    async function loadEditAreaData(admin) {
        try {
            console.log('Loading area data for admin:', admin);
            
            // Always load divisions first
            await loadEditDivisions();
            
            // Set division selection if exists and load dependent data
            if (admin.division_id) {
                console.log('Setting division to:', admin.division_id);
                document.getElementById('editDivisionSelect').value = admin.division_id;
                
                // Load and set districts if needed
                if (admin.admin_type === 'district' || admin.admin_type === 'upazila') {
                    await loadEditDistricts();
                    if (admin.district_id) {
                        console.log('Setting district to:', admin.district_id);
                        document.getElementById('editDistrictSelect').value = admin.district_id;
                        
                        // Load and set upazilas if needed
                        if (admin.admin_type === 'upazila') {
                            await loadEditUpazilas();
                            if (admin.upazila_id) {
                                console.log('Setting upazila to:', admin.upazila_id);
                                document.getElementById('editUpazilaSelect').value = admin.upazila_id;
                            }
                        }
                    }
                }
            }
            console.log('Area data loading completed');
        } catch (error) {
            console.error('Error loading edit area data:', error);
        }
    }

    function closeEditModal() {
        document.getElementById('editAdminModal').classList.add('hidden');
        document.getElementById('editAdminModal').classList.remove('flex');
        document.getElementById('editAdminForm').reset();
    }

    async function handleEditAdminTypeChange() {
        const adminType = document.getElementById('editAdminType').value;
        const divisionGroup = document.getElementById('editDivisionGroup');
        const districtGroup = document.getElementById('editDistrictGroup');
        const upazilaGroup = document.getElementById('editUpazilaGroup');
        
        // Hide all groups first
        divisionGroup.classList.add('hidden');
        districtGroup.classList.add('hidden');
        upazilaGroup.classList.add('hidden');
        
        // Reset requirements
        document.getElementById('editDivisionSelect').required = false;
        document.getElementById('editDistrictSelect').required = false;
        document.getElementById('editUpazilaSelect').required = false;
        
        if (adminType === 'national') {
            return;
        }
        
        if (adminType === 'divisional') {
            divisionGroup.classList.remove('hidden');
            document.getElementById('editDivisionSelect').required = true;
        } else if (adminType === 'district') {
            divisionGroup.classList.remove('hidden');
            districtGroup.classList.remove('hidden');
            document.getElementById('editDivisionSelect').required = true;
            document.getElementById('editDistrictSelect').required = true;
        } else if (adminType === 'upazila') {
            divisionGroup.classList.remove('hidden');
            districtGroup.classList.remove('hidden');
            upazilaGroup.classList.remove('hidden');
            document.getElementById('editDivisionSelect').required = true;
            document.getElementById('editDistrictSelect').required = true;
            document.getElementById('editUpazilaSelect').required = true;
        }
    }

    async function loadEditDivisions() {
        try {
            console.log('Loading divisions for edit form...');
            const response = await axios.get('/api/admin/library/divisions');
            const divisions = response.data;
            
            const divisionSelect = document.getElementById('editDivisionSelect');
            divisionSelect.innerHTML = '<option value="">Select Division</option>';
            
            divisions.forEach(division => {
                divisionSelect.innerHTML += `<option value="${division.id}">${division.name}</option>`;
            });
            
            console.log('Loaded divisions:', divisions.length);
            
        } catch (error) {
            console.error('Failed to load divisions:', error);
        }
    }

    async function loadEditDistricts() {
        const divisionId = document.getElementById('editDivisionSelect').value;
        if (!divisionId) {
            console.log('No division ID provided for loading districts');
            return;
        }
        
        try {
            console.log('Loading districts for division:', divisionId);
            const response = await axios.get(`/api/admin/library/districts/${divisionId}`);
            const districts = response.data;
            
            const districtSelect = document.getElementById('editDistrictSelect');
            districtSelect.innerHTML = '<option value="">Select District</option>';
            
            districts.forEach(district => {
                districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
            });
            
            console.log('Loaded districts:', districts.length);
            
            // Clear upazila selection
            document.getElementById('editUpazilaSelect').innerHTML = '<option value="">Select Upazila</option>';
            
        } catch (error) {
            console.error('Failed to load districts:', error);
        }
    }

    async function loadEditUpazilas() {
        const districtId = document.getElementById('editDistrictSelect').value;
        if (!districtId) {
            console.log('No district ID provided for loading upazilas');
            return;
        }
        
        try {
            console.log('Loading upazilas for district:', districtId);
            const response = await axios.get(`/api/admin/library/upazilas/${districtId}`);
            const upazilas = response.data;
            
            const upazilaSelect = document.getElementById('editUpazilaSelect');
            upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
            
            upazilas.forEach(upazila => {
                upazilaSelect.innerHTML += `<option value="${upazila.id}">${upazila.name}</option>`;
            });
            
            console.log('Loaded upazilas:', upazilas.length);
            
        } catch (error) {
            console.error('Failed to load upazilas:', error);
        }
    }

    // Setup edit form submission
    document.addEventListener('DOMContentLoaded', function() {
        const editForm = document.getElementById('editAdminForm');
        editForm?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const adminId = document.getElementById('editAdminId').value;
            const formData = new FormData(this);
            const data = {};
            
            // Collect form data
            data.name = document.getElementById('editName').value;
            data.email = document.getElementById('editEmail').value;
            data.mobile = document.getElementById('editMobile').value;
            data.admin_type = document.getElementById('editAdminType').value;
            data.status = document.getElementById('editStatus').value;
            
            if (data.admin_type !== 'national') {
                data.division_id = document.getElementById('editDivisionSelect').value;
                if (data.admin_type === 'district' || data.admin_type === 'upazila') {
                    data.district_id = document.getElementById('editDistrictSelect').value;
                }
                if (data.admin_type === 'upazila') {
                    data.upazila_id = document.getElementById('editUpazilaSelect').value;
                }
            }
            
            await updateAdmin(adminId, data);
        });
    });

    async function updateAdmin(adminId, data) {
        const submitBtn = document.querySelector('#editAdminForm button[type="submit"]');
        submitBtn.textContent = 'Updating...';
        submitBtn.disabled = true;
        
        try {
            await axios.put(`/api/admin/admin-users/${adminId}`, data);
            
            showMessage('Admin updated successfully!', 'success');
            closeEditModal();
            loadAdmins();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to update admin';
            showMessage(message, 'error');
        } finally {
            submitBtn.textContent = 'Update Admin';
            submitBtn.disabled = false;
        }
    }

    // Suspend/Unsuspend Admin Variables
    let pendingSuspendData = null;

    // Suspend/Unsuspend Admin
    function toggleSuspend(adminId, adminName, currentStatus) {
        const action = currentStatus === 'suspended' ? 'unsuspend' : 'suspend';
        const actionCapitalized = action.charAt(0).toUpperCase() + action.slice(1);
        
        // Store pending data
        pendingSuspendData = {
            adminId: adminId,
            adminName: adminName,
            currentStatus: currentStatus,
            action: action,
            newStatus: currentStatus === 'suspended' ? 'active' : 'suspended'
        };
        
        // Update modal content
        document.getElementById('suspendModalTitle').textContent = `${actionCapitalized} Admin`;
        document.getElementById('suspendModalMessage').textContent = `Are you sure you want to ${action} ${adminName}?`;
        
        const confirmBtn = document.getElementById('suspendConfirmBtn');
        confirmBtn.textContent = actionCapitalized;
        confirmBtn.className = `px-4 py-2 text-sm font-medium text-white border border-transparent rounded-lg ${
            action === 'suspend' 
                ? 'bg-orange-600 hover:bg-orange-700' 
                : 'bg-green-600 hover:bg-green-700'
        }`;
        
        // Show modal
        document.getElementById('suspendModal').classList.remove('hidden');
        document.getElementById('suspendModal').classList.add('flex');
    }

    function closeSuspendModal() {
        document.getElementById('suspendModal').classList.add('hidden');
        document.getElementById('suspendModal').classList.remove('flex');
        pendingSuspendData = null;
    }

    async function confirmSuspendAction() {
        if (!pendingSuspendData) {
            console.error('No pending suspend data found');
            return;
        }
        
        console.log('Confirming suspend action:', pendingSuspendData);
        
        const confirmBtn = document.getElementById('suspendConfirmBtn');
        const originalText = confirmBtn.textContent;
        confirmBtn.textContent = 'Processing...';
        confirmBtn.disabled = true;
        
        try {
            const response = await axios.put(`/api/admin/admin-users/${pendingSuspendData.adminId}`, { 
                status: pendingSuspendData.newStatus 
            });
            
            console.log('Suspend action response:', response.data);
            showMessage(`Admin ${pendingSuspendData.action}ed successfully!`, 'success');
            closeSuspendModal();
            loadAdmins();
            
        } catch (error) {
            console.error('Suspend/Unsuspend error:', error);
            console.error('Error response:', error.response?.data);
            const message = error.response?.data?.message || error.response?.data?.error || `Failed to ${pendingSuspendData.action} admin`;
            showMessage(message, 'error');
        } finally {
            confirmBtn.textContent = originalText;
            confirmBtn.disabled = false;
        }
    }

    function showMessage(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        
        toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full opacity-0`;
        toast.innerHTML = `
            <div class="flex items-center">
                <span class="mr-2">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }, 5000);
    }
</script>

@endsection