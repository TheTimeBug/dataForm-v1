<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - DataForm</title>
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
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                </div>
                <div class="flex items-center">
                    <span id="adminName" class="text-sm sm:text-base text-gray-700 truncate max-w-32 sm:max-w-none"></span>
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
                        <a href="{{ route('admin.dashboard') }}" 
                           class="nav-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200 
                                  {{ request()->routeIs('admin.dashboard') 
                                     ? 'bg-blue-100 text-blue-700 border-l-4 border-blue-500' 
                                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 1v4"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 1v4"></path>
                            </svg>
                            <span class="nav-text ml-3">Dashboard</span>
                        </a>
                        <!-- Users Menu with Submenu -->
                        <div class="users-menu">
                            <button onclick="toggleUsersSubmenu()" 
                                    class="nav-item w-full flex items-center justify-between px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                           {{ request()->routeIs('admin.users.*') 
                                              ? 'bg-blue-100 text-blue-700 border-l-4 border-blue-500' 
                                              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                                    </svg>
                                    <span class="nav-text ml-3">Users</span>
                                </div>
                                <svg id="usersChevron" class="w-4 h-4 nav-icon transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            
                            <!-- Users Submenu -->
                            <div id="usersSubmenu" class="users-submenu hidden ml-6 mt-1 space-y-1">
                                <a href="{{ route('admin.users.admins') }}" 
                                   class="submenu-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                          {{ request()->routeIs('admin.users.admins') 
                                             ? 'bg-blue-50 text-blue-600' 
                                             : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                                    <svg class="w-4 h-4 submenu-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span class="submenu-text ml-2">Admins</span>
                                </a>
                                
                                <a href="{{ route('admin.users.users') }}" 
                                   class="submenu-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                          {{ request()->routeIs('admin.users.users') 
                                             ? 'bg-blue-50 text-blue-600' 
                                             : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                                    <svg class="w-4 h-4 submenu-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="submenu-text ml-2">Users</span>
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('admin.submissions') }}" 
                           class="nav-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                  {{ request()->routeIs('admin.submissions') 
                                     ? 'bg-blue-100 text-blue-700 border-l-4 border-blue-500' 
                                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="w-5 h-5 nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="nav-text ml-3">Submissions</span>
                        </a>
                        <!-- Edit Requests Menu with Submenu -->
                        <div class="edit-requests-menu">
                            <button onclick="toggleEditRequestsSubmenu()" 
                                    class="nav-item w-full flex items-center justify-between px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                           {{ request()->routeIs('admin.edit-requests.*') 
                                              ? 'bg-blue-100 text-blue-700 border-l-4 border-blue-500' 
                                              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="nav-text ml-3">Rollback</span>
                                </div>
                                <svg id="editRequestsChevron" class="w-4 h-4 nav-icon transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            
                            <!-- Edit Requests Submenu -->
                            <div id="editRequestsSubmenu" class="edit-requests-submenu hidden ml-6 mt-1 space-y-1">
                                <a href="{{ route('admin.edit-requests.pending') }}" 
                                   class="submenu-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                          {{ request()->routeIs('admin.edit-requests.pending') 
                                             ? 'bg-blue-50 text-blue-600' 
                                             : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                                    <svg class="w-4 h-4 submenu-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="submenu-text ml-2">Pending</span>
                                </a>
                                
                                <a href="{{ route('admin.edit-requests.history') }}" 
                                   class="submenu-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                          {{ request()->routeIs('admin.edit-requests.history') 
                                             ? 'bg-blue-50 text-blue-600' 
                                             : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                                    <svg class="w-4 h-4 submenu-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="submenu-text ml-2">History</span>
                                </a>
                            </div>
                        </div>
                        <!-- Library Menu with Submenu -->
                        <div class="library-menu">
                            <button onclick="toggleLibrarySubmenu()" 
                                    class="nav-item w-full flex items-center justify-between px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                           {{ request()->routeIs('admin.library*') 
                                              ? 'bg-blue-100 text-blue-700 border-l-4 border-blue-500' 
                                              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 nav-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"></path>
                                    </svg>
                                    <span class="nav-text ml-3">Library</span>
                                </div>
                                <svg id="libraryChevron" class="w-4 h-4 nav-icon transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            
                            <!-- Library Submenu -->
                            <div id="librarySubmenu" class="library-submenu hidden ml-6 mt-1 space-y-1">
                                <a href="{{ route('admin.library.divisions') }}" 
                                   class="submenu-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                          {{ request()->routeIs('admin.library.divisions') 
                                             ? 'bg-blue-50 text-blue-600' 
                                             : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                                    <svg class="w-4 h-4 submenu-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                    </svg>
                                    <span class="submenu-text ml-2">Divisions</span>
                                </a>
                                
                                <a href="{{ route('admin.library.districts') }}" 
                                   class="submenu-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                          {{ request()->routeIs('admin.library.districts') 
                                             ? 'bg-blue-50 text-blue-600' 
                                             : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                                    <svg class="w-4 h-4 submenu-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="submenu-text ml-2">Districts</span>
                                </a>
                                
                                <a href="{{ route('admin.library.upazilas') }}" 
                                   class="submenu-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                          {{ request()->routeIs('admin.library.upazilas') 
                                             ? 'bg-blue-50 text-blue-600' 
                                             : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                                    <svg class="w-4 h-4 submenu-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h1a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="submenu-text ml-2">Upazilas</span>
                                </a>
                                
                                <a href="{{ route('admin.library.mouzas') }}" 
                                   class="submenu-item flex items-center px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200
                                          {{ request()->routeIs('admin.library.mouzas') 
                                             ? 'bg-blue-50 text-blue-600' 
                                             : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                                    <svg class="w-4 h-4 submenu-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="submenu-text ml-2">Mouzas</span>
                                </a>
                            </div>
                        </div>
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
    </div>

    <!-- Message Container -->
    <div id="message" class="fixed top-4 right-4 p-4 rounded-lg shadow-lg hidden z-50"></div>

    <script>
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
            localStorage.removeItem('admin_token');
            localStorage.removeItem('user_data');
            window.location.href = '{{ route("admin.login") }}';
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
                    
                    expandSidebarItems();
                }
            }
        }

        function collapseSidebarItems() {
            // Hide navigation text labels
            const navTexts = document.querySelectorAll('.nav-text, .logout-text, .submenu-text');
            navTexts.forEach(text => {
                text.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    text.classList.add('hidden');
                }, 150);
            });
            
            // Hide submenus and chevrons
            const librarySubmenu = document.getElementById('librarySubmenu');
            const libraryChevron = document.getElementById('libraryChevron');
            const editRequestsSubmenu = document.getElementById('editRequestsSubmenu');
            const editRequestsChevron = document.getElementById('editRequestsChevron');
            const usersSubmenu = document.getElementById('usersSubmenu');
            const usersChevron = document.getElementById('usersChevron');
            
            if (librarySubmenu) librarySubmenu.classList.add('hidden');
            if (libraryChevron) libraryChevron.classList.add('hidden');
            if (editRequestsSubmenu) editRequestsSubmenu.classList.add('hidden');
            if (editRequestsChevron) editRequestsChevron.classList.add('hidden');
            if (usersSubmenu) usersSubmenu.classList.add('hidden');
            if (usersChevron) usersChevron.classList.add('hidden');
            
            // Adjust navigation items for collapsed state
            const navItems = document.querySelectorAll('.nav-item, .logout-btn');
            navItems.forEach(item => {
                item.classList.add('justify-center');
                item.classList.remove('justify-start');
            });
        }

        function expandSidebarItems() {
            // Show navigation text labels
            const navTexts = document.querySelectorAll('.nav-text, .logout-text, .submenu-text');
            navTexts.forEach(text => {
                text.classList.remove('hidden');
                setTimeout(() => {
                    text.classList.remove('opacity-0', 'scale-95');
                }, 50);
            });
            
            // Show chevrons
            const libraryChevron = document.getElementById('libraryChevron');
            const editRequestsChevron = document.getElementById('editRequestsChevron');
            const usersChevron = document.getElementById('usersChevron');
            
            if (libraryChevron) libraryChevron.classList.remove('hidden');
            if (editRequestsChevron) editRequestsChevron.classList.remove('hidden');
            if (usersChevron) usersChevron.classList.remove('hidden');
            
            // Restore navigation items alignment
            const navItems = document.querySelectorAll('.nav-item, .logout-btn');
            navItems.forEach(item => {
                item.classList.remove('justify-center');
                item.classList.add('justify-start');
            });
            
            // Auto-expand submenus if on those pages
            if (window.location.pathname.includes('/admin/library/')) {
                expandLibrarySubmenu();
            }
            if (window.location.pathname.includes('/admin/edit-requests/')) {
                expandEditRequestsSubmenu();
            }
            if (window.location.pathname.includes('/admin/users/')) {
                expandUsersSubmenu();
            }
        }

        function resetSidebarItems(collapsed = false) {
            if (collapsed) {
                collapseSidebarItems();
            } else {
                expandSidebarItems();
            }
        }

        // Toggle library submenu
        function toggleLibrarySubmenu() {
            const submenu = document.getElementById('librarySubmenu');
            const chevron = document.getElementById('libraryChevron');
            
            if (submenu.classList.contains('hidden')) {
                expandLibrarySubmenu();
            } else {
                submenu.classList.add('hidden');
                chevron.classList.remove('rotate-90');
            }
        }

        function expandLibrarySubmenu() {
            if (!sidebarCollapsed) {
                const submenu = document.getElementById('librarySubmenu');
                const chevron = document.getElementById('libraryChevron');
                submenu.classList.remove('hidden');
                chevron.classList.add('rotate-90');
            }
        }

        // Toggle edit requests submenu
        function toggleEditRequestsSubmenu() {
            const submenu = document.getElementById('editRequestsSubmenu');
            const chevron = document.getElementById('editRequestsChevron');
            
            if (submenu.classList.contains('hidden')) {
                expandEditRequestsSubmenu();
            } else {
                submenu.classList.add('hidden');
                chevron.classList.remove('rotate-90');
            }
        }

        function expandEditRequestsSubmenu() {
            if (!sidebarCollapsed) {
                const submenu = document.getElementById('editRequestsSubmenu');
                const chevron = document.getElementById('editRequestsChevron');
                submenu.classList.remove('hidden');
                chevron.classList.add('rotate-90');
            }
        }

        // Toggle users submenu
        function toggleUsersSubmenu() {
            const submenu = document.getElementById('usersSubmenu');
            const chevron = document.getElementById('usersChevron');
            
            if (submenu.classList.contains('hidden')) {
                expandUsersSubmenu();
            } else {
                submenu.classList.add('hidden');
                chevron.classList.remove('rotate-90');
            }
        }

        function expandUsersSubmenu() {
            if (!sidebarCollapsed) {
                const submenu = document.getElementById('usersSubmenu');
                const chevron = document.getElementById('usersChevron');
                submenu.classList.remove('hidden');
                chevron.classList.add('rotate-90');
            }
        }

        // Auto-expand appropriate submenu on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkScreenSize();
            
            // Auto-expand submenus based on current page
            if (window.location.pathname.includes('/admin/library/')) {
                expandLibrarySubmenu();
            }
            if (window.location.pathname.includes('/admin/edit-requests/')) {
                expandEditRequestsSubmenu();
            }
            if (window.location.pathname.includes('/admin/users/')) {
                expandUsersSubmenu();
            }
            
            // Listen for window resize
            window.addEventListener('resize', function() {
                setTimeout(checkScreenSize, 100);
            });
        });
    </script>
</body>
</html> 