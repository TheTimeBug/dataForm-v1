@extends('admin.layout')

@section('title', 'Districts Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Districts Management</h2>
                <p class="text-gray-600">Manage administrative districts</p>
            </div>
            <nav class="text-sm text-gray-500">
                <a href="{{ route('admin.library') }}" class="hover:text-gray-700">Library</a>
                <span class="mx-2">›</span>
                <span class="text-gray-800">Districts</span>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Add District Form -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New District
                </h3>
                <form id="districtForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Division *</label>
                        <select name="division_id" required id="districtDivisionSelect"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Division</option>
                        </select>
                    </div>
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
                    <button type="submit" id="districtSubmitBtn"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        Add District
                    </button>
                </form>
            </div>

            <!-- Districts List -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    All Districts
                </h3>
                
                <!-- Search and Filters -->
                <div class="mb-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="text" id="searchDistricts" placeholder="Search districts..." 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="perPageDistricts" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results Info -->
                <div id="districtsInfo" class="text-sm text-gray-600 mb-3">
                    <!-- Results info will be shown here -->
                </div>

                <div id="districtsList" class="space-y-3 max-h-96 overflow-y-auto">
                    <!-- Districts will be loaded here -->
                </div>

                <!-- Pagination -->
                <div id="districtsPagination" class="mt-4">
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
            <h3 id="editModalTitle" class="text-xl font-semibold text-gray-800">Edit District</h3>
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
                    Update District
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentEditId = null;
    let divisionsData = [];
    let currentPage = 1;
    let searchTerm = '';
    let perPage = 10;

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDivisions();
        loadDistricts();
        
        // Setup search functionality
        let searchTimeout;
        document.getElementById('searchDistricts').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchTerm = e.target.value;
                currentPage = 1;
                loadDistricts();
            }, 300);
        });

        // Setup per page change
        document.getElementById('perPageDistricts').addEventListener('change', function(e) {
            perPage = parseInt(e.target.value);
            currentPage = 1;
            loadDistricts();
        });
    });

    // Load divisions for dropdown
    async function loadDivisions() {
        try {
            const response = await axios.get('/api/admin/library/divisions');
            divisionsData = response.data;
            
            const select = document.getElementById('districtDivisionSelect');
            select.innerHTML = '<option value="">Select Division</option>' + 
                divisionsData.map(division => `<option value="${division.id}">${division.name}</option>`).join('');
            
        } catch (error) {
            console.error('Failed to load divisions:', error);
        }
    }

    // District form submission
    document.getElementById('districtForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('districtSubmitBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Adding...';
        submitBtn.disabled = true;
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            await axios.post('/api/admin/library/districts', data);
            
            showMessage('District added successfully!', 'success');
            this.reset();
            loadDistricts();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to add district';
            showMessage(message, 'error');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });

    // Load districts
    async function loadDistricts() {
        try {
            const params = new URLSearchParams({
                page: currentPage,
                per_page: perPage,
                search: searchTerm
            });
            
            const response = await axios.get(`/api/admin/library/districts?${params}`);
            const data = response.data;
            
            // Handle both paginated and non-paginated responses
            const districts = data.data || data;
            const pagination = data.meta || null;
            
            const container = document.getElementById('districtsList');
            const infoContainer = document.getElementById('districtsInfo');
            const paginationContainer = document.getElementById('districtsPagination');
            
            // Show results info
            if (pagination) {
                infoContainer.innerHTML = `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total || 0} districts`;
            } else {
                infoContainer.innerHTML = `Total: ${districts.length} districts`;
            }
            
            if (districts.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No districts found.</p>';
                paginationContainer.innerHTML = '';
                return;
            }
            
            container.innerHTML = districts.map(district => `
                <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${district.name}</h4>
                            ${district.name_bn ? `<p class="text-sm text-gray-600">${district.name_bn}</p>` : ''}
                            <p class="text-xs text-green-600 mt-1">Division: ${district.division.name}</p>
                            ${district.code ? `<p class="text-xs text-blue-600">Code: ${district.code}</p>` : ''}
                            ${district.description ? `<p class="text-sm text-gray-500 mt-2">${district.description}</p>` : ''}
                            <div class="flex items-center mt-2 text-xs text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h1a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Upazilas: ${district.upazilas ? district.upazilas.length : 0}
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button onclick="editDistrict(${district.id})" 
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                            <button onclick="deleteDistrict(${district.id})" 
                                class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Render pagination
            renderPagination(pagination, 'districtsPagination');
            
        } catch (error) {
            console.error('Failed to load districts:', error);
            showMessage('Failed to load districts', 'error');
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
        loadDistricts();
    }

    // Edit district
    async function editDistrict(id) {
        try {
            const response = await axios.get(`/api/admin/library/districts`);
            const district = response.data.find(d => d.id === id);
            
            if (!district) {
                showMessage('District not found', 'error');
                return;
            }
            
            currentEditId = id;
            
            // Populate edit form
            const divisionOptions = divisionsData.map(division => 
                `<option value="${division.id}" ${division.id === district.division_id ? 'selected' : ''}>${division.name}</option>`
            ).join('');
            
            document.getElementById('editFormFields').innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Division *</label>
                    <select name="division_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Division</option>
                        ${divisionOptions}
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name (English) *</label>
                    <input type="text" name="name" value="${district.name}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name (Bengali)</label>
                    <input type="text" name="name_bn" value="${district.name_bn || ''}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                    <input type="text" name="code" value="${district.code || ''}" maxlength="10"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">${district.description || ''}</textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" ${district.is_active ? 'checked' : ''} 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
            `;
            
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
            
        } catch (error) {
            console.error('Failed to load district for editing:', error);
            showMessage('Failed to load district', 'error');
        }
    }

    // Delete district
    async function deleteDistrict(id) {
        if (!confirm('Are you sure you want to delete this district? This will also delete all associated upazilas and mouzas.')) {
            return;
        }

        try {
            await axios.delete(`/api/admin/library/districts/${id}`);
            showMessage('District deleted successfully!', 'success');
            loadDistricts();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to delete district';
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
            
            await axios.put(`/api/admin/library/districts/${currentEditId}`, data);
            
            showMessage('District updated successfully!', 'success');
            closeEditModal();
            loadDistricts();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to update district';
            showMessage(message, 'error');
        } finally {
            submitBtn.textContent = 'Update District';
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