@extends('admin.layout')

@section('title', 'Data Submissions')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Submissions</h1>
                <p class="text-gray-600 mt-1">View and manage all submitted data records</p>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span id="submissionsCount">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Data Submissions Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">All Submissions</h2>
            <button onclick="loadSubmissions()" 
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
                <input type="text" id="submissionsSearchInput" placeholder="Search submissions..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-3">
                <input type="date" id="submissionsDateFromInput" placeholder="From date" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <input type="date" id="submissionsDateToInput" placeholder="To date" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="relative">
                    <div class="relative">
                        <input type="text" id="submissionsUserSearchInput" placeholder="Search users..." 
                            class="pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-48"
                            autocomplete="off">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div id="submissionsUserDropdown" class="absolute z-20 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto mt-1">
                    </div>
                </div>
                <select id="submissionsPerPageSelect" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>

        <!-- Submissions Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Integer Fields</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selector Fields</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="submissionsTable" class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="submissionsEmptyState" class="hidden text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Submissions Found</h3>
            <p class="text-gray-500">No data submissions match your search criteria.</p>
        </div>

        <!-- Pagination -->
        <div id="submissionsPaginationContainer" class="mt-6">
        </div>
    </div>
</div>

<script>
    // Load submissions functionality
    let submissionsCurrentPage = 1;
    let submissionsCurrentSearch = '';
    let submissionsCurrentDateFrom = '';
    let submissionsCurrentDateTo = '';
    let submissionsCurrentUser = '';
    let submissionsCurrentPerPage = 10;
    let submissionsSearchTimeout;

    // User search variables
    let allUsers = [];
    let filteredUsers = [];
    let submissionsUserSearchTimeout;

    document.addEventListener('DOMContentLoaded', function() {
        loadSubmissions();
        setupSubmissionsFilters();
        loadUsersForFilter();
    });

    function setupSubmissionsFilters() {
        // Search input
        const searchInput = document.getElementById('submissionsSearchInput');
        searchInput?.addEventListener('input', function() {
            clearTimeout(submissionsSearchTimeout);
            submissionsSearchTimeout = setTimeout(() => {
                submissionsCurrentSearch = this.value;
                submissionsCurrentPage = 1;
                loadSubmissions();
            }, 500);
        });

        // Date filters
        const dateFromInput = document.getElementById('submissionsDateFromInput');
        dateFromInput?.addEventListener('change', function() {
            submissionsCurrentDateFrom = this.value;
            submissionsCurrentPage = 1;
            loadSubmissions();
        });

        const dateToInput = document.getElementById('submissionsDateToInput');
        dateToInput?.addEventListener('change', function() {
            submissionsCurrentDateTo = this.value;
            submissionsCurrentPage = 1;
            loadSubmissions();
        });

        // User search filter
        const userSearchInput = document.getElementById('submissionsUserSearchInput');
        userSearchInput?.addEventListener('input', function() {
            clearTimeout(submissionsUserSearchTimeout);
            submissionsUserSearchTimeout = setTimeout(() => {
                filterUserDropdown(this.value);
            }, 300);
        });

        userSearchInput?.addEventListener('focus', function() {
            showUserDropdown();
        });

        // Per page filter
        const perPageSelect = document.getElementById('submissionsPerPageSelect');
        perPageSelect?.addEventListener('change', function() {
            submissionsCurrentPerPage = parseInt(this.value);
            submissionsCurrentPage = 1;
            loadSubmissions();
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

    async function loadSubmissions(page = 1) {
        try {
            submissionsCurrentPage = page;
            const params = new URLSearchParams({
                page: submissionsCurrentPage,
                per_page: submissionsCurrentPerPage,
                search: submissionsCurrentSearch,
                date_from: submissionsCurrentDateFrom,
                date_to: submissionsCurrentDateTo,
                user: submissionsCurrentUser
            });

            const response = await axios.get(`/api/admin/submissions?${params}`);
            const data = response.data;
            
            displaySubmissions(data.data);
            displaySubmissionsPagination(data);
            updateSubmissionsCount(data.total);
            
        } catch (error) {
            console.error('Failed to load submissions:', error);
        }
    }

    function displaySubmissions(submissions) {
        const tbody = document.getElementById('submissionsTable');
        const emptyState = document.getElementById('submissionsEmptyState');
        
        if (submissions.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        tbody.innerHTML = submissions.map(submission => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${submission.id}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-800">${submission.user.name.charAt(0).toUpperCase()}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${submission.user.name}</div>
                            <div class="text-sm text-gray-500">${submission.user.email}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex flex-wrap gap-1">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${submission.integer_field_1}</span>
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${submission.integer_field_2}</span>
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${submission.integer_field_3}</span>
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${submission.integer_field_4}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex flex-wrap gap-1">
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${submission.selector_field_1}</span>
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${submission.selector_field_2}</span>
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${submission.selector_field_3}</span>
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${submission.selector_field_4}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    <div class="max-w-xs">
                        <div class="truncate font-medium">${submission.comment_field_1}</div>
                        <div class="truncate text-gray-500">${submission.comment_field_2}</div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="text-sm text-gray-900">${new Date(submission.created_at).toLocaleDateString()}</div>
                    <div class="text-sm text-gray-500">${new Date(submission.created_at).toLocaleTimeString()}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button onclick="sendForEdit(${submission.id})" 
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition duration-200">
                        Send for Edit
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function displaySubmissionsPagination(data) {
        const container = document.getElementById('submissionsPaginationContainer');
        
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
            paginationHTML += `<button onclick="loadSubmissions(${data.current_page - 1})" 
                class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Previous</button>`;
        }
        
        for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
            if (i === data.current_page) {
                paginationHTML += `<button class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-lg">${i}</button>`;
            } else {
                paginationHTML += `<button onclick="loadSubmissions(${i})" 
                    class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">${i}</button>`;
            }
        }
        
        if (data.current_page < data.last_page) {
            paginationHTML += `<button onclick="loadSubmissions(${data.current_page + 1})" 
                class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Next</button>`;
        }
        
        paginationHTML += `</div></div>`;
        container.innerHTML = paginationHTML;
    }

    function updateSubmissionsCount(total) {
        const countElement = document.getElementById('submissionsCount');
        if (countElement) {
            countElement.textContent = `${total} total submissions`;
        }
    }

    function showUserDropdown() {
        const dropdown = document.getElementById('submissionsUserDropdown');
        if (dropdown && filteredUsers.length > 0) {
            populateUserDropdown();
            dropdown.classList.remove('hidden');
        }
    }

    function filterUserDropdown(searchText) {
        if (searchText.trim() === '') {
            filteredUsers = [...allUsers];
        } else {
            filteredUsers = allUsers.filter(user => 
                user.name.toLowerCase().includes(searchText.toLowerCase()) ||
                user.email.toLowerCase().includes(searchText.toLowerCase())
            );
        }
        populateUserDropdown();
        showUserDropdown();
    }

    function populateUserDropdown() {
        const dropdown = document.getElementById('submissionsUserDropdown');
        if (!dropdown) return;

        let html = `
            <div class="user-option p-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100" onclick="selectUser('', 'All Users')">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <span class="text-xs font-medium text-gray-500">All</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">All Users</div>
                    </div>
                </div>
            </div>
        `;

        html += filteredUsers.map(user => `
            <div class="user-option p-2 hover:bg-gray-100 cursor-pointer" onclick="selectUser('${user.id}', '${user.name.replace(/'/g, "\\'")}')">
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

        dropdown.innerHTML = html;
    }

    function selectUser(userId, userName) {
        const searchInput = document.getElementById('submissionsUserSearchInput');
        if (searchInput) {
            searchInput.value = userName;
        }
        
        submissionsCurrentUser = userId;
        submissionsCurrentPage = 1;
        document.getElementById('submissionsUserDropdown').classList.add('hidden');
        loadSubmissions();
    }

    function sendForEdit(submissionId) {
        // Simple implementation - you can enhance this with a modal
        if (confirm('Send this submission for edit?')) {
            // Add your send for edit logic here
            console.log('Sending submission', submissionId, 'for edit');
        }
    }
</script>

@endsection 