@extends('admin.layout')

@section('title', 'Edit History')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit History</h1>
                <p class="text-gray-600 mt-1">View and manage data submissions that were sent for edit and have been completed</p>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span id="historyCount">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Edit History Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Completed Edit Requests</h2>
            <button onclick="loadEditHistory()" 
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
                <input type="text" id="historySearchInput" placeholder="Search edit history..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-3">
                <input type="date" id="historyDateFromInput" placeholder="From date" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <input type="date" id="historyDateToInput" placeholder="To date" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <select id="historyStatusFilter" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>
                <div class="relative">
                    <div class="relative">
                        <input type="text" id="historyUserSearchInput" placeholder="Search users..." 
                            class="pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-48"
                            autocomplete="off">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div id="historyUserDropdown" class="absolute z-20 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto mt-1">
                    </div>
                </div>
                <select id="historyPerPageSelect" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>

        <!-- History Table -->
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="historyTable" class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="historyEmptyState" class="hidden text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Edit History Found</h3>
            <p class="text-gray-500">No completed edit requests match your search criteria.</p>
        </div>

        <!-- Pagination -->
        <div id="historyPaginationContainer" class="mt-6">
        </div>
    </div>
</div>

<script>
    // Load edit history functionality
    let historyCurrentPage = 1;
    let historyCurrentSearch = '';
    let historyCurrentDateFrom = '';
    let historyCurrentDateTo = '';
    let historyCurrentUser = '';
    let historyCurrentStatus = '';
    let historyCurrentPerPage = 10;
    let historySearchTimeout;

    // User search variables
    let allUsers = [];
    let filteredUsers = [];
    let historyUserSearchTimeout;

    document.addEventListener('DOMContentLoaded', function() {
        loadEditHistory();
        setupHistoryFilters();
        loadUsersForFilter();
    });

    function setupHistoryFilters() {
        // Search input
        const searchInput = document.getElementById('historySearchInput');
        searchInput?.addEventListener('input', function() {
            clearTimeout(historySearchTimeout);
            historySearchTimeout = setTimeout(() => {
                historyCurrentSearch = this.value;
                historyCurrentPage = 1;
                loadEditHistory();
            }, 500);
        });

        // Date filters
        const dateFromInput = document.getElementById('historyDateFromInput');
        dateFromInput?.addEventListener('change', function() {
            historyCurrentDateFrom = this.value;
            historyCurrentPage = 1;
            loadEditHistory();
        });

        const dateToInput = document.getElementById('historyDateToInput');
        dateToInput?.addEventListener('change', function() {
            historyCurrentDateTo = this.value;
            historyCurrentPage = 1;
            loadEditHistory();
        });

        // Status filter
        const statusFilter = document.getElementById('historyStatusFilter');
        statusFilter?.addEventListener('change', function() {
            historyCurrentStatus = this.value;
            historyCurrentPage = 1;
            loadEditHistory();
        });

        // User search filter
        const userSearchInput = document.getElementById('historyUserSearchInput');
        userSearchInput?.addEventListener('input', function() {
            clearTimeout(historyUserSearchTimeout);
            historyUserSearchTimeout = setTimeout(() => {
                filterUserDropdown(this.value);
            }, 300);
        });

        userSearchInput?.addEventListener('focus', function() {
            showUserDropdown();
        });

        // Per page filter
        const perPageSelect = document.getElementById('historyPerPageSelect');
        perPageSelect?.addEventListener('change', function() {
            historyCurrentPerPage = parseInt(this.value);
            historyCurrentPage = 1;
            loadEditHistory();
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

    async function loadEditHistory(page = 1) {
        try {
            historyCurrentPage = page;
            const params = new URLSearchParams({
                page: historyCurrentPage,
                per_page: historyCurrentPerPage,
                search: historyCurrentSearch,
                date_from: historyCurrentDateFrom,
                date_to: historyCurrentDateTo,
                user: historyCurrentUser,
                status: historyCurrentStatus || 'completed,rejected' // Show completed and rejected edit requests
            });

            const response = await axios.get(`/api/admin/edit-requests?${params}`);
            const data = response.data;
            
            displayEditHistory(data.data);
            displayHistoryPagination(data);
            updateHistoryCount(data.total);
            
        } catch (error) {
            console.error('Failed to load edit history:', error);
        }
    }

    function displayEditHistory(editRequests) {
        const tbody = document.getElementById('historyTable');
        const emptyState = document.getElementById('historyEmptyState');
        
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
                    ${getStatusBadge(request.status)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="text-sm text-gray-900">${new Date(request.updated_at).toLocaleDateString()}</div>
                    <div class="text-sm text-gray-500">${new Date(request.updated_at).toLocaleTimeString()}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="viewEditRequest(${request.id})" 
                        class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                    <button onclick="downloadEditRequest(${request.id})" 
                        class="text-green-600 hover:text-green-900">Download</button>
                </td>
            </tr>
        `).join('');
    }

    function getStatusBadge(status) {
        switch(status) {
            case 'completed':
                return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>';
            case 'rejected':
                return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>';
            default:
                return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
        }
    }

    function displayHistoryPagination(data) {
        const container = document.getElementById('historyPaginationContainer');
        
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
            paginationHTML += `<button onclick="loadEditHistory(${data.current_page - 1})" 
                class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Previous</button>`;
        }
        
        for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
            if (i === data.current_page) {
                paginationHTML += `<button class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-lg">${i}</button>`;
            } else {
                paginationHTML += `<button onclick="loadEditHistory(${i})" 
                    class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">${i}</button>`;
            }
        }
        
        if (data.current_page < data.last_page) {
            paginationHTML += `<button onclick="loadEditHistory(${data.current_page + 1})" 
                class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Next</button>`;
        }
        
        paginationHTML += `</div></div>`;
        container.innerHTML = paginationHTML;
    }

    function updateHistoryCount(total) {
        const countElement = document.getElementById('historyCount');
        if (countElement) {
            countElement.textContent = `${total} completed edit requests`;
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
        const dropdown = document.getElementById('historyUserDropdown');
        renderUserDropdown();
        dropdown.classList.remove('hidden');
    }

    function renderUserDropdown() {
        const dropdown = document.getElementById('historyUserDropdown');
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
                
                document.getElementById('historyUserSearchInput').value = userName;
                historyCurrentUser = userId;
                historyCurrentPage = 1;
                loadEditHistory();
                dropdown.classList.add('hidden');
            });
        });
    }

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('historyUserDropdown');
        const input = document.getElementById('historyUserSearchInput');
        if (!dropdown.contains(e.target) && e.target !== input) {
            dropdown.classList.add('hidden');
        }
    });

    // Action functions
    function viewEditRequest(id) {
        // Implement view functionality
        console.log('View edit request:', id);
    }

    function downloadEditRequest(id) {
        // Implement download functionality
        console.log('Download edit request:', id);
    }
</script>

@endsection 