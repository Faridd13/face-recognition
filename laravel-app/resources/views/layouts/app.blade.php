<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Face Attendance')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v={{ time() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
            overflow-x: hidden;
            width: 100%;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
        }
        .btn-primary {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
        }
        
        .btn-green {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            transition: all 0.3s ease;
        }
        .btn-green:hover {
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(22, 163, 74, 0.3);
        }
        
        .btn-purple {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            transition: all 0.3s ease;
        }
        .btn-purple:hover {
            background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(124, 58, 237, 0.3);
        }
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.1), 0 2px 4px -1px rgba(249, 115, 22, 0.06);
            transition: all 0.3s ease;
        }
        .card-shadow:hover {
            box-shadow: 0 10px 25px -3px rgba(249, 115, 22, 0.15), 0 4px 6px -2px rgba(249, 115, 22, 0.08);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulseSlow {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .animate-fadeIn { animation: fadeIn 0.5s ease forwards; }
        .animate-slideInLeft { animation: slideInLeft 0.5s ease forwards; }
        .animate-slideInRight { animation: slideInRight 0.5s ease forwards; }
        .animate-slideInUp { animation: slideInUp 0.5s ease forwards; }
        .animate-slideInDown { animation: slideInDown 0.5s ease forwards; }
        .animate-pulse-slow { animation: pulseSlow 2s ease-in-out infinite; }

        /* Mobile menu styles */
        .mobile-menu {
            max-height: 0;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            transition: max-height 0.3s ease;
        }
        .mobile-menu.open {
            max-height: 600px;
        }
        
        /* Responsive table styles */
        .responsive-table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            max-width: 100%;
        }
        .responsive-table-container::-webkit-scrollbar {
            height: 8px;
        }
        .responsive-table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .responsive-table-container::-webkit-scrollbar-thumb {
            background: #f97316;
            border-radius: 4px;
        }
        .responsive-table-container::-webkit-scrollbar-thumb:hover {
            background: #ea580c;
        }
        
        /* Responsive text */
        @media (max-width: 640px) {
            h1 { font-size: 1.5rem !important; }
            h2 { font-size: 1.25rem !important; }
            h3 { font-size: 1.1rem !important; }
            .btn-primary, .btn-green, .btn-purple {
                padding: 0.75rem 1.25rem !important;
                font-size: 0.9rem !important;
            }
            .card-shadow {
                padding: 1.25rem !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen m-0 p-0 overflow-x-hidden flex flex-col" style="scroll-padding-top: 80px;">
    <!-- Navbar -->
    <nav class="bg-white border-b border-orange-100 shadow-sm fixed top-0 left-0 right-0 z-[9999] animate-slideInDown w-full max-w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center transition-transform duration-300 group-hover:rotate-6 group-hover:scale-110 shadow-lg shadow-orange-200">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Face recognition with tech grid background -->
                            <rect x="3" y="3" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="14" y="3" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="3" y="14" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="14" y="14" width="7" height="7" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <!-- Face outline -->
                            <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="1.5" fill="rgba(255,255,255,0.2)"/>
                            <!-- Eyes -->
                            <circle cx="10" cy="11" r="0.8" fill="currentColor"/>
                            <circle cx="14" cy="11" r="0.8" fill="currentColor"/>
                            <!-- Smile -->
                            <path d="M9 14C10 15 14 15 15 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <!-- Scan lines -->
                            <line x1="7" y1="8" x2="17" y2="8" stroke="currentColor" stroke-width="1" opacity="0.6"/>
                            <line x1="7" y1="16" x2="17" y2="16" stroke="currentColor" stroke-width="1" opacity="0.6"/>
                        </svg>
                    </div>
                    <span class="ml-3 text-xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors duration-300">FaceAttendance</span>
                </a>

                @auth
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-orange-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-orange-50 font-medium">Dashboard</a>
                    <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-orange-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-orange-50 font-medium">Siswa</a>
                    <a href="{{ route('attendance.index') }}" class="text-gray-600 hover:text-orange-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-orange-50 font-medium">Presensi</a>
                    @if(Auth::user()->role === 'admin')
                    <div class="relative" id="experimentDropdown">
                        <button onclick="toggleDropdown()" class="text-gray-600 hover:text-orange-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-orange-50 font-medium flex items-center space-x-1 focus:outline-none">
                            <span>Experiment</span>
                            <svg class="w-4 h-4 transition-transform duration-300" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 hidden z-50 border border-orange-100" id="dropdownMenu">
                            <a href="{{ route('experiment.capture') }}" class="block px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all duration-200">Capture Data</a>
                            <a href="{{ route('experiment.train') }}" class="block px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all duration-200">Train Model</a>
                            <a href="{{ route('experiment.test') }}" class="block px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all duration-200">Test Model</a>
                            <a href="{{ route('experiment.logs') }}" class="block px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all duration-200">Logs</a>
                            <a href="{{ route('experiment.metrics') }}" class="block px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all duration-200">Metrics</a>
                            <a href="{{ route('experiment.threshold') }}" class="block px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-all duration-200">Threshold</a>
                        </div>
                    </div>
                    @endif
                    <div class="flex items-center ml-4 pl-4 border-l border-gray-100">
                        <div class="text-right mr-3 hidden sm:block">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-orange-600 transition-all duration-300 px-3 py-2 rounded-lg hover:bg-orange-50">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-orange-600 transition-all duration-300 p-2 rounded-lg hover:bg-orange-50 focus:outline-none">
                        <svg id="menuIcon" class="w-6 h-6 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                @endauth
            </div>

            <!-- Mobile Menu -->
            @auth
            <div id="mobileMenu" class="mobile-menu md:hidden">
                <div class="py-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="block text-gray-600 hover:text-orange-600 px-4 py-3 rounded-lg transition-all duration-300 hover:bg-orange-50 font-medium">Dashboard</a>
                    <a href="{{ route('students.index') }}" class="block text-gray-600 hover:text-orange-600 px-4 py-3 rounded-lg transition-all duration-300 hover:bg-orange-50 font-medium">Siswa</a>
                    <a href="{{ route('attendance.index') }}" class="block text-gray-600 hover:text-orange-600 px-4 py-3 rounded-lg transition-all duration-300 hover:bg-orange-50 font-medium">Presensi</a>
                    @if(Auth::user()->role === 'admin')
                        <div class="border-t border-gray-100 pt-2 mt-2">
                            <p class="px-4 py-2 text-xs font-semibold text-orange-600 uppercase tracking-wider">Experiment</p>
                            <a href="{{ route('experiment.capture') }}" class="block text-gray-600 hover:text-orange-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-orange-50 ml-2">Capture Data</a>
                            <a href="{{ route('experiment.train') }}" class="block text-gray-600 hover:text-orange-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-orange-50 ml-2">Train Model</a>
                            <a href="{{ route('experiment.test') }}" class="block text-gray-600 hover:text-orange-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-orange-50 ml-2">Test Model</a>
                            <a href="{{ route('experiment.logs') }}" class="block text-gray-600 hover:text-orange-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-orange-50 ml-2">Logs</a>
                            <a href="{{ route('experiment.metrics') }}" class="block text-gray-600 hover:text-orange-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-orange-50 ml-2">Metrics</a>
                            <a href="{{ route('experiment.threshold') }}" class="block text-gray-600 hover:text-orange-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-orange-50 ml-2">Threshold</a>
                        </div>
                    @endif
                    <div class="border-t border-gray-100 pt-4 mt-4">
                        <div class="px-4 py-2">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="px-4">
                            @csrf
                            <button type="submit" class="w-full text-left text-sm text-gray-500 hover:text-orange-600 transition-all duration-300 px-3 py-3 rounded-lg hover:bg-orange-50 font-medium">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 pb-8 flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mb-6 animate-slideInLeft">
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mb-6 animate-slideInLeft" style="animation-delay: 0.1s;">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-orange-100 py-6 sm:py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 text-center">
            <p class="text-gray-500 text-xs sm:text-sm">© 2026 FaceAttendance. All rights reserved.</p>
            <p class="text-orange-600 text-xs mt-1 sm:mt-2 font-medium">Sistem Presensi Wajah Berbasis AI</p>
        </div>
    </footer>

    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            const arrow = document.getElementById('dropdownArrow');
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('menuIcon');
            menu.classList.toggle('open');
            
            if (menu.classList.contains('open')) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
            } else {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
            }
        }

        // Tutup dropdown ketika klik di luar
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('experimentDropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                document.getElementById('dropdownMenu').classList.add('hidden');
                document.getElementById('dropdownArrow').classList.remove('rotate-180');
            }
        });
    </script>
</body>
</html>
