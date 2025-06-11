@extends('admin.layout')

@section('title', 'Pending Edit Requests')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pending Edit Requests</h1>
                <p class="text-gray-600 mt-1">View and manage data submissions that were sent for edit and are still pending</p>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="pendingCount">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Pending Edit Requests Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Pending Edit Requests</h2>
            <button onclick="loadPendingEditRequests()" 
                class="bg-green-100 text-green-800 px-4 py-2 rounded-lg hover:bg-green-200 transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>

        <!-- Search and Filter Controls -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1">
                <input type="text" id="pendingSearchInput" placeholder="Search pending edit requests..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-3">
                <input type="date" id="pendingDateFromInput" placeholder="From date" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <input type="date" id="pendingDateToInput" placeholder="To date" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="relative">
                    <div class="relative">
                        <input type="text" id="pendingUserSearchInput" placeholder="Search users..." 
                            class="pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-48"
                            autocomplete="off">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div id="pendingUserDropdown" class="absolute z-20 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto mt-1">
                    </div>
                </div>
                <select id="pendingPerPageSelect" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>

        <!-- Pending Requests Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Integer Fields</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selector Fields</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent for Edit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="pendingTable" class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="pendingEmptyState" class="hidden text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Pending Edit Requests Found</h3>
            <p class="text-gray-500">No pending edit requests match your search criteria.</p>
        </div>

        <!-- Pagination -->
        <div id="pendingPaginationContainer" class="mt-6">
        </div>
    </div>
</div>

