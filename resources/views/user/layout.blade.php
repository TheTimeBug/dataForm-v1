<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard') - DataForm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50 h-screen overflow-hidden">
    <div class="h-full flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b relative z-30 flex-shrink-0">
        <div class="flex justify-between items-center py-3 sm:py-4 px-4 sm:px-6">
            <div class="flex items-center">
                <button onclick="toggleSidebar()" class="mr-3 sm:mr-4 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">User Dashboard</h1>
            </div>
            <div class="flex items-center">
                <span id="userName" class="text-sm sm:text-base text-gray-700 truncate max-w-32 sm:max-w-none"></span>
            </div>
        </div>
    </header>

    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <!-- Main Layout with Sidebar -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Left Sidebar Navigation -->
        <nav id="sidebar" class="bg-white shadow-sm border-r fixed lg:relative z-50 lg:z-auto 
                                 w-44 lg:w-44 md:w-44 sm:w-44 
                                 h-screen lg:h-full 
                                 transform -translate-x-full lg:translate-x-0 
                                 transition-all duration-300 ease-in-out flex flex-col 
                                 top-0 lg:top-0 pt-16 lg:pt-0">
            <div class="p-2 sm:p-3 flex-1 overflow-y-auto">
                <div class="space-y-1 sm:space-y-2">
                    <a href="{{ route('user.data-submission') }}" 
                       class="nav-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200 
                              {{ request()->routeIs('user.data-submission') 
                                 ? 'bg-green-100 text-green-700 border-l-4 border-green-500' 
                                 : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="nav-text ml-3">Data Submission</span>
                    </a>
                    <a href="{{ route('user.edit-requests') }}" 
                       class="nav-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                              {{ request()->routeIs('user.edit-requests') 
                                 ? 'bg-green-100 text-green-700 border-l-4 border-green-500' 
                                 : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="nav-text ml-3">Edit Requests</span>
                    </a>
                    <a href="{{ route('user.profile') }}" 
                       class="nav-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                              {{ request()->routeIs('user.profile') 
                                 ? 'bg-green-100 text-green-700 border-l-4 border-green-500' 
                                 : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="nav-text ml-3">Profile</span>
                    </a>
                </div>
            </div>
            
            <!-- Logout at bottom -->
            <div class="p-2 sm:p-3 border-t bg-white flex-shrink-0">
                <button onclick="logout()" 
                        class="logout-btn w-full flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200 text-red-600 hover:bg-red-50 hover:text-red-700">
                    <svg class="w-5 h-5 logout-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="logout-text ml-3">Logout</span>
                </button>
            </div>
        </nav>

        <!-- Main Content -->
        <div id="mainContent" class="flex-1 p-4 sm:p-6 lg:p-8 transition-all duration-300 ease-in-out overflow-y-auto">
            @yield('content')
        </div>
    </div>

    <!-- Message Container -->
    <div id="message" class="fixed top-4 right-4 p-4 rounded-lg shadow-lg hidden z-50"></div>

    <script>
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

        // Show message function
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

        // Sidebar toggle functionality
        let sidebarCollapsed = false;
        let isMobile = false;

        // Check screen size and update mobile state
        function checkScreenSize() {
            isMobile = window.innerWidth < 1024; // lg breakpoint
            
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            if (isMobile) {
                // Mobile: Reset sidebar to default mobile state
                sidebar.classList.remove('w-16');
                sidebar.classList.add('w-44');
                resetSidebarItems(false); // Show text labels
                
                // Ensure sidebar is hidden initially on mobile
                if (!sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                }
                
                // Ensure overlay is hidden initially
                overlay.classList.add('hidden');
                
            } else {
                // Desktop: Remove mobile-specific classes
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            if (isMobile) {
                // Mobile: Toggle sidebar visibility with overlay
                const isHidden = sidebar.classList.contains('-translate-x-full');
                
                if (isHidden) {
                    // Show sidebar
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                } else {
                    // Hide sidebar
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                }
                
            } else {
                // Desktop: Toggle sidebar collapse
                sidebarCollapsed = !sidebarCollapsed;
                
                if (sidebarCollapsed) {
                    // Collapse sidebar
                    sidebar.classList.remove('w-44');
                    sidebar.classList.add('w-16');
                    
                    collapseSidebarItems();
                    
                } else {
                    // Expand sidebar
                    sidebar.classList.remove('w-16');
                    sidebar.classList.add('w-44');
                    
                    resetSidebarItems(false);
                }
            }
        }

        function collapseSidebarItems() {
            const navTexts = document.querySelectorAll('.nav-text');
            const logoutText = document.querySelector('.logout-text');
            
            // Hide text labels
            navTexts.forEach(text => {
                text.classList.add('hidden');
            });
            
            if (logoutText) {
                logoutText.classList.add('hidden');
            }
            
            // Center icons and adjust padding
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.classList.add('justify-center');
                item.classList.remove('px-3');
                item.classList.add('px-2');
            });
            
            const logoutBtn = document.querySelector('.logout-btn');
            if (logoutBtn) {
                logoutBtn.classList.add('justify-center');
                logoutBtn.classList.remove('px-3');
                logoutBtn.classList.add('px-2');
            }
        }

        function resetSidebarItems(hideText) {
            const navTexts = document.querySelectorAll('.nav-text');
            const logoutText = document.querySelector('.logout-text');
            
            // Show/hide text labels
            navTexts.forEach(text => {
                if (hideText) {
                    text.classList.add('hidden');
                } else {
                    text.classList.remove('hidden');
                }
            });
            
            if (logoutText) {
                if (hideText) {
                    logoutText.classList.add('hidden');
                } else {
                    logoutText.classList.remove('hidden');
                }
            }
            
            // Reset alignment and padding
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.classList.remove('justify-center');
                item.classList.remove('px-2');
                item.classList.add('px-3');
            });
            
            const logoutBtn = document.querySelector('.logout-btn');
            if (logoutBtn) {
                logoutBtn.classList.remove('justify-center');
                logoutBtn.classList.remove('px-2');
                logoutBtn.classList.add('px-3');
            }
        }

        // Close sidebar when clicking overlay (mobile)
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            if (isMobile) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });

        // Handle window resize
        window.addEventListener('resize', checkScreenSize);
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkScreenSize();
        });
    </script>

    @yield('scripts')
    </div>
</body>
</html> 