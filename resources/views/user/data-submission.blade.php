@extends('user.layout')

@section('title', 'Data Submission')

@section('content')
    <!-- Add Data Record Form -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Add Data Record</h3>
        
        <form id="dataRecordForm" class="flex flex-wrap items-end gap-3">
            <!-- Integer Fields -->
            <div class="flex-1 min-w-20">
                <label class="block text-xs font-medium text-gray-600 mb-1">Int 1</label>
                <input type="number" name="integer_field_1" required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex-1 min-w-20">
                <label class="block text-xs font-medium text-gray-600 mb-1">Int 2</label>
                <input type="number" name="integer_field_2" required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex-1 min-w-20">
                <label class="block text-xs font-medium text-gray-600 mb-1">Int 3</label>
                <input type="number" name="integer_field_3" required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex-1 min-w-20">
                <label class="block text-xs font-medium text-gray-600 mb-1">Int 4</label>
                <input type="number" name="integer_field_4" required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Selector Fields -->
            <div class="flex-1 min-w-24">
                <label class="block text-xs font-medium text-gray-600 mb-1">Select 1</label>
                <select name="selector_field_1" required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent">
                    <option value="">Choose</option>
                    <option value="Option A">Option A</option>
                    <option value="Option B">Option B</option>
                    <option value="Option C">Option C</option>
                </select>
            </div>
            <div class="flex-1 min-w-24">
                <label class="block text-xs font-medium text-gray-600 mb-1">Select 2</label>
                <select name="selector_field_2" required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent">
                    <option value="">Choose</option>
                    <option value="Option X">Option X</option>
                    <option value="Option Y">Option Y</option>
                    <option value="Option Z">Option Z</option>
                </select>
            </div>
            <div class="flex-1 min-w-24">
                <label class="block text-xs font-medium text-gray-600 mb-1">Select 3</label>
                <select name="selector_field_3" required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent">
                    <option value="">Choose</option>
                    <option value="Type 1">Type 1</option>
                    <option value="Type 2">Type 2</option>
                    <option value="Type 3">Type 3</option>
                </select>
            </div>
            <div class="flex-1 min-w-24">
                <label class="block text-xs font-medium text-gray-600 mb-1">Select 4</label>
                <select name="selector_field_4" required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent">
                    <option value="">Choose</option>
                    <option value="Category 1">Category 1</option>
                    <option value="Category 2">Category 2</option>
                    <option value="Category 3">Category 3</option>
                </select>
            </div>

            <!-- Comment Fields and Submit Button in same row -->
            <div class="flex-1 min-w-32">
                <label class="block text-xs font-medium text-gray-600 mb-1">Comment 1</label>
                <input type="text" name="comment_field_1" required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent"
                    placeholder="Enter comment...">
            </div>
            <div class="flex-1 min-w-32">
                <label class="block text-xs font-medium text-gray-600 mb-1">Comment 2</label>
                <input type="text" name="comment_field_2" required
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent"
                    placeholder="Enter comment...">
            </div>
            <div class="flex-shrink-0">
                <label class="block text-xs font-medium text-gray-600 mb-1">&nbsp;</label>
                <button type="submit" id="submitBtn"
                    class="bg-green-600 text-white px-4 py-1.5 text-sm rounded hover:bg-green-700 focus:ring-1 focus:ring-green-500 focus:ring-offset-1 transition duration-200 font-medium">
                    Submit
                </button>
            </div>
        </form>
    </div>

    <!-- Data Records Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Your Data Records</h2>
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search records..." 
                        class="pl-8 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <svg class="w-4 h-4 absolute left-2.5 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <select id="perPageSelect" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
                <button onclick="loadDataRecords()" 
                    class="bg-green-100 text-green-800 px-3 py-2 rounded-lg hover:bg-green-200 transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
        
        <!-- Records Info -->
        <div id="recordsInfo" class="mb-4 text-sm text-gray-600"></div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Int 1</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Int 2</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Int 3</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Int 4</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Select 1</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Select 2</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Select 3</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Select 4</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment 1</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment 2</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    </tr>
                </thead>
                <tbody id="dataRecordsTable" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div id="paginationContainer" class="mt-6 flex items-center justify-between">
            <div id="paginationInfo" class="text-sm text-gray-700"></div>
            <div id="paginationLinks" class="flex space-x-1"></div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let currentPage = 1;
    let currentSearch = '';
    let currentPerPage = 10;

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDataRecords();
        setupEventListeners();
    });

    function setupEventListeners() {
        // Search input with debounce
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = e.target.value;
                currentPage = 1;
                loadDataRecords();
            }, 300);
        });

        // Per page selector
        document.getElementById('perPageSelect').addEventListener('change', function(e) {
            currentPerPage = parseInt(e.target.value);
            currentPage = 1;
            loadDataRecords();
        });
    }

    // Submit data record form
    document.getElementById('dataRecordForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;
        
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Convert integer fields to numbers
            ['integer_field_1', 'integer_field_2', 'integer_field_3', 'integer_field_4'].forEach(field => {
                data[field] = parseInt(data[field]);
            });
            
            await axios.post('/api/user/data-records', data);
            
            showMessage('Data record submitted successfully!', 'success');
            this.reset();
            loadDataRecords();
            
        } catch (error) {
            showMessage(error.response?.data?.message || 'Failed to submit data record', 'error');
        } finally {
            submitBtn.textContent = 'Submit';
            submitBtn.disabled = false;
        }
    });

    // Load data records with pagination and search
    async function loadDataRecords(page = currentPage) {
        try {
            const params = new URLSearchParams({
                page: page,
                per_page: currentPerPage,
                search: currentSearch
            });

            const response = await axios.get(`/api/user/data-records?${params}`);
            const data = response.data;
            
            updateRecordsInfo(data);
            renderTable(data.data);
            renderPagination(data);
            
            currentPage = page;
            
        } catch (error) {
            console.error('Failed to load data records:', error);
            showMessage('Failed to load data records', 'error');
        }
    }

    function updateRecordsInfo(data) {
        const recordsInfo = document.getElementById('recordsInfo');
        if (data.total === 0) {
            recordsInfo.innerHTML = 'No records found';
        } else {
            recordsInfo.innerHTML = `Showing ${data.from} to ${data.to} of ${data.total} records`;
        }
    }

    function renderTable(records) {
        const tbody = document.getElementById('dataRecordsTable');
        
        if (records.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="12" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            ${currentSearch ? 'No records found matching your search.' : 'No data records found. Submit your first record above!'}
                        </div>
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = records.map(record => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">${record.id}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">${record.integer_field_1}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">${record.integer_field_2}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">${record.integer_field_3}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">${record.integer_field_4}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm">
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">${record.selector_field_1}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm">
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">${record.selector_field_2}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm">
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">${record.selector_field_3}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm">
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">${record.selector_field_4}</span>
                </td>
                <td class="px-4 py-3 text-sm">
                    <div class="max-w-xs truncate text-gray-700" title="${record.comment_field_1}">${record.comment_field_1}</div>
                </td>
                <td class="px-4 py-3 text-sm">
                    <div class="max-w-xs truncate text-gray-700" title="${record.comment_field_2}">${record.comment_field_2}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                    ${new Date(record.created_at).toLocaleDateString()}
                </td>
            </tr>
        `).join('');
    }

    function renderPagination(data) {
        const paginationInfo = document.getElementById('paginationInfo');
        const paginationLinks = document.getElementById('paginationLinks');
        
        // Pagination info
        if (data.total > 0) {
            paginationInfo.innerHTML = `Page ${data.current_page} of ${data.last_page} (${data.total} total records)`;
        } else {
            paginationInfo.innerHTML = '';
        }
        
        // Pagination links
        if (data.last_page <= 1) {
            paginationLinks.innerHTML = '';
            return;
        }
        
        let links = '';
        
        // Previous button
        if (data.current_page > 1) {
            links += `<button onclick="loadDataRecords(${data.current_page - 1})" class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50">Previous</button>`;
        }
        
        // Page numbers
        const start = Math.max(1, data.current_page - 2);
        const end = Math.min(data.last_page, data.current_page + 2);
        
        for (let i = start; i <= end; i++) {
            const isActive = i === data.current_page;
            links += `<button onclick="loadDataRecords(${i})" class="px-3 py-1 text-sm ${isActive ? 'bg-green-500 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50'} rounded">${i}</button>`;
        }
        
        // Next button
        if (data.current_page < data.last_page) {
            links += `<button onclick="loadDataRecords(${data.current_page + 1})" class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50">Next</button>`;
        }
        
        paginationLinks.innerHTML = links;
    }
</script>
@endsection 