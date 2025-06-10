@extends('admin.layout')

@section('title', 'Upazilas Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Upazilas Management</h2>
                <p class="text-gray-600">Manage administrative upazilas</p>
            </div>
            <nav class="text-sm text-gray-500">
                <a href="{{ route('admin.library') }}" class="hover:text-gray-700">Library</a>
                <span class="mx-2">›</span>
                <span class="text-gray-800">Upazilas</span>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Add Upazila Form -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Upazila
                </h3>
                <form id="upazilaForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Division *</label>
                        <select name="division_id" required id="upazilaDivisionSelect" onchange="loadDistrictsByDivision()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Division</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">District *</label>
                        <select name="district_id" required id="upazilaDistrictSelect"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select District</option>
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
                    <button type="submit" id="upazilaSubmitBtn"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        Add Upazila
                    </button>
                </form>
            </div>

            <!-- Upazilas List -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    All Upazilas
                </h3>
                
                <!-- Search and Filters -->
                <div class="mb-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="text" id="searchUpazilas" placeholder="Search upazilas..." 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="perPageUpazilas" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results Info -->
                <div id="upazilasInfo" class="text-sm text-gray-600 mb-3">
                    <!-- Results info will be shown here -->
                </div>

                <div id="upazilasList" class="space-y-3 max-h-96 overflow-y-auto">
                    <!-- Upazilas will be loaded here -->
                </div>

                <!-- Pagination -->
                <div id="upazilasPagination" class="mt-4">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let divisionsData = [];
    let districtsData = [];
    let currentPage = 1;
    let searchTerm = '';
    let perPage = 10;

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDivisions();
        loadDistricts();
        loadUpazilas();
        
        // Setup search functionality
        let searchTimeout;
        document.getElementById('searchUpazilas').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchTerm = e.target.value;
                currentPage = 1;
                loadUpazilas();
            }, 300);
        });

        // Setup per page change
        document.getElementById('perPageUpazilas').addEventListener('change', function(e) {
            perPage = parseInt(e.target.value);
            currentPage = 1;
            loadUpazilas();
        });
    });

    // Load divisions for dropdown
    async function loadDivisions() {
        try {
            const response = await axios.get('/api/admin/library/divisions');
            divisionsData = response.data;
            
            const select = document.getElementById('upazilaDivisionSelect');
            select.innerHTML = '<option value="">Select Division</option>' + 
                divisionsData.map(division => `<option value="${division.id}">${division.name}</option>`).join('');
            
        } catch (error) {
            console.error('Failed to load divisions:', error);
        }
    }

    // Load all districts
    async function loadDistricts() {
        try {
            const response = await axios.get('/api/admin/library/districts');
            districtsData = response.data;
        } catch (error) {
            console.error('Failed to load districts:', error);
        }
    }

    // Load districts by division
    function loadDistrictsByDivision() {
        const divisionId = document.getElementById('upazilaDivisionSelect').value;
        const districtSelect = document.getElementById('upazilaDistrictSelect');
        
        if (!divisionId) {
            districtSelect.innerHTML = '<option value="">Select District</option>';
            return;
        }
        
        const filteredDistricts = districtsData.filter(district => district.division_id == divisionId);
        districtSelect.innerHTML = '<option value="">Select District</option>' + 
            filteredDistricts.map(district => `<option value="${district.id}">${district.name}</option>`).join('');
    }

    // Upazila form submission
    document.getElementById('upazilaForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('upazilaSubmitBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Adding...';
        submitBtn.disabled = true;
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            await axios.post('/api/admin/library/upazilas', data);
            
            showMessage('Upazila added successfully!', 'success');
            this.reset();
            loadUpazilas();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to add upazila';
            showMessage(message, 'error');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });

    // Load upazilas
    async function loadUpazilas() {
        try {
            const params = new URLSearchParams({
                page: currentPage,
                per_page: perPage,
                search: searchTerm
            });
            
            const response = await axios.get(`/api/admin/library/upazilas?${params}`);
            const data = response.data;
            
            // Handle both paginated and non-paginated responses
            const upazilas = data.data || data;
            const pagination = data.meta || null;
            
            const container = document.getElementById('upazilasList');
            const infoContainer = document.getElementById('upazilasInfo');
            const paginationContainer = document.getElementById('upazilasPagination');
            
            // Show results info
            if (pagination) {
                infoContainer.innerHTML = `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total || 0} upazilas`;
            } else {
                infoContainer.innerHTML = `Total: ${upazilas.length} upazilas`;
            }
            
            if (upazilas.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No upazilas found.</p>';
                paginationContainer.innerHTML = '';
                return;
            }
            
            container.innerHTML = upazilas.map(upazila => `
                <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${upazila.name}</h4>
                            ${upazila.name_bn ? `<p class="text-sm text-gray-600">${upazila.name_bn}</p>` : ''}
                            <p class="text-xs text-green-600 mt-1">District: ${upazila.district.name}</p>
                            <p class="text-xs text-purple-600">Division: ${upazila.district.division.name}</p>
                            ${upazila.code ? `<p class="text-xs text-blue-600">Code: ${upazila.code}</p>` : ''}
                            ${upazila.description ? `<p class="text-sm text-gray-500 mt-2">${upazila.description}</p>` : ''}
                            <div class="flex items-center mt-2 text-xs text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Mouzas: ${upazila.mouzas ? upazila.mouzas.length : 0}
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button onclick="deleteUpazila(${upazila.id})" 
                                class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Render pagination
            renderPagination(pagination, 'upazilasPagination');
            
        } catch (error) {
            console.error('Failed to load upazilas:', error);
            showMessage('Failed to load upazilas', 'error');
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
        loadUpazilas();
    }

    // Delete upazila
    async function deleteUpazila(id) {
        if (!confirm('Are you sure you want to delete this upazila? This will also delete all associated mouzas.')) {
            return;
        }

        try {
            await axios.delete(`/api/admin/library/upazilas/${id}`);
            showMessage('Upazila deleted successfully!', 'success');
            loadUpazilas();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to delete upazila';
            showMessage(message, 'error');
        }
    }
</script>
@endsection
