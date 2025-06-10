@extends('admin.layout')

@section('title', 'Library Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Library Management</h2>
        <p class="text-gray-600">Manage geographical divisions, districts, upazilas, and mouzas</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6">
                <button onclick="showTab('divisions')" id="divisionsTab" class="library-tab-button active">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"></path>
                    </svg>
                    Divisions
                </button>
                <button onclick="showTab('districts')" id="districtsTab" class="library-tab-button">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Districts
                </button>
                <button onclick="showTab('upazilas')" id="upazilasTab" class="library-tab-button">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Upazilas
                </button>
                <button onclick="showTab('mouzas')" id="mouzasTab" class="library-tab-button">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mouzas
                </button>
            </nav>
        </div>

        <!-- Divisions Tab -->
        <div id="divisionsContent" class="library-tab-content p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Add Division Form -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New Division</h3>
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">All Divisions</h3>
                    <div id="divisionsList" class="space-y-3 max-h-96 overflow-y-auto">
                        <!-- Divisions will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Districts Tab -->
        <div id="districtsContent" class="library-tab-content hidden p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Add District Form -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New District</h3>
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">All Districts</h3>
                    <div id="districtsList" class="space-y-3 max-h-96 overflow-y-auto">
                        <!-- Districts will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Upazilas Tab -->
        <div id="upazilasContent" class="library-tab-content hidden p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Add Upazila Form -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New Upazila</h3>
                    <form id="upazilaForm" class="space-y-4">
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">All Upazilas</h3>
                    <div id="upazilasList" class="space-y-3 max-h-96 overflow-y-auto">
                        <!-- Upazilas will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Mouzas Tab -->
        <div id="mouzasContent" class="library-tab-content hidden p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Add Mouza Form -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New Mouza</h3>
                    <form id="mouzaForm" class="space-y-4">
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">All Mouzas</h3>
                    <div id="mouzasList" class="space-y-3 max-h-96 overflow-y-auto">
                        <!-- Mouzas will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-6">
            <h3 id="editModalTitle" class="text-xl font-semibold text-gray-800">Edit Item</h3>
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
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentEditType = '';
    let currentEditId = null;

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDivisions();
        loadDistricts();
        loadUpazilas();
        loadMouzas();
        populateDropdowns();
    });

    // Tab functionality
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.library-tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.library-tab-button').forEach(button => {
            button.classList.remove('active');
        });
        
        // Show selected tab content
        document.getElementById(tabName + 'Content').classList.remove('hidden');
        document.getElementById(tabName + 'Tab').classList.add('active');
    }

    // Division functions
    async function loadDivisions() {
        try {
            const response = await axios.get('/api/admin/library/divisions');
            const divisions = response.data;
            
            const container = document.getElementById('divisionsList');
            container.innerHTML = divisions.map(division => `
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${division.name}</h4>
                            ${division.name_bn ? `<p class="text-sm text-gray-600">${division.name_bn}</p>` : ''}
                            ${division.code ? `<p class="text-xs text-blue-600">Code: ${division.code}</p>` : ''}
                            ${division.description ? `<p class="text-sm text-gray-500 mt-1">${division.description}</p>` : ''}
                            <p class="text-xs text-gray-400 mt-2">Districts: ${division.districts ? division.districts.length : 0}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editItem('division', ${division.id})" 
                                class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button onclick="deleteItem('division', ${division.id})" 
                                class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>
                </div>
            `).join('');
            
        } catch (error) {
            console.error('Failed to load divisions:', error);
        }
    }

    // District functions
    async function loadDistricts() {
        try {
            const response = await axios.get('/api/admin/library/districts');
            const districts = response.data;
            
            const container = document.getElementById('districtsList');
            container.innerHTML = districts.map(district => `
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${district.name}</h4>
                            ${district.name_bn ? `<p class="text-sm text-gray-600">${district.name_bn}</p>` : ''}
                            <p class="text-xs text-green-600">Division: ${district.division.name}</p>
                            ${district.code ? `<p class="text-xs text-blue-600">Code: ${district.code}</p>` : ''}
                            ${district.description ? `<p class="text-sm text-gray-500 mt-1">${district.description}</p>` : ''}
                            <p class="text-xs text-gray-400 mt-2">Upazilas: ${district.upazilas ? district.upazilas.length : 0}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editItem('district', ${district.id})" 
                                class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button onclick="deleteItem('district', ${district.id})" 
                                class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>
                </div>
            `).join('');
            
        } catch (error) {
            console.error('Failed to load districts:', error);
        }
    }

    // Upazila functions
    async function loadUpazilas() {
        try {
            const response = await axios.get('/api/admin/library/upazilas');
            const upazilas = response.data;
            
            const container = document.getElementById('upazilasList');
            container.innerHTML = upazilas.map(upazila => `
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${upazila.name}</h4>
                            ${upazila.name_bn ? `<p class="text-sm text-gray-600">${upazila.name_bn}</p>` : ''}
                            <p class="text-xs text-green-600">District: ${upazila.district.name}</p>
                            <p class="text-xs text-purple-600">Division: ${upazila.district.division.name}</p>
                            ${upazila.code ? `<p class="text-xs text-blue-600">Code: ${upazila.code}</p>` : ''}
                            ${upazila.description ? `<p class="text-sm text-gray-500 mt-1">${upazila.description}</p>` : ''}
                            <p class="text-xs text-gray-400 mt-2">Mouzas: ${upazila.mouzas ? upazila.mouzas.length : 0}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editItem('upazila', ${upazila.id})" 
                                class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button onclick="deleteItem('upazila', ${upazila.id})" 
                                class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>
                </div>
            `).join('');
            
        } catch (error) {
            console.error('Failed to load upazilas:', error);
        }
    }

    // Mouza functions
    async function loadMouzas() {
        try {
            const response = await axios.get('/api/admin/library/mouzas');
            const mouzas = response.data;
            
            const container = document.getElementById('mouzasList');
            container.innerHTML = mouzas.map(mouza => `
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">${mouza.name}</h4>
                            ${mouza.name_bn ? `<p class="text-sm text-gray-600">${mouza.name_bn}</p>` : ''}
                            <p class="text-xs text-green-600">Upazila: ${mouza.upazila.name}</p>
                            <p class="text-xs text-orange-600">District: ${mouza.upazila.district.name}</p>
                            <p class="text-xs text-purple-600">Division: ${mouza.upazila.district.division.name}</p>
                            ${mouza.code ? `<p class="text-xs text-blue-600">Code: ${mouza.code}</p>` : ''}
                            ${mouza.description ? `<p class="text-sm text-gray-500 mt-1">${mouza.description}</p>` : ''}
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editItem('mouza', ${mouza.id})" 
                                class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button onclick="deleteItem('mouza', ${mouza.id})" 
                                class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>
                </div>
            `).join('');
            
        } catch (error) {
            console.error('Failed to load mouzas:', error);
        }
    }

    // Populate dropdowns
    async function populateDropdowns() {
        try {
            // Load divisions for district form
            const divisionsResponse = await axios.get('/api/admin/library/divisions');
            const divisions = divisionsResponse.data;
            
            const divisionSelect = document.getElementById('districtDivisionSelect');
            divisionSelect.innerHTML = '<option value="">Select Division</option>' + 
                divisions.map(division => `<option value="${division.id}">${division.name}</option>`).join('');

            // Load districts for upazila form
            const districtsResponse = await axios.get('/api/admin/library/districts');
            const districts = districtsResponse.data;
            
            const districtSelect = document.getElementById('upazilaDistrictSelect');
            districtSelect.innerHTML = '<option value="">Select District</option>' + 
                districts.map(district => `<option value="${district.id}">${district.name} (${district.division.name})</option>`).join('');

            // Load upazilas for mouza form
            const upazilasResponse = await axios.get('/api/admin/library/upazilas');
            const upazilas = upazilasResponse.data;
            
            const upazilaSelect = document.getElementById('mouzaUpazilaSelect');
            upazilaSelect.innerHTML = '<option value="">Select Upazila</option>' + 
                upazilas.map(upazila => `<option value="${upazila.id}">${upazila.name} (${upazila.district.name})</option>`).join('');

        } catch (error) {
            console.error('Failed to populate dropdowns:', error);
        }
    }

    // Form submissions
    document.getElementById('divisionForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await submitForm('division', this, 'divisionSubmitBtn');
    });

    document.getElementById('districtForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await submitForm('district', this, 'districtSubmitBtn');
    });

    document.getElementById('upazilaForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await submitForm('upazila', this, 'upazilaSubmitBtn');
    });

    document.getElementById('mouzaForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        await submitForm('mouza', this, 'mouzaSubmitBtn');
    });

    async function submitForm(type, form, buttonId) {
        const submitBtn = document.getElementById(buttonId);
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Adding...';
        submitBtn.disabled = true;
        
        try {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            
            await axios.post(`/api/admin/library/${type}s`, data);
            
            showMessage(`${type.charAt(0).toUpperCase() + type.slice(1)} added successfully!`, 'success');
            form.reset();
            
            // Reload data
            if (type === 'division') {
                loadDivisions();
                populateDropdowns();
            } else if (type === 'district') {
                loadDistricts();
                populateDropdowns();
            } else if (type === 'upazila') {
                loadUpazilas();
                populateDropdowns();
            } else if (type === 'mouza') {
                loadMouzas();
            }
            
        } catch (error) {
            const message = error.response?.data?.message || `Failed to add ${type}`;
            showMessage(message, 'error');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    }

    // Edit and delete functions
    async function editItem(type, id) {
        // Implementation for edit functionality
        console.log(`Edit ${type} with ID: ${id}`);
    }

    async function deleteItem(type, id) {
        if (!confirm(`Are you sure you want to delete this ${type}?`)) {
            return;
        }

        try {
            await axios.delete(`/api/admin/library/${type}s/${id}`);
            showMessage(`${type.charAt(0).toUpperCase() + type.slice(1)} deleted successfully!`, 'success');
            
            // Reload data
            if (type === 'division') {
                loadDivisions();
                populateDropdowns();
            } else if (type === 'district') {
                loadDistricts();
                populateDropdowns();
            } else if (type === 'upazila') {
                loadUpazilas();
                populateDropdowns();
            } else if (type === 'mouza') {
                loadMouzas();
            }
            
        } catch (error) {
            const message = error.response?.data?.message || `Failed to delete ${type}`;
            showMessage(message, 'error');
        }
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
</script>

<style>
    .library-tab-button {
        @apply py-4 px-6 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 transition duration-200 flex items-center;
    }
    .library-tab-button.active {
        @apply text-blue-600 border-blue-600;
    }
</style>
@endsection 