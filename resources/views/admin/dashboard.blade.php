<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DataForm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span id="adminName" class="text-gray-700"></span>
                    <button onclick="logout()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Navigation Tabs -->
        <div class="mb-8">
            <nav class="flex space-x-8">
                <button onclick="showTab('users')" id="usersTab" class="tab-button active">
                    Add Users
                </button>
                <button onclick="showTab('submissions')" id="submissionsTab" class="tab-button">
                    View Submissions
                </button>
                <button onclick="showTab('editRequests')" id="editRequestsTab" class="tab-button">
                    Edit Requests
                </button>
            </nav>
        </div>

        <!-- Add Users Tab -->
        <div id="usersContent" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Add User Form -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Add New User</h2>
                    
                    <form id="addUserForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" required minlength="8"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select name="role" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        
                        <button type="submit" id="addUserBtn"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium">
                            Add User
                        </button>
                    </form>
                </div>

                <!-- Users List -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">All Users</h2>
                    <div id="usersList" class="space-y-3">
                        <!-- Users will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Submissions Tab -->
        <div id="submissionsContent" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Data Submissions</h2>
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
                            <!-- Submissions will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Requests Tab -->
        <div id="editRequestsContent" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Requests</h2>
                <div id="editRequestsList" class="space-y-4">
                    <!-- Edit requests will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Send for Edit Modal -->
    <div id="sendEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Send for Edit</h3>
                <button onclick="closeSendEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="sendEditForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                    <textarea name="admin_notes" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Add notes for the user about what needs to be edited..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeSendEditModal()" 
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit" id="sendEditBtn"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                        Send for Edit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="message" class="fixed top-4 right-4 p-4 rounded-lg shadow-lg hidden z-50"></div>

    <script>
        let currentDataRecordId = null;

        // Check authentication
        const token = localStorage.getItem('admin_token');
        const userData = JSON.parse(localStorage.getItem('user_data') || '{}');

        if (!token) {
            window.location.href = '{{ route("admin.login") }}';
        }

        // Set axios default header
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        // Display admin name
        document.getElementById('adminName').textContent = userData.name || 'Admin';

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
            loadSubmissions();
            loadEditRequests();
        });

        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName + 'Content').classList.remove('hidden');
            document.getElementById(tabName + 'Tab').classList.add('active');
        }

        // Add user form
        document.getElementById('addUserForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const addUserBtn = document.getElementById('addUserBtn');
            addUserBtn.textContent = 'Adding User...';
            addUserBtn.disabled = true;
            
            try {
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                
                await axios.post('/api/admin/users', data);
                
                showMessage('User added successfully!', 'success');
                this.reset();
                loadUsers();
                
            } catch (error) {
                const message = error.response?.data?.message || 'Failed to add user';
                showMessage(message, 'error');
            } finally {
                addUserBtn.textContent = 'Add User';
                addUserBtn.disabled = false;
            }
        });

        // Load users
        async function loadUsers() {
            try {
                const response = await axios.get('/api/admin/users');
                const users = response.data;
                
                const usersList = document.getElementById('usersList');
                usersList.innerHTML = users.map(user => `
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">${user.name}</p>
                            <p class="text-sm text-gray-600">${user.email}</p>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs ${user.role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'}">
                            ${user.role}
                        </span>
                    </div>
                `).join('');
                
            } catch (error) {
                console.error('Failed to load users:', error);
            }
        }

        // Load submissions
        async function loadSubmissions() {
            try {
                const response = await axios.get('/api/admin/submissions');
                const submissions = response.data;
                
                const tbody = document.getElementById('submissionsTable');
                tbody.innerHTML = submissions.map(submission => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${submission.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${submission.user.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${submission.integer_field_1}, ${submission.integer_field_2}, ${submission.integer_field_3}, ${submission.integer_field_4}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${submission.selector_field_1}, ${submission.selector_field_2}, ${submission.selector_field_3}, ${submission.selector_field_4}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate">${submission.comment_field_1}</div>
                            <div class="max-w-xs truncate">${submission.comment_field_2}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${new Date(submission.created_at).toLocaleDateString()}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="openSendEditModal(${submission.id})" 
                                class="text-blue-600 hover:text-blue-900">Send for Edit</button>
                        </td>
                    </tr>
                `).join('');
                
            } catch (error) {
                console.error('Failed to load submissions:', error);
            }
        }

        // Load edit requests
        async function loadEditRequests() {
            try {
                const response = await axios.get('/api/admin/edit-requests');
                const editRequests = response.data;
                
                const container = document.getElementById('editRequestsList');
                
                if (editRequests.length === 0) {
                    container.innerHTML = '<p class="text-gray-500">No edit requests found.</p>';
                    return;
                }
                
                container.innerHTML = editRequests.map(request => `
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-medium text-gray-800">Edit Request #${request.id}</h4>
                                <p class="text-sm text-gray-600">User: ${request.user.name}</p>
                                <p class="text-sm text-gray-600">Data Record ID: ${request.data_record_id}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs ${request.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">
                                ${request.status}
                            </span>
                        </div>
                        ${request.admin_notes ? `<p class="text-sm text-gray-600 mb-2"><strong>Admin Notes:</strong> ${request.admin_notes}</p>` : ''}
                        <p class="text-xs text-gray-500">Created: ${new Date(request.created_at).toLocaleDateString()}</p>
                    </div>
                `).join('');
                
            } catch (error) {
                console.error('Failed to load edit requests:', error);
            }
        }

        // Open send edit modal
        function openSendEditModal(dataRecordId) {
            currentDataRecordId = dataRecordId;
            document.getElementById('sendEditModal').classList.remove('hidden');
            document.getElementById('sendEditModal').classList.add('flex');
        }

        // Close send edit modal
        function closeSendEditModal() {
            document.getElementById('sendEditModal').classList.add('hidden');
            document.getElementById('sendEditModal').classList.remove('flex');
            currentDataRecordId = null;
            document.getElementById('sendEditForm').reset();
        }

        // Send edit form
        document.getElementById('sendEditForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const sendEditBtn = document.getElementById('sendEditBtn');
            sendEditBtn.textContent = 'Sending...';
            sendEditBtn.disabled = true;
            
            try {
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                data.data_record_id = currentDataRecordId;
                
                await axios.post('/api/admin/send-for-edit', data);
                
                showMessage('Edit request sent successfully!', 'success');
                closeSendEditModal();
                loadEditRequests();
                
            } catch (error) {
                const message = error.response?.data?.message || 'Failed to send edit request';
                showMessage(message, 'error');
            } finally {
                sendEditBtn.textContent = 'Send for Edit';
                sendEditBtn.disabled = false;
            }
        });

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
            localStorage.removeItem('admin_token');
            localStorage.removeItem('user_data');
            window.location.href = '{{ route("admin.login") }}';
        }
    </script>

    <style>
        .tab-button {
            @apply py-2 px-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 transition duration-200;
        }
        .tab-button.active {
            @apply text-blue-600 border-blue-600;
        }
    </style>
</body>
</html> 