<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - DataForm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold text-gray-900">User Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span id="userName" class="text-gray-700"></span>
                    <button onclick="logout()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Add Data Record Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Add Data Record</h2>
            
            <form id="dataRecordForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Integer Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Integer Field 1</label>
                    <input type="number" name="integer_field_1" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Integer Field 2</label>
                    <input type="number" name="integer_field_2" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Integer Field 3</label>
                    <input type="number" name="integer_field_3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Integer Field 4</label>
                    <input type="number" name="integer_field_4" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Selector Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selector Field 1</label>
                    <select name="selector_field_1" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Option</option>
                        <option value="Option A">Option A</option>
                        <option value="Option B">Option B</option>
                        <option value="Option C">Option C</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selector Field 2</label>
                    <select name="selector_field_2" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Option</option>
                        <option value="Option X">Option X</option>
                        <option value="Option Y">Option Y</option>
                        <option value="Option Z">Option Z</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selector Field 3</label>
                    <select name="selector_field_3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Option</option>
                        <option value="Type 1">Type 1</option>
                        <option value="Type 2">Type 2</option>
                        <option value="Type 3">Type 3</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selector Field 4</label>
                    <select name="selector_field_4" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Option</option>
                        <option value="Category 1">Category 1</option>
                        <option value="Category 2">Category 2</option>
                        <option value="Category 3">Category 3</option>
                    </select>
                </div>

                <!-- Comment Fields -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment Field 1</label>
                    <textarea name="comment_field_1" required rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Enter your comment..."></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment Field 2</label>
                    <textarea name="comment_field_2" required rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Enter your comment..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="lg:col-span-4 flex justify-end">
                    <button type="submit" id="submitBtn"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 font-medium">
                        Submit Data
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Records Table -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Your Data Records</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Integer Fields</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selector Fields</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        </tr>
                    </thead>
                    <tbody id="dataRecordsTable" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Requests -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Requests</h2>
            <div id="editRequestsContainer">
                <!-- Edit requests will be loaded here -->
            </div>
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

    <div id="message" class="fixed top-4 right-4 p-4 rounded-lg shadow-lg hidden z-50"></div>

    <script>
        let currentEditRequestId = null;

        // Check authentication
        const token = localStorage.getItem('user_token');
        const userData = JSON.parse(localStorage.getItem('user_data') || '{}');

        if (!token) {
            window.location.href = '{{ route("user.login") }}';
        }

        // Set axios default header
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        // Display user name
        document.getElementById('userName').textContent = userData.name || 'User';

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDataRecords();
            loadEditRequests();
        });

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
                submitBtn.textContent = 'Submit Data';
                submitBtn.disabled = false;
            }
        });

        // Load data records
        async function loadDataRecords() {
            try {
                const response = await axios.get('/api/user/data-records?simple=true');
                const records = response.data;
                
                const tbody = document.getElementById('dataRecordsTable');
                tbody.innerHTML = records.map(record => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${record.integer_field_1}, ${record.integer_field_2}, ${record.integer_field_3}, ${record.integer_field_4}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${record.selector_field_1}, ${record.selector_field_2}, ${record.selector_field_3}, ${record.selector_field_4}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate">${record.comment_field_1}</div>
                            <div class="max-w-xs truncate">${record.comment_field_2}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${new Date(record.created_at).toLocaleDateString()}
                        </td>
                    </tr>
                `).join('');
                
            } catch (error) {
                console.error('Failed to load data records:', error);
                showMessage('Failed to load data records', 'error');
            }
        }

        // Load edit requests
        async function loadEditRequests() {
            try {
                const response = await axios.get('/api/user/edit-requests?simple=true');
                const editRequests = response.data;
                
                const container = document.getElementById('editRequestsContainer');
                
                if (editRequests.length === 0) {
                    container.innerHTML = '<p class="text-gray-500">No edit requests found.</p>';
                    return;
                }
                
                container.innerHTML = editRequests.map(request => `
                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-medium text-gray-800">Edit Request #${request.id}</h4>
                                <p class="text-sm text-gray-600">Status: <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Pending</span></p>
                                ${request.parent_id ? `<p class="text-sm text-gray-500">Original Data ID: ${request.parent_id}</p>` : ''}
                            </div>
                            <button onclick="openEditModal(${request.id}, ${JSON.stringify(request).replace(/"/g, '&quot;')})" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                                Edit
                            </button>
                        </div>
                        ${request.admin_notes ? `<p class="text-sm text-gray-600 mb-2"><strong>Admin Notes:</strong> ${request.admin_notes}</p>` : ''}
                        <p class="text-xs text-gray-500">Requested by: ${request.admin ? request.admin.name : 'Admin'}</p>
                    </div>
                `).join('');
                
            } catch (error) {
                console.error('Failed to load edit requests:', error);
                showMessage('Failed to load edit requests', 'error');
            }
        }

        // Open edit modal
        function openEditModal(editRequestId, dataRecord) {
            currentEditRequestId = editRequestId;
            
            const form = document.getElementById('editForm');
            form.innerHTML = `
                <!-- Integer Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Integer Field 1</label>
                    <input type="number" name="integer_field_1" value="${dataRecord.integer_field_1}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Integer Field 2</label>
                    <input type="number" name="integer_field_2" value="${dataRecord.integer_field_2}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Integer Field 3</label>
                    <input type="number" name="integer_field_3" value="${dataRecord.integer_field_3}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Integer Field 4</label>
                    <input type="number" name="integer_field_4" value="${dataRecord.integer_field_4}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Selector Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selector Field 1</label>
                    <select name="selector_field_1" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Option A" ${dataRecord.selector_field_1 === 'Option A' ? 'selected' : ''}>Option A</option>
                        <option value="Option B" ${dataRecord.selector_field_1 === 'Option B' ? 'selected' : ''}>Option B</option>
                        <option value="Option C" ${dataRecord.selector_field_1 === 'Option C' ? 'selected' : ''}>Option C</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selector Field 2</label>
                    <select name="selector_field_2" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Option X" ${dataRecord.selector_field_2 === 'Option X' ? 'selected' : ''}>Option X</option>
                        <option value="Option Y" ${dataRecord.selector_field_2 === 'Option Y' ? 'selected' : ''}>Option Y</option>
                        <option value="Option Z" ${dataRecord.selector_field_2 === 'Option Z' ? 'selected' : ''}>Option Z</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selector Field 3</label>
                    <select name="selector_field_3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Type 1" ${dataRecord.selector_field_3 === 'Type 1' ? 'selected' : ''}>Type 1</option>
                        <option value="Type 2" ${dataRecord.selector_field_3 === 'Type 2' ? 'selected' : ''}>Type 2</option>
                        <option value="Type 3" ${dataRecord.selector_field_3 === 'Type 3' ? 'selected' : ''}>Type 3</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selector Field 4</label>
                    <select name="selector_field_4" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Category 1" ${dataRecord.selector_field_4 === 'Category 1' ? 'selected' : ''}>Category 1</option>
                        <option value="Category 2" ${dataRecord.selector_field_4 === 'Category 2' ? 'selected' : ''}>Category 2</option>
                        <option value="Category 3" ${dataRecord.selector_field_4 === 'Category 3' ? 'selected' : ''}>Category 3</option>
                    </select>
                </div>

                <!-- Comment Fields -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment Field 1</label>
                    <textarea name="comment_field_1" required rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">${dataRecord.comment_field_1}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment Field 2</label>
                    <textarea name="comment_field_2" required rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">${dataRecord.comment_field_2}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="lg:col-span-4 flex justify-end space-x-4">
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
                loadDataRecords();
                loadEditRequests();
                
            } catch (error) {
                showMessage(error.response?.data?.message || 'Failed to update data record', 'error');
            } finally {
                updateBtn.textContent = 'Update Data';
                updateBtn.disabled = false;
            }
        }

        // Show message
        function showMessage(text, type) {
            const message = document.getElementById('message');
            message.textContent = text;
            message.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'
            }`;
            message.classList.remove('hidden');
            
            setTimeout(() => {
                message.classList.add('hidden');
            }, 5000);
        }

        // Logout function
        function logout() {
            localStorage.removeItem('user_token');
            localStorage.removeItem('user_data');
            window.location.href = '{{ route("user.login") }}';
        }
    </script>
</body>
</html> 