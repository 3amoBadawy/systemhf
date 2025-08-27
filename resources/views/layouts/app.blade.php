<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $businessName)</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-900 antialiased">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" onclick="closeSidebar()"></div>
    
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-50 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left side - Logo and Menu Toggle -->
                <div class="flex items-center">
                    <button id="sidebar-toggle" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <div class="flex items-center mr-4 lg:mr-0">
                        <div class="flex-shrink-0">
                            @if($logoUrl)
                                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                    <img src="{{ $logoUrl }}" alt="شعار الأعمال" class="h-8 w-auto">
                                    <h1 class="text-xl font-bold text-blue-600">{{ $businessName }}</h1>
                                </div>
                            @else
                                <h1 class="text-xl font-bold text-blue-600">{{ $businessName }}</h1>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Right side - User Menu -->
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <!-- Notifications -->
                    <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md relative">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 rtl:space-x-reverse p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                            <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'User' }}</span>
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">الملف الشخصي</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">الإعدادات</a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-16 right-0 h-full w-64 bg-white border-l border-gray-200 mobile-sidebar lg:translate-x-0 z-30 transition-transform duration-300 ease-in-out">
        <div class="h-full flex flex-col">
            <!-- Sidebar Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">القائمة الرئيسية</h2>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    </svg>
                    <span>لوحة التحكم</span>
                </a>
                
                <!-- Customers -->
                <a href="{{ route('customers.index') }}" class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <span>العملاء</span>
                </a>
                
                <!-- Products -->
                <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>المنتجات</span>
                </a>
                
                <!-- Media -->
                <a href="{{ route('media.index') }}" class="nav-item {{ request()->routeIs('media.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>الوسائط</span>
                </a>
                
                <!-- Invoices -->
                <a href="{{ route('invoices.index') }}" class="nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>الفواتير</span>
                </a>
                
                <!-- Payments -->
                <a href="{{ route('payments.index') }}" class="nav-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span>المدفوعات</span>
                </a>
                
                <!-- Categories -->
                <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>الفئات</span>
                </a>
                
                <!-- Employees -->
                <a href="{{ route('employees.index') }}" class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <span>الموظفين</span>
                </a>
                
                <!-- Attendance -->
                <a href="{{ route('attendance.index') }}" class="nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>الحضور والانصراف</span>
                </a>
                
                <!-- Salaries -->
                <a href="{{ route('salary.index') }}" class="nav-item {{ request()->routeIs('salary.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>الرواتب</span>
                </a>
                
                <!-- Attendance Kiosk -->
                <a href="{{ route('attendance.kiosk') }}" class="nav-item {{ request()->routeIs('attendance.kiosk') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>كشك الحضور</span>
                </a>
                
                <!-- Suppliers -->
                <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>الموردين</span>
                </a>
                
                <!-- Branches -->
                <a href="{{ route('branches.index') }}" class="nav-item {{ request()->routeIs('branches.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>الفروع</span>
                </a>
                
                <!-- Financial Management -->
                <a href="{{ route('financial.index') }}" class="nav-item {{ request()->routeIs('financial.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span>إدارة مالية موحدة</span>
                </a>
                
                <!-- Expenses -->
                <a href="{{ route('expenses.index') }}" class="nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>المصروفات</span>
                </a>

                <!-- System Settings -->
                <a href="{{ route('system-settings.index') }}" class="nav-item {{ request()->routeIs('system-settings.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>إعدادات النظام</span>
                </a>

                <!-- Roles Management -->
                <a href="{{ route('roles.index') }}" class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>إدارة الأدوار</span>
                </a>

                <!-- Permissions Management -->
                <a href="{{ route('permissions.index') }}" class="nav-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>إدارة الصلاحيات</span>
                </a>



                <!-- Shifts Management -->
                <a href="{{ route('shifts.index') }}" class="nav-item {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>إدارة الشِفتات</span>
                </a>

            </nav>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="lg:mr-64 pt-16">
        <div class="min-h-screen bg-gray-50">
            <!-- Page Header -->
            @hasSection('page-header')
                <div class="bg-white border-b border-gray-200 px-4 py-6 sm:px-6 lg:px-8">
                    <div class="max-w-7xl mx-auto">
                        @yield('page-header')
    </div>
                </div>
            @endif
            
            <!-- Page Content -->
            <div class="px-4 py-6 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 animate-fade-in">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                </div>
            @endif

            @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 animate-fade-in">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mr-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                </div>
            @endif

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 animate-fade-in">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mr-3">
                                    <h3 class="text-sm font-medium text-red-800">يوجد أخطاء في النموذج:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                </div>
            @endif

            @yield('content')
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <div class="lg:mr-64 mt-auto">
        @include('components.footer')
    </div>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Settings Dropdown Function -->
    <script>
        function toggleSettingsDropdown() {
            const dropdown = document.getElementById('settings-dropdown');
            const arrow = document.getElementById('settings-dropdown-arrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                dropdown.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('settings-dropdown');
            const button = event.target.closest('button[onclick="toggleSettingsDropdown()"]');
            
            if (!button && dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
                const arrow = document.getElementById('settings-dropdown-arrow');
                if (arrow) arrow.classList.remove('rotate-180');
            }
        });
    </script>

    <!-- Sidebar Functions -->
    <script>
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar) sidebar.classList.remove('open');
            if (overlay) overlay.classList.add('hidden');
            
            // Close any open dropdowns
            const dropdowns = document.querySelectorAll('#settings-dropdown, .mobile-dropdown');
            dropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
        }
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar) sidebar.classList.toggle('open');
            if (overlay) overlay.classList.toggle('hidden');
            
            // Close any open dropdowns when opening sidebar
            if (sidebar && sidebar.classList.contains('open')) {
                const dropdowns = document.querySelectorAll('#settings-dropdown, .mobile-dropdown');
                dropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
            }
        }
        
        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });
        
        // Close sidebar when clicking on a link (mobile)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.nav-item') && window.innerWidth <= 1024) {
                // Small delay to allow navigation
                setTimeout(() => {
                    closeSidebar();
                }, 100);
            }
        });
        
        // Close sidebar when resizing to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar) sidebar.classList.remove('open');
                const overlay = document.getElementById('sidebar-overlay');
                if (overlay) overlay.classList.add('hidden');
            }
        });
    </script>
    
    <!-- Custom Styles -->
    <style>
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            border-radius: 0.375rem;
            transition: all 0.2s;
            margin-bottom: 0.5rem;
            cursor: pointer;
            user-select: none;
        }
        
        .nav-item:hover {
            background-color: #f3f4f6;
            color: #111827;
        }
        
        .nav-item.active {
            background-color: #dbeafe;
            color: #1d4ed8;
            border-right: 2px solid #3b82f6;
        }
        
        .nav-icon {
            height: 1.25rem;
            width: 1.25rem;
            flex-shrink: 0;
            margin-left: 0.75rem;
            transition: transform 0.2s ease;
        }
        
        /* Mobile Sidebar */
        @media (max-width: 1024px) {
            .mobile-sidebar {
                transform: translateX(100%);
                position: fixed;
                top: 64px;
                right: 0;
                height: calc(100vh - 64px);
                z-index: 50;
                box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(10px);
                background-color: rgba(255, 255, 255, 0.95);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .mobile-sidebar.open {
                transform: translateX(0);
            }
        }
        
        @media (min-width: 1025px) {
            .mobile-sidebar {
                transform: translateX(0) !important;
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .main-content {
                margin-right: 0;
            }
            
            /* Mobile sidebar improvements */
            .mobile-sidebar {
                width: 280px;
                max-width: 85vw;
                border-radius: 0.5rem 0 0 0.5rem;
            }
            
            .nav-item {
                padding: 0.75rem 1rem;
                font-size: 1rem;
                margin-bottom: 0.25rem;
                min-height: 48px;
                -webkit-tap-highlight-color: transparent;
                cursor: pointer;
                user-select: none;
            }
            
            .nav-icon {
                height: 1.5rem;
                width: 1.5rem;
                margin-left: 1rem;
            }
        }
        
        /* Tablet adjustments */
        @media (min-width: 641px) and (max-width: 1024px) {
            .mobile-sidebar {
                width: 300px;
                border-radius: 0.5rem 0 0 0.5rem;
            }
        }
        
        /* Footer adjustments */
        .lg\:mr-64 {
            transition: margin-right 0.3s ease-in-out;
        }
        
        @media (max-width: 1024px) {
            .lg\:mr-64 {
                margin-right: 0 !important;
            }
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Dropdown styles */
        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            color: #374151;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f3f4f6;
            color: #111827;
        }
        
        .dropdown-item.active {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        
        /* Settings dropdown improvements */
        #settings-dropdown a {
            transition: all 0.2s ease;
            border-radius: 0.375rem;
            margin: 0.125rem 0.25rem;
        }
        
        #settings-dropdown a:hover {
            background-color: #f3f4f6;
            transform: translateX(-2px);
        }
        
        #settings-dropdown a.active {
            background-color: #dbeafe;
            color: #1d4ed8;
            transform: translateX(-2px);
        }
        
        /* Mobile dropdown improvements */
        @media (max-width: 1024px) {
            #settings-dropdown {
                position: fixed !important;
                left: 1rem !important;
                right: 1rem !important;
                width: auto !important;
                max-width: none !important;
                margin-top: 0.5rem !important;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            }
        }
        
        /* Ensure dropdown is above other elements */
        .dropdown-menu {
            z-index: 9999 !important;
        }
        
        /* Mobile touch improvements */
        @media (max-width: 1024px) {
            .nav-item {
                -webkit-tap-highlight-color: transparent;
                touch-action: manipulation;
            }
            
            .nav-item:active {
                background-color: #e5e7eb;
                transform: scale(0.98);
            }
            
            /* Mobile overlay improvements */
            #sidebar-overlay {
                backdrop-filter: blur(5px);
                background-color: rgba(0, 0, 0, 0.3);
            }
            
            /* Mobile sidebar touch improvements */
            .mobile-sidebar {
                -webkit-overflow-scrolling: touch;
                overscroll-behavior: contain;
            }
            
            /* Mobile header improvements */
            .mobile-sidebar .px-6 {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            
            /* Mobile navigation spacing */
            .mobile-sidebar .px-4 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .mobile-sidebar .py-6 {
                padding-top: 1.5rem;
                padding-bottom: 1.5rem;
            }
            
            /* Mobile navigation improvements */
            .nav-item {
                min-height: 48px;
                border-radius: 0.5rem;
                margin: 0.25rem 0.5rem;
            }
            
            .nav-item:hover {
                background-color: #f3f4f6;
                transform: translateX(-2px);
            }
            
            .nav-item.active {
                background-color: #dbeafe;
                color: #1d4ed8;
                transform: translateX(-2px);
                box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
            }
        }
        
        /* Icon improvements */
        .nav-icon {
            transition: transform 0.2s ease;
        }
        
        @media (max-width: 1024px) {
            .nav-icon {
                height: 1.5rem;
                width: 1.5rem;
                margin-left: 1rem;
            }
            
            /* Mobile icon hover effects */
            .nav-item:hover .nav-icon {
                transform: scale(1.1);
                color: #1d4ed8;
            }
            
            .nav-item.active .nav-icon {
                transform: scale(1.1);
                color: #1d4ed8;
            }
        }
    </style>
    
    @stack('scripts')
</body>
</html>
