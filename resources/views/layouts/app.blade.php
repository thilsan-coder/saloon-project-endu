<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Salon Elite Management') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SignaturePad -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

    <!-- Vite Scripts & CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Compact, Clean Salon UI Utility Classes */
        .salon-card {
            background-color: #ffffff;
            border-radius: 1rem; /* 16px */
            border: 1px solid #e7e5e4;
            box-shadow: 0 2px 10px -2px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease-in-out;
        }

        .salon-card:hover {
            box-shadow: 0 8px 24px -4px rgba(225, 29, 72, 0.08);
            border-color: #fecdd3;
        }

        .btn-salon-primary {
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
            color: #ffffff;
            font-weight: 600;
            padding: 0.5rem 1.125rem;
            border-radius: 0.75rem;
            box-shadow: 0 3px 10px 0 rgba(225, 29, 72, 0.3);
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            font-size: 0.8125rem; /* 13px */
        }

        .btn-salon-primary:hover {
            background: linear-gradient(135deg, #be123c 0%, #9f1239 100%);
            box-shadow: 0 4px 14px 0 rgba(225, 29, 72, 0.4);
            transform: translateY(-1px);
        }

        .btn-salon-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn-salon-secondary {
            background-color: #f5f5f4;
            color: #44403c;
            font-weight: 600;
            padding: 0.5rem 1.125rem;
            border-radius: 0.75rem;
            border: 1px solid #e7e5e4;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
        }

        .btn-salon-secondary:hover {
            background-color: #e7e5e4;
            color: #1c1917;
        }

        .dropdown-menu-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.875rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #374151;
            transition: background-color 0.15s ease;
        }

        .dropdown-menu-item:hover {
            background-color: #fff1f2;
            color: #be123c;
        }

        /* Clean, Perfectly Aligned Select Dropdowns & Inputs */
        .salon-select,
        .salon-input {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #ffffff;
            border: 1px solid #d6d3d1;
            border-radius: 0.75rem;
            padding-top: 0.375rem;
            padding-bottom: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #1c1917;
            line-height: 1.25rem;
            height: 2.125rem; /* 34px exact height match */
            vertical-align: middle;
            transition: all 0.15s ease-in-out;
            outline: none;
            box-sizing: border-box;
        }

        .salon-select {
            padding-left: 0.625rem;
            padding-right: 1.75rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2378716c' stroke-width='2'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 0.875rem 0.875rem;
        }

        .salon-input {
            padding-left: 2rem;
            padding-right: 0.75rem;
        }

        .salon-select:focus,
        .salon-input:focus {
            border-color: #e11d48;
            box-shadow: 0 0 0 2px rgba(225, 29, 72, 0.15);
        }

        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #fafaf9;
        }
        ::-webkit-scrollbar-thumb {
            background: #e7e5e4;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #d6d3d1;
        }

        /* Sidebar Specific Scrollbar */
        aside::-webkit-scrollbar {
            width: 4px;
        }
        aside::-webkit-scrollbar-track {
            background: #1c1917;
        }
        aside::-webkit-scrollbar-thumb {
            background: #44403c;
            border-radius: 9999px;
        }
    </style>
