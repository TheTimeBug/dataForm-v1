@extends('admin.layout')

@section('title', 'Divisions Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Divisions Management</h2>
                <p class="text-gray-600">Manage administrative divisions</p>
            </div>
            <nav class="text-sm text-gray-500">
                <a href="{{ route('admin.library') }}" class="hover:text-gray-700">Library</a>
                <span class="mx-2">›</span>
                <span class="text-gray-800">Divisions</span>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Add Division Form -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Division
                </h3>
                <form id="divisionForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name (English) *</label>
                        <input type="text" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name (Bengali)</label>
                        <input type="text" name="name_bn"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                        <input type="text" name="code" maxlength="10"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <button type="submit" id="divisionSubmitBtn"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        Add Division
                    </button>
                </form>
            </div>

            <!-- Divisions List -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    All Divisions
                </h3>
                
                <!-- Search and Filters -->
                <div class="mb-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="text" id="searchDivisions" placeholder="Search divisions..." 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="perPageDivisions" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results Info -->
                <div id="divisionsInfo" class="text-sm text-gray-600 mb-3">
                    <!-- Results info will be shown here -->
                </div>

                <div id="divisionsList" class="space-y-3 max-h-96 overflow-y-auto">
                    <!-- Divisions will be loaded here -->
                </div>

                <!-- Pagination -->
                <div id="divisionsPagination" class="mt-4">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-6">
            <h3 id="editModalTitle" class="text-xl font-semibold text-gray-800">Edit Division</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="editForm">
            <div id="editFormFields" class="space-y-4">
                <!-- Form fields will be dynamically generated -->
            </div>
            
            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" onclick="closeEditModal()" 
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                    Cancel
                </button>
                <button type="submit" id="editSubmitBtn"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Update Division
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentEditId = null;
    let currentPage = 1;
    let searchTerm = '';
    let perPage = 10;

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDivisions();
        
        // Setup search functionality
        let searchTimeout;
        document.getElementById('searchDivisions').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchTerm = e.target.value;
                currentPage = 1;
                loadDivisions();
            }, 300);
        });

        // Setup per page change
        document.getElementById('perPageDivisions').addEventListener('change', function(e) {
            perPage = parseInt(e.target.value);
            currentPage = 1;
            loadDivisions();
        });
    });

    // Division form submission
    document.getElementById('divisionForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('divisionSubmitBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Adding...';
        submitBtn.disabled = true;
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            await axios.post('/api/admin/library/divisions', data);
            
            showMessage('Division added successfully!', 'success');
            this.reset();
            loadDivisions();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to add division';
            showMessage(message, 'error');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });

    // Load divisions
    async function loadDivisions() {
        try {
            const params = new URLSearchParams({
                page: currentPage,
                per_page: perPage,
                search: searchTerm
            });
            
            const response = await axios.get(`/api/admin/library/divisions?${params}`);
            const data = response.data;
            
            // Handle both paginated and non-paginated responses
            const divisions = data.data || data;
            const pagination = data.meta || null;
            
            const container = document.getElementById('divisionsList');
            const infoContainer = document.getElementById('divisionsInfo');
            const paginationContainer = document.getElementById('divisionsPagination');
            
            // Show results info
            if (pagination) {
                infoContainer.innerHTML = `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total || 0} divisions`;
            } else {
                infoContainer.innerHTML = `Total: ${divisions.length} divisions`;
            }
            
            if (divisions.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No divisions found.</p>';
                paginationContainer.innerHTML = '';
                return;
            }
            
            container.innerHTML = divisions.map(division => `
                <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${division.name}</h4>
                            ${division.name_bn ? `<p class="text-sm text-gray-600">${division.name_bn}</p>` : ''}
                            ${division.code ? `<p class="text-xs text-blue-600 mt-1">Code: ${division.code}</p>` : ''}
                            ${division.description ? `<p class="text-sm text-gray-500 mt-2">${division.description}</p>` : ''}
                            <div class="flex items-center mt-2 text-xs text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                Districts: ${division.districts ? division.districts.length : 0}
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button onclick="editDivision(${division.id})" 
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                            <button onclick="deleteDivision(${division.id})" 
                                class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Render pagination
            renderPagination(pagination, 'divisionsPagination');
            
        } catch (error) {
            console.error('Failed to load divisions:', error);
            showMessage('Failed to load divisions', 'error');
        }
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
        loadDivisions();
    }

    // Edit division
    async function editDivision(id) {
        try {
            const response = await axios.get(`/api/admin/library/divisions`);
            const division = response.data.find(d => d.id === id);
            
            if (!division) {
                showMessage('Division not found', 'error');
                return;
            }
            
            currentEditId = id;
            
            // Populate edit form
            document.getElementById('editFormFields').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name (English) *</label>
                    <input type="text" name="name" value="${division.name}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name (Bengali)</label>
                    <input type="text" name="name_bn" value="${division.name_bn || ''}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                    <input type="text" name="code" value="${division.code || ''}" maxlength="10"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">${division.description || ''}</textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" ${division.is_active ? 'checked' : ''} 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
            `;
            
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
            
        } catch (error) {
            console.error('Failed to load division for editing:', error);
            showMessage('Failed to load division', 'error');
        }
    }

    // Delete division
    async function deleteDivision(id) {
        if (!confirm('Are you sure you want to delete this division? This will also delete all associated districts, upazilas, and mouzas.')) {
            return;
        }

        try {
            await axios.delete(`/api/admin/library/divisions/${id}`);
            showMessage('Division deleted successfully!', 'success');
            loadDivisions();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to delete division';
            showMessage(message, 'error');
        }
    }

    // Edit form submission
    document.getElementById('editForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('editSubmitBtn');
        submitBtn.textContent = 'Updating...';
        submitBtn.disabled = true;
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            data.is_active = formData.has('is_active');
            
            await axios.put(`/api/admin/library/divisions/${currentEditId}`, data);
            
            showMessage('Division updated successfully!', 'success');
            closeEditModal();
            loadDivisions();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to update division';
            showMessage(message, 'error');
        } finally {
            submitBtn.textContent = 'Update Division';
            submitBtn.disabled = false;
        }
    });

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
        currentEditId = null;
    }
</script>
@endsection 