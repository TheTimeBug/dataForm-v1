<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Access Denied - DataForm Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <!-- Lock Icon -->
                <div class="mx-auto h-16 w-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8zm-8 0V7a4 4 0 018 0v4h-8z"></path>
                    </svg>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Access Denied</h1>
                <p class="text-gray-600 mb-6">You don't have permission to access this resource.</p>
                
                <!-- Error Details -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Insufficient Privileges
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>This action requires specific admin privileges that your account doesn't have.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button onclick="goBack()" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Go Back
                    </button>
                    
                    <button onclick="goToDashboard()" 
                        class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Go to Dashboard
                    </button>
                    
                    <button onclick="logout()" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Logout
                    </button>
                </div>
                
                <!-- Additional Info -->
                <div class="mt-8 text-xs text-gray-500 space-y-1">
                    <p>If you believe this is an error, please contact your system administrator.</p>
                    <p id="timestamp"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                goToDashboard();
            }
        }

        function goToDashboard() {
            // Check user type and redirect to appropriate dashboard
            const userData = JSON.parse(localStorage.getItem('user_data') || '{}');
            
            if (userData.role === 'admin') {
                window.location.href = '{{ route("admin.dashboard") }}';
            } else {
                window.location.href = '{{ route("user.data-submission") }}';
            }
        }

        function logout() {
            // Clear all authentication data
            localStorage.removeItem('admin_token');
            localStorage.removeItem('user_token');
            localStorage.removeItem('user_data');
            
            // Redirect to login page
            const userData = JSON.parse(localStorage.getItem('user_data') || '{}');
            if (userData.role === 'admin') {
                window.location.href = '{{ route("admin.login") }}';
            } else {
                window.location.href = '{{ route("user.login") }}';
            }
        }

        // Display current timestamp
        document.getElementById('timestamp').textContent = 'Time: ' + new Date().toLocaleString();
    </script>
</body>
</html> 