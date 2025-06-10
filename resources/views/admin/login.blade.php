<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DataForm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Admin Login</h1>
            <p class="text-gray-600">Sign in to your admin account</p>
        </div>

        <form id="adminLoginForm" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Enter your email">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Enter your password">
            </div>

            <button type="submit" id="loginBtn"
                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium">
                Sign In
            </button>
        </form>

        <div id="errorMessage" class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg hidden"></div>
        <div id="successMessage" class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg hidden"></div>

        <div class="mt-6 text-center">
            <a href="{{ route('user.login') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                User Login →
            </a>
        </div>
    </div>

    <script>
        document.getElementById('adminLoginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const loginBtn = document.getElementById('loginBtn');
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');
            
            // Hide previous messages
            errorMessage.classList.add('hidden');
            successMessage.classList.add('hidden');
            
            // Show loading state
            loginBtn.textContent = 'Signing In...';
            loginBtn.disabled = true;
            
            try {
                const formData = new FormData(this);
                const response = await axios.post('/api/admin/login', {
                    email: formData.get('email'),
                    password: formData.get('password')
                });
                
                // Store token
                localStorage.setItem('admin_token', response.data.access_token);
                localStorage.setItem('user_data', JSON.stringify(response.data.user));
                
                successMessage.textContent = 'Login successful! Redirecting...';
                successMessage.classList.remove('hidden');
                
                // Redirect to admin dashboard
                setTimeout(() => {
                    window.location.href = '{{ route("admin.dashboard") }}';
                }, 1000);
                
            } catch (error) {
                const message = error.response?.data?.error || 'Login failed. Please try again.';
                errorMessage.textContent = message;
                errorMessage.classList.remove('hidden');
            } finally {
                loginBtn.textContent = 'Sign In';
                loginBtn.disabled = false;
            }
        });
    </script>
</body>
</html> 