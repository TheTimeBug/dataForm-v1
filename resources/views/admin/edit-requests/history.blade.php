@extends('admin.layout')

@section('title', 'Edit History')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit History</h1>
            <p class="text-gray-600 mt-1">View complete edit history with advanced filters</p>
        </div>
        <button onclick="refreshHistory()" 
            class="bg-green-100 text-green-800 px-4 py-2 rounded-lg hover:bg-green-200 transition duration-200 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refresh
        </button>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
            </svg>
            Filters
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" id="searchHistory" placeholder="Search edit history..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            </div>

            <!-- Date From Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" id="dateFrom" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            </div>

            <!-- Date To Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" id="dateTo" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center mt-4">
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Per Page:</label>
                <select id="perPageHistory" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
            
            <button onclick="clearFilters()" 
                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Clear Filters
            </button>
        </div>
    </div>

    <!-- History Results -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Edit History
            </h3>

            <!-- Results Info -->
            <div id="historyInfo" class="text-sm text-gray-600">
                <!-- Results info will be shown here -->
            </div>
        </div>

        <!-- History Table -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-3 py-3">ID</th>
                        <th class="px-3 py-3">User</th>
                        <th class="px-3 py-3">Admin</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3">Data Record</th>
                        <th class="px-3 py-3">Notes</th>
                        <th class="px-3 py-3">Created</th>
                        <th class="px-3 py-3">Updated</th>
                        <th class="px-3 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Table rows will be populated here -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="historyEmptyState" class="hidden text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Edit History Found</h3>
            <p class="text-gray-500">No edit history matches your search criteria.</p>
        </div>

        <!-- Pagination -->
        <div id="historyPagination" class="mt-6">
            <!-- Pagination will be populated here -->
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let searchTerm = '';
    let perPage = 10;
    let dateFrom = '';
    let dateTo = '';
    let statusFilter = '';

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadEditHistory();
        setupEventListeners();
    });

    function setupEventListeners() {
        // Search input with debounce
        let searchTimeout;
        document.getElementById('searchHistory').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchTerm = e.target.value;
                currentPage = 1;
                loadEditHistory();
            }, 300);
        });

        // Date filters
        document.getElementById('dateFrom').addEventListener('change', function(e) {
            dateFrom = e.target.value;
            currentPage = 1;
            loadEditHistory();
        });

        document.getElementById('dateTo').addEventListener('change', function(e) {
            dateTo = e.target.value;
            currentPage = 1;
            loadEditHistory();
        });

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function(e) {
            statusFilter = e.target.value;
            currentPage = 1;
            loadEditHistory();
        });

        // Per page selector
        document.getElementById('perPageHistory').addEventListener('change', function(e) {
            perPage = parseInt(e.target.value);
            currentPage = 1;
            loadEditHistory();
        });
    }

    // Load edit history with pagination and filters
    async function loadEditHistory(page = 1) {
        try {
            currentPage = page;
            const params = new URLSearchParams({
                page: currentPage,
                per_page: perPage,
                search: searchTerm,
                date_from: dateFrom,
                date_to: dateTo,
                status: statusFilter
            });

            // Remove empty parameters
            for (let [key, value] of [...params.entries()]) {
                if (!value) params.delete(key);
            }

            const response = await axios.get(`/api/admin/edit-history?${params}`);
            const data = response.data;
            
            displayEditHistory(data.data);
            displayHistoryPagination(data);
            
        } catch (error) {
            console.error('Failed to load edit history:', error);
            showMessage('Failed to load edit history', 'error');
        }
    }

    function displayEditHistory(editHistory) {
        const tbody = document.getElementById('historyTableBody');
        const emptyState = document.getElementById('historyEmptyState');
        const infoContainer = document.getElementById('historyInfo');
        
        if (editHistory.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            infoContainer.innerHTML = 'No results found';
            return;
        }
        
        emptyState.classList.add('hidden');
        
        tbody.innerHTML = editHistory.map(history => {
            const statusColors = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'completed': 'bg-green-100 text-green-800',
                'rejected': 'bg-red-100 text-red-800'
            };
            
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
            };
            
            return `
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-3 text-xs font-medium text-gray-900">#${history.id}</td>
                    <td class="px-3 py-3 text-xs text-blue-600">${history.user ? history.user.name : 'N/A'}</td>
                    <td class="px-3 py-3 text-xs text-green-600">${history.admin ? history.admin.name : 'N/A'}</td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${statusColors[history.status] || statusColors.pending}">
                            ${history.status ? history.status.charAt(0).toUpperCase() + history.status.slice(1) : 'Pending'}
                        </span>
                    </td>
                    <td class="px-3 py-3 text-xs text-gray-600">${history.data_record_id || history.parent_id || 'N/A'}</td>
                    <td class="px-3 py-3 text-xs text-gray-500 max-w-xs truncate">${history.admin_notes || 'No notes'}</td>
                    <td class="px-3 py-3 text-xs text-gray-400">${formatDate(history.created_at)}</td>
                    <td class="px-3 py-3 text-xs text-gray-400">${formatDate(history.updated_at)}</td>
                    <td class="px-3 py-3">
                        <button onclick="viewHistoryDetails(${history.id})" 
                            class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            View
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function displayHistoryPagination(data) {
        const paginationContainer = document.getElementById('historyPagination');
        const infoContainer = document.getElementById('historyInfo');
        
        // Show results info
        if (data.meta) {
            infoContainer.innerHTML = `Showing ${data.meta.from || 0} to ${data.meta.to || 0} of ${data.meta.total || 0} records`;
        }
        
        if (!data.meta || data.meta.last_page <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }
        
        let paginationHtml = '<div class="flex items-center justify-between">';
        
        // Previous button
        if (data.meta.current_page > 1) {
            paginationHtml += `<button onclick="loadEditHistory(${data.meta.current_page - 1})" 
                class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">Previous</button>`;
        } else {
            paginationHtml += '<span class="px-4 py-2 text-sm text-gray-400">Previous</span>';
        }
        
        // Page info
        paginationHtml += `<span class="text-sm text-gray-600">Page ${data.meta.current_page} of ${data.meta.last_page}</span>`;
        
        // Next button
        if (data.meta.current_page < data.meta.last_page) {
            paginationHtml += `<button onclick="loadEditHistory(${data.meta.current_page + 1})" 
                class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">Next</button>`;
        } else {
            paginationHtml += '<span class="px-4 py-2 text-sm text-gray-400">Next</span>';
        }
        
        paginationHtml += '</div>';
        paginationContainer.innerHTML = paginationHtml;
    }

    // Clear all filters
    function clearFilters() {
        currentPage = 1;
        searchTerm = '';
        dateFrom = '';
        dateTo = '';
        statusFilter = '';
        
        document.getElementById('searchHistory').value = '';
        document.getElementById('dateFrom').value = '';
        document.getElementById('dateTo').value = '';
        document.getElementById('statusFilter').value = '';
        
        loadEditHistory();
    }

    // Refresh function
    function refreshHistory() {
        loadEditHistory(currentPage);
    }

    // View history details function (placeholder)
    function viewHistoryDetails(id) {
        showMessage('View history details functionality will be implemented', 'info');
    }
</script>
@endsection 