<script>
    // Load pending edit requests functionality
    let pendingCurrentPage = 1;
    let pendingCurrentSearch = '';
    let pendingCurrentDateFrom = '';
    let pendingCurrentDateTo = '';
    let pendingCurrentUser = '';
    let pendingCurrentPerPage = 10;
    let pendingSearchTimeout;

    // User search variables
    let allUsers = [];
    let filteredUsers = [];
    let pendingUserSearchTimeout;

    document.addEventListener('DOMContentLoaded', function() {
        loadPendingEditRequests();
        setupPendingFilters();
        loadUsersForFilter();
    });

    function setupPendingFilters() {
        // Search input
        const searchInput = document.getElementById('pendingSearchInput');
        searchInput?.addEventListener('input', function() {
            clearTimeout(pendingSearchTimeout);
            pendingSearchTimeout = setTimeout(() => {
                pendingCurrentSearch = this.value;
                pendingCurrentPage = 1;
                loadPendingEditRequests();
            }, 500);
        });

        // Date filters
        const dateFromInput = document.getElementById('pendingDateFromInput');
        dateFromInput?.addEventListener('change', function() {
            pendingCurrentDateFrom = this.value;
            pendingCurrentPage = 1;
            loadPendingEditRequests();
        });

        const dateToInput = document.getElementById('pendingDateToInput');
        dateToInput?.addEventListener('change', function() {
            pendingCurrentDateTo = this.value;
            pendingCurrentPage = 1;
            loadPendingEditRequests();
        });

        // User search filter
        const userSearchInput = document.getElementById('pendingUserSearchInput');
        userSearchInput?.addEventListener('input', function() {
            clearTimeout(pendingUserSearchTimeout);
            pendingUserSearchTimeout = setTimeout(() => {
                filterUserDropdown(this.value);
            }, 300);
        });

        userSearchInput?.addEventListener('focus', function() {
            showUserDropdown();
        });

        // Per page filter
        const perPageSelect = document.getElementById('pendingPerPageSelect');
        perPageSelect?.addEventListener('change', function() {
            pendingCurrentPerPage = parseInt(this.value);
            pendingCurrentPage = 1;
            loadPendingEditRequests();
        });
    }

    async function loadUsersForFilter() {
        try {
            const response = await axios.get('/api/admin/users');
            allUsers = response.data.filter(user => user.role === 'user');
            filteredUsers = [...allUsers];
        } catch (error) {
            console.error('Failed to load users for filter:', error);
        }
    }

    async function loadPendingEditRequests(page = 1) {
        try {
            pendingCurrentPage = page;
            const params = new URLSearchParams({
                page: pendingCurrentPage,
                per_page: pendingCurrentPerPage,
                search: pendingCurrentSearch,
                date_from: pendingCurrentDateFrom,
                date_to: pendingCurrentDateTo,
                user: pendingCurrentUser,
                status: 'pending' // Only show pending edit requests
            });

            const response = await axios.get(`/api/admin/edit-requests?${params}`);
            const data = response.data;
            
            displayPendingEditRequests(data.data);
            displayPendingPagination(data);
            updatePendingCount(data.total);
            
        } catch (error) {
            console.error('Failed to load pending edit requests:', error);
        }
    }

    function displayPendingEditRequests(editRequests) {
        const tbody = document.getElementById('pendingTable');
        const emptyState = document.getElementById('pendingEmptyState');
        
        if (editRequests.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        tbody.innerHTML = editRequests.map(request => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${request.id}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-800">${request.user.name.charAt(0).toUpperCase()}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${request.user.name}</div>
                            <div class="text-sm text-gray-500">${request.user.email}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${request.integer_field_1 || 'N/A'}, ${request.integer_field_2 || 'N/A'}, ${request.integer_field_3 || 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${request.selector_field_1 || 'N/A'}, ${request.selector_field_2 || 'N/A'}, ${request.selector_field_3 || 'N/A'}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                    ${request.comments || 'No comments'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Pending Edit
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="text-sm text-gray-900">${new Date(request.created_at).toLocaleDateString()}</div>
                    <div class="text-sm text-gray-500">${new Date(request.created_at).toLocaleTimeString()}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="viewEditRequest(${request.id})" 
                        class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                    <button onclick="approveEditRequest(${request.id})" 
                        class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                    <button onclick="rejectEditRequest(${request.id})" 
                        class="text-red-600 hover:text-red-900">Reject</button>
                </td>
            </tr>
        `).join('');
    }

    function displayPendingPagination(data) {
        const container = document.getElementById('pendingPaginationContainer');
        
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
            paginationHTML += `<button onclick="loadPendingEditRequests(${data.current_page - 1})" 
                class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Previous</button>`;
        }
        
        for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
            if (i === data.current_page) {
                paginationHTML += `<button class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-lg">${i}</button>`;
            } else {
                paginationHTML += `<button onclick="loadPendingEditRequests(${i})" 
                    class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">${i}</button>`;
            }
        }
        
        if (data.current_page < data.last_page) {
            paginationHTML += `<button onclick="loadPendingEditRequests(${data.current_page + 1})" 
                class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Next</button>`;
        }
        
        paginationHTML += `</div></div>`;
        container.innerHTML = paginationHTML;
    }

    function updatePendingCount(total) {
        const countElement = document.getElementById('pendingCount');
        if (countElement) {
            countElement.textContent = `${total} pending edit requests`;
        }
    }

    function filterUserDropdown(searchTerm) {
        if (searchTerm.length === 0) {
            filteredUsers = [...allUsers];
        } else {
            filteredUsers = allUsers.filter(user => 
                user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                user.email.toLowerCase().includes(searchTerm.toLowerCase())
            );
        }
        renderUserDropdown();
    }

    function showUserDropdown() {
        const dropdown = document.getElementById('pendingUserDropdown');
        renderUserDropdown();
        dropdown.classList.remove('hidden');
    }

    function renderUserDropdown() {
        const dropdown = document.getElementById('pendingUserDropdown');
        dropdown.innerHTML = filteredUsers.slice(0, 10).map(user => `
            <div class="px-3 py-2 hover:bg-gray-100 cursor-pointer user-option" data-user-id="${user.id}" data-user-name="${user.name}">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-xs font-medium text-blue-800">${user.name.charAt(0).toUpperCase()}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">${user.name}</div>
                        <div class="text-xs text-gray-500">${user.email}</div>
                    </div>
                </div>
            </div>
        `).join('');

        // Add click handlers
        dropdown.querySelectorAll('.user-option').forEach(option => {
            option.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;
                
                document.getElementById('pendingUserSearchInput').value = userName;
                pendingCurrentUser = userId;
                pendingCurrentPage = 1;
                loadPendingEditRequests();
                dropdown.classList.add('hidden');
            });
        });
    }

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('pendingUserDropdown');
        const input = document.getElementById('pendingUserSearchInput');
        if (!dropdown.contains(e.target) && e.target !== input) {
            dropdown.classList.add('hidden');
        }
    });

    // Action functions
    function viewEditRequest(id) {
        // Implement view functionality
        console.log('View edit request:', id);
    }

    function approveEditRequest(id) {
        if (confirm('Are you sure you want to approve this edit request?')) {
            // Implement approve functionality
            console.log('Approve edit request:', id);
        }
    }

    function rejectEditRequest(id) {
        if (confirm('Are you sure you want to reject this edit request?')) {
            // Implement reject functionality
            console.log('Reject edit request:', id);
        }
    }
</script>

@endsection 