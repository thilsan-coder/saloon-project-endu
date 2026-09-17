<x-app-layout>
    @section('title', 'Dashboard Overview')

    <div class="space-y-5">
        
        <!-- Page Title & Actions Control Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-stone-200/60">
            <div>
                <h3 class="text-base sm:text-lg font-extrabold text-stone-900 tracking-tight">Performance Summary</h3>
                <p class="text-xs text-stone-500">Live booking statistics, revenue analysis, and recent transactions.</p>
            </div>

            <!-- Page Actions Dropdown Menu (3 Options) -->
            <div class="flex items-center space-x-2" x-data="{ pageActions: false }">
                <div class="relative">
                    <button @click="pageActions = !pageActions" @click.away="pageActions = false" class="btn-salon-secondary text-xs">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                        <span>Dashboard Actions</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 ml-0.5"></i>
                    </button>

                    <div x-show="pageActions" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-9 z-30 w-52 bg-white rounded-xl shadow-xl border border-stone-200 py-1 text-left">
                        
                        <a href="{{ route('booking.wizard') }}" class="dropdown-menu-item">
                            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-rose-600"></i>
                            <span>Launch Booking Wizard</span>
                        </a>

                        <a href="{{ route('pricing.index') }}" class="dropdown-menu-item">
                            <i data-lucide="tag" class="w-3.5 h-3.5 text-amber-600"></i>
                            <span>Open Pricing Catalog</span>
                        </a>

                        <a href="{{ route('customers.index') }}" class="dropdown-menu-item">
                            <i data-lucide="users" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Customer Directory</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Uniform Summary Cards Grid (Responsive 1/2/4 Columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Total Bookings Card -->
            <div class="salon-card p-4 sm:p-5 h-32 flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Total Bookings</p>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-stone-900 mt-1">{{ number_format($totalBookings) }}</h3>
                    </div>
                    <div class="w-9 sm:w-10 h-9 sm:h-10 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                        <i data-lucide="calendar-check" class="w-4 sm:w-5 h-4 sm:h-5"></i>
                    </div>
                </div>
                <p class="text-[11px] text-rose-600 font-medium flex items-center">
                    <i data-lucide="trending-up" class="w-3 h-3 mr-1 inline"></i>
                    <span>{{ $totalLeads }} Leads / {{ $totalQuotations }} Quotes</span>
                </p>
            </div>

            <!-- Total Revenue Card -->
            <div class="salon-card p-4 sm:p-5 h-32 flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Total Revenue</p>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-emerald-700 mt-1">${{ number_format($totalRevenue, 2) }}</h3>
                    </div>
                    <div class="w-9 sm:w-10 h-9 sm:h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                        <i data-lucide="dollar-sign" class="w-4 sm:w-5 h-4 sm:h-5"></i>
                    </div>
                </div>
                <p class="text-[11px] text-emerald-600 font-medium flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3 h-3 mr-1 inline"></i>
                    <span>Confirmed & Completed</span>
                </p>
            </div>

            <!-- Active Customers Card -->
            <div class="salon-card p-4 sm:p-5 h-32 flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Total Customers</p>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-stone-900 mt-1">{{ number_format($totalCustomers) }}</h3>
                    </div>
                    <div class="w-9 sm:w-10 h-9 sm:h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                        <i data-lucide="users" class="w-4 sm:w-5 h-4 sm:h-5"></i>
                    </div>
                </div>
                <p class="text-[11px] text-stone-500 font-medium">Registered client profiles</p>
            </div>

            <!-- Quick Action Wizard Card -->
            <div class="salon-card p-4 sm:p-5 h-32 bg-gradient-to-br from-stone-900 to-stone-800 text-white flex flex-col justify-between border-stone-800">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="inline-block px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-rose-500 text-white">Action</span>
                        <h4 class="font-bold text-xs sm:text-sm text-white mt-1">New Booking</h4>
                    </div>
                    <div class="w-9 sm:w-10 h-9 sm:h-10 rounded-xl bg-stone-800/80 border border-stone-700 flex items-center justify-center text-amber-400 shrink-0">
                        <i data-lucide="sparkles" class="w-4 sm:w-5 h-4 sm:h-5"></i>
                    </div>
                </div>
                <a href="{{ route('booking.wizard') }}" class="btn-salon-primary w-full text-xs py-1">
                    <span>Launch Wizard</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Uniform Analytics Charts Grid (Responsive 1/3 Columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            <!-- Bookings Over Time (Line Chart) -->
            <div class="salon-card p-4 sm:p-5 lg:col-span-2">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-xs sm:text-sm font-bold text-stone-900">Bookings Over Time</h3>
                        <p class="text-[10px] sm:text-[11px] text-stone-500">Monthly booking count trend</p>
                    </div>
                    <span class="p-1.5 bg-stone-100 rounded-lg text-stone-600">
                        <i data-lucide="line-chart" class="w-4 h-4"></i>
                    </span>
                </div>
                <div class="h-44 sm:h-48 relative">
                    <canvas id="lineChartBookings"></canvas>
                </div>
            </div>

            <!-- Most Popular Services (Pie / Doughnut Chart) -->
            <div class="salon-card p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-xs sm:text-sm font-bold text-stone-900">Popular Services</h3>
                        <p class="text-[10px] sm:text-[11px] text-stone-500">Top requested treatments</p>
                    </div>
                    <span class="p-1.5 bg-rose-50 text-rose-600 rounded-lg">
                        <i data-lucide="pie-chart" class="w-4 h-4"></i>
                    </span>
                </div>
                <div class="h-44 sm:h-48 relative flex items-center justify-center">
                    <canvas id="pieChartServices"></canvas>
                </div>
            </div>
        </div>

        <!-- Revenue by Service Module & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            <!-- Revenue by Service Module (Bar Chart) -->
            <div class="salon-card p-4 sm:p-5 lg:col-span-1">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-xs sm:text-sm font-bold text-stone-900">Revenue by Module</h3>
                        <p class="text-[10px] sm:text-[11px] text-stone-500">Hair vs. Skin vs. Body Care ($)</p>
                    </div>
                    <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                        <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                    </span>
                </div>
                <div class="h-44 sm:h-48 relative">
                    <canvas id="barChartRevenue"></canvas>
                </div>
            </div>

            <!-- Recent Bookings Table Card -->
            <div class="salon-card p-4 sm:p-5 lg:col-span-2 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold text-stone-900">Recent Transactions</h3>
                            <p class="text-[10px] sm:text-[11px] text-stone-500">Latest customer bookings and leads</p>
                        </div>
                        <a href="{{ route('services.index') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-800 flex items-center">
                            <span>View All</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 ml-0.5"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[500px]">
                            <thead>
                                <tr class="border-b border-stone-200 text-[10px] font-semibold text-stone-400 uppercase tracking-wider">
                                    <th class="py-2 px-2">Booking #</th>
                                    <th class="py-2 px-2">Customer</th>
                                    <th class="py-2 px-2">Type</th>
                                    <th class="py-2 px-2">Amount</th>
                                    <th class="py-2 px-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-xs">
                                @forelse($recentBookings as $booking)
                                    <tr class="hover:bg-rose-50/40 transition-colors">
                                        <td class="py-2.5 px-2 font-bold text-stone-900">{{ $booking->booking_number }}</td>
                                        <td class="py-2.5 px-2">
                                            <div class="font-medium text-stone-800">{{ $booking->customer->name ?? 'Guest' }}</div>
                                            <div class="text-[10px] text-stone-400">{{ $booking->customer->phone ?? '' }}</div>
                                        </td>
                                        <td class="py-2.5 px-2">
                                            @if($booking->type === 'quotation')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                                    Quotation
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                    Lead
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 px-2 font-bold text-stone-900">${{ number_format($booking->total_amount, 2) }}</td>
                                        <td class="py-2.5 px-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                                {{ $booking->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                                {{ $booking->status === 'confirmed' ? 'bg-amber-100 text-amber-800' : '' }}
                                                {{ $booking->status === 'pending' ? 'bg-stone-100 text-stone-700' : '' }}
                                                {{ $booking->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-stone-400 text-xs">No bookings recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart.js Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Line Chart: Bookings over time
            const ctxLine = document.getElementById('lineChartBookings').getContext('2d');
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: @json($lineChartLabels),
                    datasets: [{
                        label: 'Total Bookings',
                        data: @json($lineChartData),
                        borderColor: '#e11d48',
                        backgroundColor: 'rgba(225, 29, 72, 0.08)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#e11d48',
                        pointRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } }, grid: { color: 'rgba(231, 229, 228, 0.5)' } },
                        x: { ticks: { font: { size: 10 } }, grid: { display: false } }
                    }
                }
            });

            // Pie Chart: Most popular services
            const ctxPie = document.getElementById('pieChartServices').getContext('2d');
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: @json($pieChartLabels),
                    datasets: [{
                        data: @json($pieChartData),
                        backgroundColor: ['#e11d48', '#f59e0b', '#10b981', '#6366f1', '#ec4899'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                    }
                }
            });

            // Bar Chart: Revenue by Module
            const ctxBar = document.getElementById('barChartRevenue').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: @json($revenueByModuleLabels),
                    datasets: [{
                        label: 'Revenue ($)',
                        data: @json($revenueByModuleData),
                        backgroundColor: ['#be123c', '#d97706', '#059669'],
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { font: { size: 10 } }, grid: { color: 'rgba(231, 229, 228, 0.5)' } },
                        x: { ticks: { font: { size: 10 } }, grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