</head>
<body class="h-full overflow-hidden antialiased text-stone-800 bg-stone-50" x-data="{ sidebarOpen: false }">
    <div class="h-screen w-full flex flex-col md:flex-row overflow-hidden">
        
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-stone-950/60 backdrop-blur-xs z-30 md:hidden"></div>

        <!-- Mobile Top Navigation Header -->
        <header class="md:hidden bg-stone-900 text-white px-4 py-3 flex items-center justify-between shadow-md shrink-0 z-20">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-rose-500 to-amber-400 flex items-center justify-center shadow-inner">
                    <i data-lucide="scissors" class="w-4 h-4 text-white"></i>
                </div>
                <span class="font-bold text-sm sm:text-base tracking-wide text-rose-100">Velvet & Co. Salon</span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-1.5 rounded-lg text-stone-300 hover:text-white hover:bg-stone-800 focus:outline-none" aria-label="Toggle menu">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
        </header>

        <!-- Sidebar Navigation (Stationary Desktop, Slide-over Mobile/Tablet) -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 w-64 md:w-60 h-screen bg-stone-900 text-stone-300 transition-transform duration-300 ease-in-out md:sticky md:top-0 md:shrink-0 flex flex-col justify-between border-r border-stone-800 shadow-2xl md:shadow-none overflow-y-auto"
        >
            <div>
                <!-- Salon Branding Header -->
                <div class="px-5 py-5 border-b border-stone-800/80 flex items-center justify-between shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-500 to-amber-400 flex items-center justify-center shadow-lg shadow-rose-950/40">
                            <i data-lucide="scissors" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                            <h1 class="font-bold text-white text-sm tracking-wide leading-tight">Velvet & Co.</h1>
                            <p class="text-[10px] text-rose-400/90 font-semibold tracking-wider uppercase">Salon Admin</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="md:hidden p-1.5 text-stone-400 hover:text-white rounded-lg hover:bg-stone-800" aria-label="Close sidebar">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="px-3 py-4 space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-rose-600 text-white shadow-md font-semibold' : 'text-stone-300 hover:bg-stone-800/70 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 mr-2.5"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('services.index') }}" 
                       class="flex items-center px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all duration-200 {{ request()->routeIs('services.*') ? 'bg-rose-600 text-white shadow-md font-semibold' : 'text-stone-300 hover:bg-stone-800/70 hover:text-white' }}">
                        <i data-lucide="sparkles" class="w-4 h-4 mr-2.5"></i>
                        <span>Services</span>
                    </a>

                    <a href="{{ route('pricing.index') }}" 
                       class="flex items-center px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all duration-200 {{ request()->routeIs('pricing.*') ? 'bg-rose-600 text-white shadow-md font-semibold' : 'text-stone-300 hover:bg-stone-800/70 hover:text-white' }}">
                        <i data-lucide="tag" class="w-4 h-4 mr-2.5"></i>
                        <span>Service Pricing</span>
                    </a>

                    <a href="{{ route('customers.index') }}" 
                       class="flex items-center px-3.5 py-2.5 rounded-xl font-medium text-xs transition-all duration-200 {{ request()->routeIs('customers.*') ? 'bg-rose-600 text-white shadow-md font-semibold' : 'text-stone-300 hover:bg-stone-800/70 hover:text-white' }}">
                        <i data-lucide="users" class="w-4 h-4 mr-2.5"></i>
                        <span>Customers</span>
                    </a>

                    <div class="pt-3 pb-1">
                        <div class="px-3 text-[10px] font-semibold uppercase tracking-wider text-stone-500">Quick Actions</div>
                    </div>

                    <a href="{{ route('booking.wizard') }}" 
                       class="flex items-center px-3.5 py-2.5 rounded-xl font-semibold text-xs bg-gradient-to-r from-amber-500 to-rose-500 text-white shadow-sm hover:shadow hover:from-amber-600 hover:to-rose-600 transition-all duration-200">
                        <i data-lucide="plus-circle" class="w-4 h-4 mr-2.5"></i>
                        <span>New Booking Wizard</span>
                    </a>
                </nav>
            </div>

            <!-- User Profile & Logout Bottom Bar -->
            <div class="p-3 border-t border-stone-800/80 bg-stone-950/40 shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-full bg-rose-900/60 text-rose-200 font-bold flex items-center justify-center border border-rose-700/50 text-xs">
                            {{ strtoupper(substr(Auth::user()->name ?? 'SA', 0, 2)) }}
                        </div>
                        <div class="truncate max-w-[100px]">
                            <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? 'Super Admin' }}</p>
                            <p class="text-[10px] text-stone-400 truncate">Super Admin</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Logout" class="p-1.5 text-stone-400 hover:text-rose-400 hover:bg-stone-800 rounded-lg transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper with Independent Vertical Scroll -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
            
            <!-- Top Header Bar -->
            <header class="hidden md:flex bg-white border-b border-stone-200/80 px-4 sm:px-6 py-3 items-center justify-between sticky top-0 z-20 shadow-xs shrink-0">
                <div class="flex items-center space-x-3">
                    <h2 class="text-base sm:text-lg font-bold text-stone-900 tracking-tight">
                        @yield('title', 'Salon Management')
                    </h2>
                </div>
            </header>

            <!-- Notification Toast / Flash Alerts -->
            @if(session('success'))
                <div class="mx-5 mt-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between text-emerald-800 shadow-xs shrink-0" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center space-x-2.5">
                        <div class="p-1 bg-emerald-500 text-white rounded-md">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        </div>
                        <span class="text-xs font-semibold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-900 p-1">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-5 mt-4 p-3 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-between text-rose-800 shadow-xs shrink-0" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center space-x-2.5">
                        <div class="p-1 bg-rose-500 text-white rounded-md">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        </div>
                        <span class="text-xs font-semibold">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-600 hover:text-rose-900 p-1">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            @endif

            <!-- Main Page View Content -->
            <main class="p-4 sm:p-5 md:p-6 flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Lucide Icon Renderer & Dynamic AJAX Table Pagination (Zero Scroll Jump) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                window.lucide.createIcons();
            }
        });

        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[data-ajax-pagination="true"]');
            if (!link) return;

            e.preventDefault();
            const targetUrl = link.href;
            if (!targetUrl || targetUrl === '#' || targetUrl.startsWith('javascript:')) return;

            // Find target table container card
            const tableCard = link.closest('.salon-card') || link.closest('[data-table-container]');
            if (!tableCard) {
                window.location.href = targetUrl;
                return;
            }

            // Save exact current scroll Y position
            const currentScrollY = window.scrollY;

            // Indicate soft loading state on table container only
            tableCard.style.opacity = '0.5';
            tableCard.style.pointerEvents = 'none';

            fetch(targetUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                let freshCard = null;
                if (tableCard.id) {
                    freshCard = doc.getElementById(tableCard.id);
                }
                if (!freshCard) {
                    const cards = Array.from(document.querySelectorAll('.salon-card, [data-table-container]'));
                    const idx = cards.indexOf(tableCard);
                    const freshCards = doc.querySelectorAll('.salon-card, [data-table-container]');
                    if (idx !== -1 && freshCards[idx]) {
                        freshCard = freshCards[idx];
                    }
                }

                if (freshCard) {
                    tableCard.innerHTML = freshCard.innerHTML;
                    
                    // Update URL without page reload or scroll jump
                    window.history.pushState({ path: targetUrl }, '', targetUrl);

                    // Re-initialize Alpine.js & Lucide icons on updated table card
                    if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                        window.Alpine.initTree(tableCard);
                    }
                    if (window.lucide && typeof window.lucide.createIcons === 'function') {
                        window.lucide.createIcons();
                    }

                    // Freeze exact scroll position
                    window.scrollTo(0, currentScrollY);
                } else {
                    window.location.href = targetUrl;
                }
            })
            .catch(err => {
                console.error('AJAX Pagination error:', err);
                window.location.href = targetUrl;
            })
            .finally(() => {
                tableCard.style.opacity = '1';
                tableCard.style.pointerEvents = 'auto';
            });
        });

        // Universal Dynamic AJAX Form Submissions for Filters (Zero Scroll Jump)
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.method.toUpperCase() !== 'GET' && !form.hasAttribute('data-ajax-form')) return;

            const tableCard = form.closest('.salon-card') || form.closest('[data-table-container]');
            if (!tableCard) return;

            e.preventDefault();

            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (const [key, value] of formData.entries()) {
                if (value !== '') {
                    params.append(key, value);
                }
            }

            const baseUrl = form.action || window.location.pathname;
            const queryString = params.toString();
            const targetUrl = queryString ? `${baseUrl}?${queryString}` : baseUrl;

            const currentScrollY = window.scrollY;

            tableCard.style.opacity = '0.5';
            tableCard.style.pointerEvents = 'none';

            fetch(targetUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                let freshCard = null;
                if (tableCard.id) {
                    freshCard = doc.getElementById(tableCard.id);
                }
                if (!freshCard) {
                    const cards = Array.from(document.querySelectorAll('.salon-card, [data-table-container]'));
                    const idx = cards.indexOf(tableCard);
                    const freshCards = doc.querySelectorAll('.salon-card, [data-table-container]');
                    if (idx !== -1 && freshCards[idx]) {
                        freshCard = freshCards[idx];
                    }
                }

                if (freshCard) {
                    tableCard.innerHTML = freshCard.innerHTML;
                    window.history.pushState({ path: targetUrl }, '', targetUrl);

                    if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                        window.Alpine.initTree(tableCard);
                    }
                    if (window.lucide && typeof window.lucide.createIcons === 'function') {
                        window.lucide.createIcons();
                    }

                    window.scrollTo(0, currentScrollY);
                } else {
                    window.location.href = targetUrl;
                }
            })
            .catch(err => {
                console.error('AJAX Filter error:', err);
                window.location.href = targetUrl;
            })
            .finally(() => {
                tableCard.style.opacity = '1';
                tableCard.style.pointerEvents = 'auto';
            });
        });

        window.addEventListener('popstate', function() {
            window.location.reload();
        });
    </script>
</body>
</html>
