@extends('user.layout')

@section('title', 'Edit Requests')

@section('content')
    <!-- Edit Requests -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Edit Requests</h2>
            <button onclick="loadEditRequests()" 
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
                <input type="text" id="searchInput" placeholder="Search edit requests..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex gap-3">
                <select id="perPageSelect" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>

        <!-- Edit Requests Table -->
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
                        <th class="px-3 py-3">Date</th>
                    </tr>
                </thead>
                <tbody id="editRequestsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Table rows will be populated here -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Edit Requests Found</h3>
            <p class="text-gray-500">No edit requests match your search criteria.</p>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="mt-6">
            <!-- Pagination will be populated here -->
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-4xl max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Edit Data Record</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="editForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Form fields will be populated dynamically -->
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let currentEditRequestId = null;
    let currentPage = 1;
    let currentSearch = '';
    let currentPerPage = 10;
    let searchTimeout;

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadEditRequests();
        setupEventListeners();
    });

    function setupEventListeners() {
        // Search input with debounce
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = e.target.value;
                currentPage = 1;
                loadEditRequests();
            }, 300);
        });

        // Per page selector
        document.getElementById('perPageSelect').addEventListener('change', function(e) {
            currentPerPage = parseInt(e.target.value);
            currentPage = 1;
            loadEditRequests();
        });
    }

    // Load edit requests with pagination and search
    async function loadEditRequests(page = 1) {
        try {
            currentPage = page;
            const params = new URLSearchParams({
                page: currentPage,
                per_page: currentPerPage,
                search: currentSearch
            });

            const response = await axios.get(`/api/user/edit-requests?${params}`);
            const data = response.data;
            
            displayEditRequests(data.data);
            displayPagination(data);
            
        } catch (error) {
            console.error('Failed to load edit requests:', error);
            showMessage('Failed to load edit requests', 'error');
        }
    }

    function displayEditRequests(editRequests) {
        const tbody = document.getElementById('editRequestsTableBody');
        const emptyState = document.getElementById('emptyState');
        
        if (editRequests.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
                 tbody.innerHTML = editRequests.map(request => {
             const statusColors = {
                 'pending': 'bg-yellow-100 text-yellow-800',
                 'completed': 'bg-green-100 text-green-800'
             };
             
             return `
                 <!-- Main Data Row -->
                 <tr class="hover:bg-gray-50">
                     <td class="px-3 py-3 text-xs font-medium text-gray-900">#${request.id}</td>
                     <td class="px-3 py-3">
                         <span class="px-2 py-1 text-xs font-medium rounded-full ${statusColors[request.status] || statusColors.pending}">
                             ${request.status.charAt(0).toUpperCase() + request.status.slice(1)}
                         </span>
                     </td>
                     <td class="px-3 py-3 text-xs text-gray-600">${request.data_record.id}</td>
                     <td class="px-3 py-3">
                         <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${request.data_record.integer_field_1}</span>
                     </td>
                     <td class="px-3 py-3">
                         <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${request.data_record.integer_field_2}</span>
                     </td>
                     <td class="px-3 py-3">
                         <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${request.data_record.integer_field_3}</span>
                     </td>
                     <td class="px-3 py-3">
                         <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">${request.data_record.integer_field_4}</span>
                     </td>
                     <td class="px-3 py-3">
                         <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${request.data_record.selector_field_1}</span>
                     </td>
                     <td class="px-3 py-3">
                         <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${request.data_record.selector_field_2}</span>
                     </td>
                     <td class="px-3 py-3">
                         <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${request.data_record.selector_field_3}</span>
                     </td>
                     <td class="px-3 py-3">
                         <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">${request.data_record.selector_field_4}</span>
                     </td>
                     <td class="px-3 py-3 text-xs text-gray-600 max-w-32 truncate" title="${request.data_record.comment_field_1}">
                         ${request.data_record.comment_field_1}
                     </td>
                     <td class="px-3 py-3 text-xs text-gray-600 max-w-32 truncate" title="${request.data_record.comment_field_2}">
                         ${request.data_record.comment_field_2}
                     </td>
                     <td class="px-3 py-3 text-xs text-gray-500">${new Date(request.created_at).toLocaleDateString()}</td>
                 </tr>
                 
                 <!-- Admin Notes & Actions Row -->
                 <tr class="border-b border-gray-200" style="border-top: none;">
                     <td colspan="14" class="px-3 py-2 bg-gray-25">
                         <div class="flex flex-col sm:flex-row justify-between items-start gap-3 border-l-4 border-gray-300 pl-4">
                             <div class="flex-1">
                                 ${request.admin_notes ? `
                                     <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-2">
                                         <p class="text-xs text-blue-800 font-medium mb-1">Admin Notes:</p>
                                         <p class="text-xs text-blue-700">${request.admin_notes}</p>
                                     </div>
                                 ` : `
                                     <div class="text-xs text-gray-500 italic mb-2">No admin notes</div>
                                 `}
                                 <p class="text-xs text-gray-600">
                                     <strong>Requested by:</strong> ${request.admin.name} on ${new Date(request.created_at).toLocaleDateString('en-US', {
                                         year: 'numeric',
                                         month: 'short',
                                         day: 'numeric',
                                         hour: '2-digit',
                                         minute: '2-digit'
                                     })}
                                 </p>
                             </div>
                             <div class="flex-shrink-0">
                                 ${request.status === 'pending' ? `
                                     <button onclick="openEditModal(${request.id}, ${JSON.stringify(request.data_record).replace(/"/g, '&quot;')})" 
                                         class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center text-sm">
                                         <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                         </svg>
                                         Edit
                                     </button>
                                 ` : `
                                     <div class="flex items-center text-green-600">
                                         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                         </svg>
                                         <span class="text-sm font-medium">Completed</span>
                                     </div>
                                 `}
                             </div>
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
                <button onclick="loadEditRequests(${data.current_page - 1})" 
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
                    <button onclick="loadEditRequests(${i})" 
                        class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        ${i}
                    </button>
                `;
            }
        }
        
        // Next button
        if (data.current_page < data.last_page) {
            paginationHTML += `
                <button onclick="loadEditRequests(${data.current_page + 1})" 
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

    // Open edit modal
    function openEditModal(editRequestId, dataRecord) {
        currentEditRequestId = editRequestId;
        
        const form = document.getElementById('editForm');
        form.innerHTML = `
            <!-- Integer Fields -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Int 1</label>
                <input type="number" name="integer_field_1" value="${dataRecord.integer_field_1}" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Int 2</label>
                <input type="number" name="integer_field_2" value="${dataRecord.integer_field_2}" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Int 3</label>
                <input type="number" name="integer_field_3" value="${dataRecord.integer_field_3}" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Int 4</label>
                <input type="number" name="integer_field_4" value="${dataRecord.integer_field_4}" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Selector Fields -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sel 1</label>
                <select name="selector_field_1" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="Option A" ${dataRecord.selector_field_1 === 'Option A' ? 'selected' : ''}>Option A</option>
                    <option value="Option B" ${dataRecord.selector_field_1 === 'Option B' ? 'selected' : ''}>Option B</option>
                    <option value="Option C" ${dataRecord.selector_field_1 === 'Option C' ? 'selected' : ''}>Option C</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sel 2</label>
                <select name="selector_field_2" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="Option X" ${dataRecord.selector_field_2 === 'Option X' ? 'selected' : ''}>Option X</option>
                    <option value="Option Y" ${dataRecord.selector_field_2 === 'Option Y' ? 'selected' : ''}>Option Y</option>
                    <option value="Option Z" ${dataRecord.selector_field_2 === 'Option Z' ? 'selected' : ''}>Option Z</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sel 3</label>
                <select name="selector_field_3" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="Type 1" ${dataRecord.selector_field_3 === 'Type 1' ? 'selected' : ''}>Type 1</option>
                    <option value="Type 2" ${dataRecord.selector_field_3 === 'Type 2' ? 'selected' : ''}>Type 2</option>
                    <option value="Type 3" ${dataRecord.selector_field_3 === 'Type 3' ? 'selected' : ''}>Type 3</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sel 4</label>
                <select name="selector_field_4" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="Category 1" ${dataRecord.selector_field_4 === 'Category 1' ? 'selected' : ''}>Category 1</option>
                    <option value="Category 2" ${dataRecord.selector_field_4 === 'Category 2' ? 'selected' : ''}>Category 2</option>
                    <option value="Category 3" ${dataRecord.selector_field_4 === 'Category 3' ? 'selected' : ''}>Category 3</option>
                </select>
            </div>

            <!-- Comment Fields -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Comment 1</label>
                <input type="text" name="comment_field_1" value="${dataRecord.comment_field_1}" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Comment 2</label>
                <input type="text" name="comment_field_2" value="${dataRecord.comment_field_2}" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Submit Button -->
            <div class="lg:col-span-4 flex justify-end space-x-4 pt-4">
                <button type="button" onclick="closeEditModal()" 
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                    Cancel
                </button>
                <button type="submit" id="updateBtn"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Update Data
                </button>
            </div>
        `;
        
        // Add form submit handler
        form.addEventListener('submit', updateDataRecord);
        
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    // Close edit modal
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
        currentEditRequestId = null;
    }

    // Update data record
    async function updateDataRecord(e) {
        e.preventDefault();
        
        const updateBtn = document.getElementById('updateBtn');
        updateBtn.textContent = 'Updating...';
        updateBtn.disabled = true;
        
        try {
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());
            
            // Convert integer fields to numbers
            ['integer_field_1', 'integer_field_2', 'integer_field_3', 'integer_field_4'].forEach(field => {
                data[field] = parseInt(data[field]);
            });
            
            await axios.put(`/api/user/edit-requests/${currentEditRequestId}`, data);
            
            showMessage('Data record updated successfully!', 'success');
            closeEditModal();
            loadEditRequests(currentPage);
            
        } catch (error) {
            showMessage(error.response?.data?.message || 'Failed to update data record', 'error');
        } finally {
            updateBtn.textContent = 'Update Data';
            updateBtn.disabled = false;
        }
    }
</script>
@endsection 