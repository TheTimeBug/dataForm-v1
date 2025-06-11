@extends('admin.layout')

@section('title', 'Mouzas Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Mouzas Management</h2>
                <p class="text-gray-600">Manage administrative mouzas</p>
            </div>
            <nav class="text-sm text-gray-500">
                <a href="{{ route('admin.library.index') }}" class="hover:text-gray-700">Library</a>
                <span class="mx-2">›</span>
                <span class="text-gray-800">Mouzas</span>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Add Mouza Form -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Mouza
                </h3>
                <form id="mouzaForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Division *</label>
                        <select name="division_id" required id="mouzaDivisionSelect" onchange="loadDistrictsByDivisionForMouza()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Division</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">District *</label>
                        <select name="district_id" required id="mouzaDistrictSelect" onchange="loadUpazilasByDistrictForMouza()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upazila *</label>
                        <select name="upazila_id" required id="mouzaUpazilaSelect"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Upazila</option>
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
                    <button type="submit" id="mouzaSubmitBtn"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        Add Mouza
                    </button>
                </form>
            </div>

            <!-- Mouzas List -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    All Mouzas
                </h3>
                
                <!-- Search and Filters -->
                <div class="mb-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="text" id="searchMouzas" placeholder="Search mouzas..." 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="perPageMouzas" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results Info -->
                <div id="mouzasInfo" class="text-sm text-gray-600 mb-3">
                    <!-- Results info will be shown here -->
                </div>

                <div id="mouzasList" class="space-y-3 max-h-96 overflow-y-auto">
                    <!-- Mouzas will be loaded here -->
                </div>

                <!-- Pagination -->
                <div id="mouzasPagination" class="mt-4">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Authorization handled at Laravel level - no JavaScript checks needed
    console.log('Mouzas page loaded with Laravel-level authorization');

    let divisionsData = [];
    let districtsData = [];
    let upazilasData = [];
    let currentPage = 1;
    let searchTerm = '';
    let perPage = 10;

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDivisions();
        loadDistricts();
        loadUpazilas();
        loadMouzas();
        
        // Setup search functionality
        let searchTimeout;
        document.getElementById('searchMouzas').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchTerm = e.target.value;
                currentPage = 1;
                loadMouzas();
            }, 300);
        });

        // Setup per page change
        document.getElementById('perPageMouzas').addEventListener('change', function(e) {
            perPage = parseInt(e.target.value);
            currentPage = 1;
            loadMouzas();
        });
    });

    // Load divisions for dropdown
    async function loadDivisions() {
        try {
            const response = await axios.get('/api/admin/library/divisions');
            divisionsData = response.data;
            
            const select = document.getElementById('mouzaDivisionSelect');
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

    // Load all upazilas
    async function loadUpazilas() {
        try {
            const response = await axios.get('/api/admin/library/upazilas');
            upazilasData = response.data;
        } catch (error) {
            console.error('Failed to load upazilas:', error);
        }
    }

    // Load districts by division
    function loadDistrictsByDivisionForMouza() {
        const divisionId = document.getElementById('mouzaDivisionSelect').value;
        const districtSelect = document.getElementById('mouzaDistrictSelect');
        const upazilaSelect = document.getElementById('mouzaUpazilaSelect');
        
        if (!divisionId) {
            districtSelect.innerHTML = '<option value="">Select District</option>';
            upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
            return;
        }
        
        const filteredDistricts = districtsData.filter(district => district.division_id == divisionId);
        districtSelect.innerHTML = '<option value="">Select District</option>' + 
            filteredDistricts.map(district => `<option value="${district.id}">${district.name}</option>`).join('');
        
        upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
    }

    // Load upazilas by district
    function loadUpazilasByDistrictForMouza() {
        const districtId = document.getElementById('mouzaDistrictSelect').value;
        const upazilaSelect = document.getElementById('mouzaUpazilaSelect');
        
        if (!districtId) {
            upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
            return;
        }
        
        const filteredUpazilas = upazilasData.filter(upazila => upazila.district_id == districtId);
        upazilaSelect.innerHTML = '<option value="">Select Upazila</option>' + 
            filteredUpazilas.map(upazila => `<option value="${upazila.id}">${upazila.name}</option>`).join('');
    }

    // Mouza form submission
    document.getElementById('mouzaForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('mouzaSubmitBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Adding...';
        submitBtn.disabled = true;
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            await axios.post('/api/admin/library/mouzas', data);
            
            showMessage('Mouza added successfully!', 'success');
            this.reset();
            loadMouzas();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to add mouza';
            showMessage(message, 'error');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });

    // Load mouzas
    async function loadMouzas() {
        try {
            const params = new URLSearchParams({
                page: currentPage,
                per_page: perPage,
                search: searchTerm
            });
            
            const response = await axios.get(`/api/admin/library/mouzas?${params}`);
            const data = response.data;
            
            // Handle both paginated and non-paginated responses
            const mouzas = data.data || data;
            const pagination = data.meta || null;
            
            const container = document.getElementById('mouzasList');
            const infoContainer = document.getElementById('mouzasInfo');
            const paginationContainer = document.getElementById('mouzasPagination');
            
            // Show results info
            if (pagination) {
                infoContainer.innerHTML = `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total || 0} mouzas`;
            } else {
                infoContainer.innerHTML = `Total: ${mouzas.length} mouzas`;
            }
            
            if (mouzas.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No mouzas found.</p>';
                paginationContainer.innerHTML = '';
                return;
            }
            
            container.innerHTML = mouzas.map(mouza => `
                <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${mouza.name}</h4>
                            ${mouza.name_bn ? `<p class="text-sm text-gray-600">${mouza.name_bn}</p>` : ''}
                            <p class="text-xs text-green-600 mt-1">Upazila: ${mouza.upazila.name}</p>
                            <p class="text-xs text-orange-600">District: ${mouza.upazila.district.name}</p>
                            <p class="text-xs text-purple-600">Division: ${mouza.upazila.district.division.name}</p>
                            ${mouza.code ? `<p class="text-xs text-blue-600">Code: ${mouza.code}</p>` : ''}
                            ${mouza.description ? `<p class="text-sm text-gray-500 mt-2">${mouza.description}</p>` : ''}
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button onclick="deleteMouza(${mouza.id})" 
                                class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Render pagination
            renderPagination(pagination, 'mouzasPagination');
            
        } catch (error) {
            console.error('Failed to load mouzas:', error);
            showMessage('Failed to load mouzas', 'error');
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
        loadMouzas();
    }

    // Delete mouza
    async function deleteMouza(id) {
        if (!confirm('Are you sure you want to delete this mouza?')) {
            return;
        }

        try {
            await axios.delete(`/api/admin/library/mouzas/${id}`);
            showMessage('Mouza deleted successfully!', 'success');
            loadMouzas();
            
        } catch (error) {
            const message = error.response?.data?.message || 'Failed to delete mouza';
            showMessage(message, 'error');
        }
    }
</script>
@endsection
