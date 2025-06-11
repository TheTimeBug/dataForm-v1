@extends('user.layout')

@section('title', 'Edit History')

@section('content')
    <!-- Edit History -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Edit History</h2>
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
                <input type="text" id="searchInput" placeholder="Search edit history..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex gap-3">
                <input type="date" id="dateFromInput" placeholder="From date" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <input type="date" id="dateToInput" placeholder="To date" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <select id="perPageSelect" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>

        <!-- Edit History Table -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-3 py-3">ID</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="px-3 py-3">Data ID</th>
                        <th class="px-3 py-3">Int 1</th>
                        <th class="px-3 py-3">Int 2</th>
                        <th class="px-3 py-3">Int 3</th>
                        <th class="px-3 py-3">Int 4</th>
                        <th class="px-3 py-3">Sel 1</th>
                        <th class="px-3 py-3">Sel 2</th>
                        <th class="px-3 py-3">Sel 3</th>
                        <th class="px-3 py-3">Sel 4</th>
                        <th class="px-3 py-3">Comment 1</th>
                        <th class="px-3 py-3">Comment 2</th>
                        <th class="px-3 py-3">Completed</th>
                    </tr>
                </thead>
                <tbody id="editHistoryTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Table rows will be populated here -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Edit History Found</h3>
            <p class="text-gray-500">No completed edit requests match your search criteria.</p>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="mt-6">
            <!-- Pagination will be populated here -->
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let currentPage = 1;
    let currentSearch = '';
    let currentDateFrom = '';
    let currentDateTo = '';
    let currentPerPage = 10;
    let searchTimeout;

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadEditHistory();
        setupEventListeners();
    });

    function setupEventListeners() {
        // Search input with debounce
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = e.target.value;
                currentPage = 1;
                loadEditHistory();
            }, 300);
        });

        // Date filters
        document.getElementById('dateFromInput').addEventListener('change', function(e) {
            currentDateFrom = e.target.value;
            currentPage = 1;
            loadEditHistory();
        });

        document.getElementById('dateToInput').addEventListener('change', function(e) {
            currentDateTo = e.target.value;
            currentPage = 1;
            loadEditHistory();
        });

        // Per page selector
        document.getElementById('perPageSelect').addEventListener('change', function(e) {
            currentPerPage = parseInt(e.target.value);
            currentPage = 1;
            loadEditHistory();
        });
    }

    // Load edit history with pagination and search
    async function loadEditHistory(page = 1) {
        try {
            currentPage = page;
            const params = new URLSearchParams({
                page: currentPage,
                per_page: currentPerPage,
                search: currentSearch,
                date_from: currentDateFrom,
                date_to: currentDateTo
            });

            const response = await axios.get(`/api/user/edit-history?${params}`);
            const data = response.data;
            
            displayEditHistory(data.data);
            displayPagination(data);
            
        } catch (error) {
            console.error('Failed to load edit history:', error);
            showMessage('Failed to load edit history', 'error');
        }
    }

    function displayEditHistory(editHistory) {
        const tbody = document.getElementById('editHistoryTableBody');
        const emptyState = document.getElementById('emptyState');
        
        if (editHistory.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        tbody.innerHTML = editHistory.map(request => {
            return `
                <!-- Main Data Row -->
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-3 text-xs font-medium text-gray-900">#${request.id}</td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    </td>
                    <td class="px-3 py-3 text-xs text-gray-600">${request.parent_id || request.id}</td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${request.integer_field_1}</span>
                    </td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${request.integer_field_2}</span>
                    </td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${request.integer_field_3}</span>
                    </td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${request.integer_field_4}</span>
                    </td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${request.selector_field_1}</span>
                    </td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${request.selector_field_2}</span>
                    </td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${request.selector_field_3}</span>
                    </td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${request.selector_field_4}</span>
                    </td>
                    <td class="px-3 py-3 text-xs text-gray-600 max-w-32 truncate" title="${request.comment_field_1}">
                        ${request.comment_field_1}
                    </td>
                    <td class="px-3 py-3 text-xs text-gray-600 max-w-32 truncate" title="${request.comment_field_2}">
                        ${request.comment_field_2}
                    </td>
                    <td class="px-3 py-3 text-xs text-gray-500">${new Date(request.updated_at).toLocaleDateString()}</td>
                </tr>
                
                <!-- Admin Notes Row -->
                <tr class="border-b border-gray-200" style="border-top: none;">
                    <td colspan="14" class="px-3 py-2 bg-gray-25">
                        <div class="border-l-4 border-green-300 pl-4">
                            ${request.admin_notes ? `
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-2">
                                    <p class="text-xs text-green-800 font-medium mb-1">Admin Notes:</p>
                                    <p class="text-xs text-green-700">${request.admin_notes}</p>
                                </div>
                            ` : `
                                <div class="text-xs text-gray-500 italic mb-2">No admin notes</div>
                            `}
                            <p class="text-xs text-gray-600">
                                <strong>Requested by:</strong> ${request.admin ? request.admin.name : 'Admin'} on ${new Date(request.created_at).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </p>
                            <p class="text-xs text-gray-600">
                                <strong>Completed on:</strong> ${new Date(request.updated_at).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </p>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function displayPagination(data) {
        const container = document.getElementById('paginationContainer');
        
        if (data.last_page <= 1) {
            container.innerHTML = '';
            return;
        }
        
        const startRecord = ((data.current_page - 1) * data.per_page) + 1;
        const endRecord = Math.min(data.current_page * data.per_page, data.total);
        
        let paginationHTML = `
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <div class="text-sm text-gray-700 mb-3 sm:mb-0">
                    Showing ${startRecord} to ${endRecord} of ${data.total} results
                </div>
                <div class="flex items-center space-x-2">
        `;
        
        // Previous button
        if (data.current_page > 1) {
            paginationHTML += `
                <button onclick="loadEditHistory(${data.current_page - 1})" 
                    class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Previous
                </button>
            `;
        } else {
            paginationHTML += `
                <button disabled 
                    class="px-3 py-2 text-sm text-gray-300 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                    Previous
                </button>
            `;
        }
        
        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, data.current_page - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(data.last_page, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage < maxVisiblePages - 1) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === data.current_page) {
                paginationHTML += `
                    <button class="px-3 py-2 text-sm text-white bg-green-600 border border-green-600 rounded-lg">
                        ${i}
                    </button>
                `;
            } else {
                paginationHTML += `
                    <button onclick="loadEditHistory(${i})" 
                        class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        ${i}
                    </button>
                `;
            }
        }
        
        // Next button
        if (data.current_page < data.last_page) {
            paginationHTML += `
                <button onclick="loadEditHistory(${data.current_page + 1})" 
                    class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Next
                </button>
            `;
        } else {
            paginationHTML += `
                <button disabled 
                    class="px-3 py-2 text-sm text-gray-300 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                    Next
                </button>
            `;
        }
        
        paginationHTML += `
                </div>
            </div>
        `;
        
        container.innerHTML = paginationHTML;
    }
</script>
@endsection 