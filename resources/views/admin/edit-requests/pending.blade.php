@extends('admin.layout')

@section('title', 'Pending Edit Requests')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pending Edit Requests</h1>
            <p class="text-gray-600 mt-1">Manage pending edit requests from users</p>
        </div>
        <button onclick="refreshPendingRequests()" 
            class="bg-green-100 text-green-800 px-4 py-2 rounded-lg hover:bg-green-200 transition duration-200 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refresh
        </button>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Send for Edit Form -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Send for Edit
                </h3>
                <form id="sendEditForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data Record ID *</label>
                        <input type="number" name="data_record_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                        <textarea name="admin_notes" rows="4" placeholder="Optional notes for the user..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <button type="submit" id="sendEditBtn"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        Send for Edit
                    </button>
                </form>
            </div>

            <!-- Pending Requests List -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pending Requests
                </h3>
                
                <!-- Search and Filters -->
                <div class="mb-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="text" id="searchPending" placeholder="Search pending requests..." 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="perPagePending" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results Info -->
                <div id="pendingInfo" class="text-sm text-gray-600 mb-3">
                    <!-- Results info will be shown here -->
                </div>

                <div id="pendingList" class="space-y-3 max-h-96 overflow-y-auto">
                    <!-- Pending requests will be loaded here -->
                </div>

                <!-- Pagination -->
                <div id="pendingPagination" class="mt-4">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let searchTerm = '';
    let perPage = 10;

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadPendingRequests();
        
        // Setup search functionality
        let searchTimeout;
        document.getElementById('searchPending').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchTerm = e.target.value;
                currentPage = 1;
                loadPendingRequests();
            }, 300);
        });

        // Setup per page change
        document.getElementById('perPagePending').addEventListener('change', function(e) {
            perPage = parseInt(e.target.value);
            currentPage = 1;
            loadPendingRequests();
        });
    });

    // Send for edit form submission
    document.getElementById('sendEditForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('sendEditBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Sending...';
        submitBtn.disabled = true;
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            await axios.post('/api/admin/send-edit-request', data);
            
            showMessage('Edit request sent successfully!', 'success');
            this.reset();
            loadPendingRequests();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to send edit request';
            showMessage(message, 'error');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });

    // Load pending requests
    async function loadPendingRequests() {
        try {
            const params = new URLSearchParams({
                page: currentPage,
                per_page: perPage,
                search: searchTerm,
                status: 'pending'
            });
            
            const response = await axios.get(`/api/admin/edit-requests?${params}`);
            const data = response.data;
            
            // Handle both paginated and non-paginated responses
            const requests = data.data || data;
            const pagination = data.meta || null;
            
            const container = document.getElementById('pendingList');
            const infoContainer = document.getElementById('pendingInfo');
            const paginationContainer = document.getElementById('pendingPagination');
            
            // Show results info
            if (pagination) {
                infoContainer.innerHTML = `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total || 0} pending requests`;
            } else {
                infoContainer.innerHTML = `Total: ${requests.length} pending requests`;
            }
            
            if (requests.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No pending edit requests found.</p>';
                paginationContainer.innerHTML = '';
                return;
            }
            
            container.innerHTML = requests.map(request => `
                <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">Edit Request #${request.id}</h4>
                            <p class="text-sm text-blue-600 mt-1">User: ${request.user.name}</p>
                            <p class="text-sm text-green-600">Data Record ID: ${request.data_record_id}</p>
                            ${request.admin_notes ? `<p class="text-sm text-gray-500 mt-2">${request.admin_notes}</p>` : ''}
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">Pending</span>
                                <span class="text-xs text-gray-400">${new Date(request.created_at).toLocaleDateString()}</span>
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button onclick="viewRequest(${request.id})" 
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">View</button>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Render pagination
            renderPagination(pagination, 'pendingPagination');
            
        } catch (error) {
            console.error('Failed to load pending requests:', error);
            showMessage('Failed to load pending requests', 'error');
        }
    }

    // Refresh function
    function refreshPendingRequests() {
        currentPage = 1;
        searchTerm = '';
        document.getElementById('searchPending').value = '';
        loadPendingRequests();
    }

    // Pagination function
    function renderPagination(pagination, containerId) {
        const container = document.getElementById(containerId);
        
        if (!pagination || pagination.last_page <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let paginationHtml = '<div class="flex items-center justify-between">';
        
        // Previous button
        if (pagination.current_page > 1) {
            paginationHtml += `<button onclick="changePage(${pagination.current_page - 1})" 
                class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">Previous</button>`;
        } else {
            paginationHtml += '<span class="px-3 py-1 text-sm text-gray-400">Previous</span>';
        }
        
        // Page info
        paginationHtml += `<span class="text-sm text-gray-600">Page ${pagination.current_page} of ${pagination.last_page}</span>`;
        
        // Next button  
        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `<button onclick="changePage(${pagination.current_page + 1})" 
                class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">Next</button>`;
        } else {
            paginationHtml += '<span class="px-3 py-1 text-sm text-gray-400">Next</span>';
        }
        
        paginationHtml += '</div>';
        container.innerHTML = paginationHtml;
    }

    // Change page function
    function changePage(page) {
        currentPage = page;
        loadPendingRequests();
    }

    // View request function (placeholder)
    function viewRequest(id) {
        showMessage('View request functionality will be implemented', 'info');
    }
</script>
@endsection 