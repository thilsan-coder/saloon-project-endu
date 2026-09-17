<x-app-layout>
    @section('title', 'Salon Services & Bookings')

    <div class="space-y-5" x-data="servicesPage()">

        <!-- Page Header -->
        <div class="pb-2 border-b border-stone-200/60">
            <h3 class="text-base sm:text-lg font-extrabold text-stone-900 tracking-tight">Services & Transactions</h3>
            <p class="text-xs text-stone-500">Explore main treatment modules and manage customer booking records.</p>
        </div>

        <!-- 3 Main Module Cards (Uniform Proportions & Card Specs) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($modules as $module)
                <div class="salon-card p-5 h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                                @if($module->name === 'Hair Care')
                                    <i data-lucide="scissors" class="w-4 h-4"></i>
                                @elseif($module->name === 'Skin Care')
                                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                                @else
                                    <i data-lucide="heart-pulse" class="w-4 h-4"></i>
                                @endif
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-stone-100 text-stone-600 border border-stone-200">
                                {{ $module->subModules->count() }} Sub-Modules
                            </span>
                        </div>
                        <h3 class="text-base font-extrabold text-stone-900 tracking-tight">{{ $module->name }}</h3>
                        <p class="text-xs text-stone-500 mt-1 line-clamp-2 h-9 leading-relaxed">{{ $module->description }}</p>
                    </div>

                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between mt-4">
                        <span class="text-[11px] font-semibold text-stone-500">
                            {{ $module->subModules->flatMap->serviceItems->count() }} Available Services
                        </span>
                        <a href="{{ route('booking.wizard') }}" class="text-xs font-bold text-rose-600 hover:text-rose-800 flex items-center shrink-0">
                            <span>Book Now</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 ml-0.5"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bookings & Selections Table Card -->
        <div class="salon-card p-5">
            
            <!-- Table Header Controls -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-base font-bold text-stone-900">Customer Bookings & Selections</h3>
                    <p class="text-xs text-stone-500">View, update, or remove recorded leads and quotations.</p>
                </div>

                <form method="GET" action="{{ route('services.index') }}" data-ajax-form="true" class="flex flex-wrap items-center gap-2">
                    <!-- Search Input -->
                    <div class="relative">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-stone-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer/booking..." 
                               class="salon-input w-44 md:w-56">
                    </div>

                    <!-- Type Filter -->
                    <select name="type" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit()" class="salon-select">
                        <option value="">All Types</option>
                        <option value="lead" {{ request('type') == 'lead' ? 'selected' : '' }}>Lead</option>
                        <option value="quotation" {{ request('type') == 'quotation' ? 'selected' : '' }}>Quotation</option>
                    </select>

                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.requestSubmit ? this.form.requestSubmit() : this.form.submit()" class="salon-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    @if(request()->anyFilled(['search', 'type', 'status']))
                        <a href="{{ route('services.index') }}" data-ajax-pagination="true" class="p-1.5 text-stone-400 hover:text-stone-700 rounded-lg" title="Clear Filters">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table List -->
            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-left border-collapse min-w-[650px]">
                    <thead>
                        <tr class="border-b border-stone-200 text-[10px] font-semibold text-stone-400 uppercase tracking-wider bg-stone-50/50">
                            <th class="py-3 px-3">Booking #</th>
                            <th class="py-3 px-3">Customer Name</th>
                            <th class="py-3 px-3">Services Selected</th>
                            <th class="py-3 px-3">Type</th>
                            <th class="py-3 px-3">Total Amount</th>
                            <th class="py-3 px-3">Date</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 text-xs">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-rose-50/30 transition-colors group">
                                <td class="py-3 px-3 font-bold text-stone-900">
                                    {{ $booking->booking_number }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-stone-800">{{ $booking->customer->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-stone-400">{{ $booking->customer->phone ?? 'No phone' }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="max-w-xs truncate text-xs text-stone-600" title="{{ implode(', ', $booking->bookingItems->map(fn($i) => $i->serviceItem->name ?? '')->toArray()) }}">
                                        <span class="font-bold text-rose-600">{{ $booking->bookingItems->count() }} Items:</span>
                                        {{ implode(', ', $booking->bookingItems->map(fn($i) => $i->serviceItem->name ?? '')->take(2)->toArray()) }}
                                        @if($booking->bookingItems->count() > 2)...@endif
                                    </div>
                                </td>
                                <td class="py-3 px-3">
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
                                <td class="py-3 px-3 font-extrabold text-stone-900">
                                    ${{ number_format($booking->total_amount, 2) }}
                                </td>
                                <td class="py-3 px-3 text-stone-500 whitespace-nowrap">
                                    {{ $booking->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-3 px-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                        {{ $booking->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $booking->status === 'confirmed' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $booking->status === 'pending' ? 'bg-stone-100 text-stone-700' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                                <!-- 3-Option Action Dropdown Menu -->
                                <td class="py-3 px-3 text-right relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" class="p-1 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 focus:outline-none">
                                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                    </button>

                                    <!-- Dropdown Menu Body (3 Options) -->
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-3 top-10 z-30 w-44 bg-white rounded-xl shadow-xl border border-stone-100 py-1 text-left">
                                        
                                        <!-- Option 1: View Details -->
                                        <button @click="open = false; openViewModal({{ $booking->id }})" class="dropdown-menu-item w-full">
                                            <i data-lucide="eye" class="w-3.5 h-3.5 text-stone-500"></i>
                                            <span>View Details</span>
                                        </button>

                                        <!-- Option 2: Update Status -->
                                        <button @click="open = false; openEditModal({{ $booking->id }}, '{{ $booking->status }}')" class="dropdown-menu-item w-full">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5 text-stone-500"></i>
                                            <span>Update Status</span>
                                        </button>

                                        <!-- Option 3: Delete / PDF Action -->
                                        @if($booking->type === 'quotation')
                                            <a href="{{ route('booking.pdf', $booking->id) }}" target="_blank" class="dropdown-menu-item w-full">
                                                <i data-lucide="file-text" class="w-3.5 h-3.5 text-purple-600"></i>
                                                <span class="text-purple-700 font-medium">Download PDF</span>
                                            </a>
                                        @else
                                            <button @click="open = false; openDeleteModal({{ $booking->id }}, '{{ $booking->booking_number }}')" class="dropdown-menu-item w-full text-rose-600 hover:bg-rose-50">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5 text-rose-500"></i>
                                                <span>Delete Booking</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 text-center text-stone-400">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto text-stone-300 mb-1"></i>
                                    <p class="font-medium text-stone-600">No bookings match your request.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div>
                {{ $bookings->links('vendor.pagination.tailwind') }}
            </div>
        </div>

        <!-- View Details Modal -->
        <div x-show="viewModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <div class="max-w-xl w-full bg-white rounded-2xl p-6 relative shadow-2xl border border-stone-100 max-h-[88vh] overflow-y-auto space-y-4" @click.away="viewModalOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div>
                        <h3 class="text-lg font-bold text-stone-900 tracking-tight" x-text="'Booking #' + (activeBooking.booking_number || '')"></h3>
                        <p class="text-xs text-stone-400 mt-0.5" x-text="'Recorded on ' + (activeBooking.created_at || '')"></p>
                    </div>
                    <button @click="viewModalOpen = false" class="w-8 h-8 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="py-2 space-y-4" x-show="activeBooking.id">
                    <div class="p-4 rounded-xl bg-stone-50/70 border border-stone-100">
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-2">Customer Profile</h4>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div><span class="text-stone-500">Name:</span> <strong class="text-stone-800 ml-1" x-text="activeBooking.customer?.name"></strong></div>
                            <div><span class="text-stone-500">Phone:</span> <strong class="text-stone-800 ml-1" x-text="activeBooking.customer?.phone"></strong></div>
                            <div><span class="text-stone-500">Email:</span> <strong class="text-stone-800 ml-1" x-text="activeBooking.customer?.email || 'N/A'"></strong></div>
                            <div><span class="text-stone-500">Address:</span> <strong class="text-stone-800 ml-1" x-text="activeBooking.customer?.address || 'N/A'"></strong></div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-2">Selected Services</h4>
                        <div class="divide-y divide-stone-100 border border-stone-100 rounded-xl overflow-hidden">
                            <template x-for="bItem in activeBooking.booking_items" :key="bItem.id">
                                <div class="p-3 flex items-center justify-between bg-white text-xs">
                                    <div>
                                        <div class="font-bold text-stone-800" x-text="bItem.service_item?.name"></div>
                                        <div class="text-[10px] text-stone-400 mt-0.5" x-text="(bItem.service_item?.sub_module?.main_module?.name || '') + ' → ' + (bItem.service_item?.sub_module?.name || '')"></div>
                                    </div>
                                    <div class="font-extrabold text-stone-900" x-text="'$' + parseFloat(bItem.price_at_booking).toFixed(2)"></div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <template x-if="activeBooking.signature_path">
                        <div class="p-3.5 bg-purple-50/50 border border-purple-100 rounded-xl">
                            <h4 class="text-[10px] font-bold uppercase tracking-wider text-purple-700 mb-2">Digital Signature Captured</h4>
                            <div class="p-2 bg-white rounded-xl border border-purple-200/80 inline-block shadow-2xs">
                                <img :src="'/' + activeBooking.signature_path" alt="Customer Signature" class="h-12 object-contain">
                            </div>
                        </div>
                    </template>

                    <div class="flex items-center justify-between pt-3 border-t border-stone-100">
                        <span class="text-xs font-bold text-stone-700">Total Amount:</span>
                        <span class="text-xl font-black text-rose-600" x-text="'$' + parseFloat(activeBooking.total_amount || 0).toFixed(2)"></span>
                    </div>
                </div>

                <div class="pt-3 border-t border-stone-100 flex justify-end">
                    <button @click="viewModalOpen = false" class="btn-salon-secondary text-xs px-4 py-2">Close</button>
                </div>
            </div>
        </div>

        <!-- Edit Status Modal -->
        <div x-show="editModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <div class="max-w-sm w-full bg-white rounded-2xl p-6 relative shadow-2xl border border-stone-100 space-y-4" @click.away="editModalOpen = false">
                <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                    <h3 class="text-base font-bold text-stone-900">Update Booking Status</h3>
                    <button @click="editModalOpen = false" class="w-7 h-7 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form :action="'/services/' + editBookingId + '/status'" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1.5">Status</label>
                        <select name="status" x-model="editStatus" class="salon-select w-full">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-stone-100">
                        <button type="button" @click="editModalOpen = false" class="btn-salon-secondary text-xs px-4 py-2">Cancel</button>
                        <button type="submit" class="btn-salon-primary text-xs px-4 py-2">Save Status</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="deleteModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <div class="max-w-sm w-full bg-white rounded-2xl p-6 relative shadow-2xl border border-stone-100 space-y-4" @click.away="deleteModalOpen = false">
                <div class="flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-stone-900">Delete Booking?</h3>
                        <p class="text-xs text-stone-500 mt-1 leading-relaxed" x-text="'Are you sure you want to delete booking ' + deleteNumber + '?'"></p>
                    </div>
                </div>

                <form :action="'/services/' + deleteBookingId" method="POST" class="pt-2 flex justify-end space-x-2.5 border-t border-stone-100">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteModalOpen = false" class="btn-salon-secondary text-xs px-4 py-2">Cancel</button>
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-4 py-2 rounded-xl text-xs shadow-xs transition-colors">Delete</button>
                </form>
            </div>
        </div>

        <!-- End of Delete Modal -->

    </div>

    <script>
        function servicesPage() {
            return {
                viewModalOpen: false,
                editModalOpen: false,
                deleteModalOpen: false,
                activeBooking: {},
                editBookingId: null,
                editStatus: 'pending',
                deleteBookingId: null,
                deleteNumber: '',

                openViewModal(id) {
                    fetch('/services/' + id)
                        .then(res => res.json())
                        .then(data => {
                            if(data.success) {
                                this.activeBooking = data.booking;
                                this.viewModalOpen = true;
                                setTimeout(() => lucide.createIcons(), 50);
                            }
                        });
                },

                openEditModal(id, currentStatus) {
                    this.editBookingId = id;
                    this.editStatus = currentStatus;
                    this.editModalOpen = true;
                },

                openDeleteModal(id, number) {
                    this.deleteBookingId = id;
                    this.deleteNumber = number;
                    this.deleteModalOpen = true;
                }
            }
        }
    </script>
</x-app-layout>
