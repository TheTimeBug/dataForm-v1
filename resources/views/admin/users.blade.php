@extends('admin.layout')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                <p class="text-gray-600 mt-1">Manage user accounts and permissions</p>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span id="usersCount">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Add New User Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Add New User</h2>
            <button onclick="toggleAddUserForm()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add User
            </button>
        </div>

        <!-- Add User Form -->
        <div id="addUserFormContainer" class="hidden">
            <form id="addUserForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                    <input type="text" name="name" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                    <select name="role" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Role</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex justify-end space-x-4">
                    <button type="button" onclick="cancelAddUser()" 
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit" id="addUserBtn"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users List Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">All Users</h2>
            <button onclick="loadUsers()" 
                class="bg-green-100 text-green-800 px-4 py-2 rounded-lg hover:bg-green-200 transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>

        <!-- Search Controls -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1">
                <input type="text" id="usersSearchInput" placeholder="Search users..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-3">
                <select id="roleFilterSelect" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Roles</option>
                    <option value="user">Users</option>
                    <option value="admin">Admins</option>
                </select>
                <select id="usersPerPageSelect" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTable" class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="usersEmptyState" class="hidden text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Users Found</h3>
            <p class="text-gray-500">No users match your search criteria.</p>
        </div>

        <!-- Pagination -->
        <div id="usersPaginationContainer" class="mt-6">
        </div>
    </div>
</div>

<script>
    // Users management functionality
    let usersCurrentPage = 1;
    let usersCurrentSearch = '';
    let usersCurrentRole = '';
    let usersCurrentPerPage = 10;
    let usersSearchTimeout;

    document.addEventListener('DOMContentLoaded', function() {
        loadUsers();
        setupUsersFilters();
        setupAddUserForm();
    });

    function setupUsersFilters() {
        // Search input
        const searchInput = document.getElementById('usersSearchInput');
        searchInput?.addEventListener('input', function() {
            clearTimeout(usersSearchTimeout);
            usersSearchTimeout = setTimeout(() => {
                usersCurrentSearch = this.value;
                usersCurrentPage = 1;
                loadUsers();
            }, 500);
        });

        // Role filter
        const roleFilter = document.getElementById('roleFilterSelect');
        roleFilter?.addEventListener('change', function() {
            usersCurrentRole = this.value;
            usersCurrentPage = 1;
            loadUsers();
        });

        // Per page filter
        const perPageSelect = document.getElementById('usersPerPageSelect');
        perPageSelect?.addEventListener('change', function() {
            usersCurrentPerPage = parseInt(this.value);
            usersCurrentPage = 1;
            loadUsers();
        });
    }

    function setupAddUserForm() {
        const form = document.getElementById('addUserForm');
        form?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const addUserBtn = document.getElementById('addUserBtn');
            addUserBtn.textContent = 'Adding...';
            addUserBtn.disabled = true;
            
            try {
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                
                await axios.post('/api/admin/users', data);
                
                showMessage('User added successfully!', 'success');
                this.reset();
                toggleAddUserForm();
                loadUsers();
                
            } catch (error) {
                const message = error.response?.data?.message || 'Failed to add user';
                showMessage(message, 'error');
            } finally {
                addUserBtn.textContent = 'Add User';
                addUserBtn.disabled = false;
            }
        });
    }

    async function loadUsers(page = 1) {
        try {
            usersCurrentPage = page;
            const params = new URLSearchParams({
                page: usersCurrentPage,
                per_page: usersCurrentPerPage,
                search: usersCurrentSearch,
                role: usersCurrentRole
            });

            const response = await axios.get(`/api/admin/users?${params}`);
            const data = response.data;
            
            displayUsers(data.data);
            displayUsersPagination(data);
            updateUsersCount(data.total);
            
        } catch (error) {
            console.error('Failed to load users:', error);
        }
    }

    function displayUsers(users) {
        const tbody = document.getElementById('usersTable');
        const emptyState = document.getElementById('usersEmptyState');
        
        if (users.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        tbody.innerHTML = users.map(user => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-800">${user.name.charAt(0).toUpperCase()}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${user.name}</div>
                            <div class="text-sm text-gray-500">ID: ${user.id}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.email}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${
                        user.role === 'admin' 
                        ? 'bg-purple-100 text-purple-800' 
                        : 'bg-green-100 text-green-800'
                    }">
                        ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        Active
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="text-sm text-gray-900">${new Date(user.created_at).toLocaleDateString()}</div>
                    <div class="text-sm text-gray-500">${new Date(user.created_at).toLocaleTimeString()}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button onclick="editUser(${user.id})" 
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition duration-200">
                        Edit
                    </button>
                    ${user.role !== 'admin' ? `
                        <button onclick="deleteUser(${user.id})" 
                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition duration-200">
                            Delete
                        </button>
                    ` : ''}
                </td>
            </tr>
        `).join('');
    }

    function displayUsersPagination(data) {
        const container = document.getElementById('usersPaginationContainer');
        
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
            paginationHTML += `<button onclick="loadUsers(${data.current_page - 1})" 
                class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Previous</button>`;
        }
        
        for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
            if (i === data.current_page) {
                paginationHTML += `<button class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-lg">${i}</button>`;
            } else {
                paginationHTML += `<button onclick="loadUsers(${i})" 
                    class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">${i}</button>`;
            }
        }
        
        if (data.current_page < data.last_page) {
            paginationHTML += `<button onclick="loadUsers(${data.current_page + 1})" 
                class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Next</button>`;
        }
        
        paginationHTML += `</div></div>`;
        container.innerHTML = paginationHTML;
    }

    function updateUsersCount(total) {
        const countElement = document.getElementById('usersCount');
        if (countElement) {
            countElement.textContent = `${total} total users`;
        }
    }

    function toggleAddUserForm() {
        const container = document.getElementById('addUserFormContainer');
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function cancelAddUser() {
        document.getElementById('addUserFormContainer').classList.add('hidden');
        document.getElementById('addUserForm').reset();
    }

    function editUser(userId) {
        // Simple implementation - you can enhance this with a modal
        console.log('Edit user', userId);
        showMessage('Edit functionality not implemented yet', 'info');
    }

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            // Add your delete logic here
            console.log('Delete user', userId);
            showMessage('Delete functionality not implemented yet', 'info');
        }
    }

    // Message system (add this if not already present in layout)
    function showMessage(message, type) {
        // Simple alert for now - you can enhance this with better UI
        alert(message);
    }
</script>

@endsection 