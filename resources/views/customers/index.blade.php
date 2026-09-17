<x-app-layout>
    @section('title', 'Customer Profiles & History')

    <div class="space-y-5" x-data="customersPage()">

        <!-- Page Header & Actions Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-stone-200/60">
            <div>
                <h3 class="text-base sm:text-lg font-extrabold text-stone-900 tracking-tight">Customer Directory</h3>
                <p class="text-xs text-stone-500">Manage client profiles, contact information, and past booking activity.</p>
            </div>

            <!-- Page Actions Dropdown Menu (3 Options) -->
            <div class="flex items-center space-x-2" x-data="{ customerActions: false }">
                <div class="relative">
                    <button @click="customerActions = !customerActions" @click.away="customerActions = false" class="btn-salon-primary text-xs">
                        <i data-lucide="user-cog" class="w-3.5 h-3.5"></i>
                        <span>Customer Actions</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 ml-0.5"></i>
                    </button>

                    <div x-show="customerActions" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-9 z-30 w-52 bg-white rounded-xl shadow-xl border border-stone-200 py-1 text-left">
                        
                        <button @click="customerActions = false; openAddModal()" class="dropdown-menu-item w-full">
                            <i data-lucide="user-plus" class="w-3.5 h-3.5 text-rose-600"></i>
                            <span>Add New Customer</span>
                        </button>

                        <a href="{{ route('services.index') }}" class="dropdown-menu-item">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 text-amber-600"></i>
                            <span>View All Bookings</span>
                        </a>

                        <a href="{{ route('booking.wizard') }}" class="dropdown-menu-item">
                            <i data-lucide="calendar-plus" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Launch Booking Wizard</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Container Card -->
        <div class="salon-card p-5">
            
            <!-- Search Control -->
            <div class="flex items-center justify-between gap-3 mb-4">
                <form method="GET" action="{{ route('customers.index') }}" data-ajax-form="true" class="relative w-full max-w-sm">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-stone-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer by name, phone, or email..." 
                           class="salon-input w-full">
                </form>

                <span class="text-xs text-stone-500 font-medium hidden sm:inline">
                    Showing {{ $customers->total() }} Customers
                </span>
            </div>

            <!-- Customer Table -->
            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-left border-collapse min-w-[650px]">
                    <thead>
                        <tr class="border-b border-stone-200 text-[10px] font-semibold text-stone-400 uppercase tracking-wider bg-stone-50/50">
                            <th class="py-3 px-3">Customer Name</th>
                            <th class="py-3 px-3">Phone Number</th>
                            <th class="py-3 px-3">Email</th>
                            <th class="py-3 px-3">Address</th>
                            <th class="py-3 px-3">Total Bookings</th>
                            <th class="py-3 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 text-xs">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-rose-50/30 transition-colors">
                                <td class="py-3 px-3 font-bold text-stone-900">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-700 font-bold text-[11px] flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                        </div>
                                        <span>{{ $customer->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3 font-medium text-stone-800">
                                    {{ $customer->phone }}
                                </td>
                                <td class="py-3 px-3 text-stone-600">
                                    {{ $customer->email ?: 'N/A' }}
                                </td>
                                <td class="py-3 px-3 text-stone-500 max-w-xs truncate">
                                    {{ $customer->address ?: 'N/A' }}
                                </td>
                                <td class="py-3 px-3 font-bold text-rose-600">
                                    <span class="px-2 py-0.5 rounded-full bg-rose-50 border border-rose-100 text-[10px]">
                                        {{ $customer->bookings_count }} Bookings
                                    </span>
                                </td>

                                <!-- 3-Option Action Dropdown Menu -->
                                <td class="py-3 px-3 text-right relative" x-data="{ openRowMenu: false }">
                                    <button @click="openRowMenu = !openRowMenu" @click.away="openRowMenu = false" class="p-1 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 focus:outline-none">
                                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                    </button>

                                    <!-- Dropdown Menu Body (3 Options) -->
                                    <div x-show="openRowMenu" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-3 top-10 z-30 w-44 bg-white rounded-xl shadow-xl border border-stone-100 py-1 text-left">
                                        
                                        <!-- Option 1: View History -->
                                        <button @click="openRowMenu = false; openHistoryModal({{ $customer->id }})" class="dropdown-menu-item w-full">
                                            <i data-lucide="history" class="w-3.5 h-3.5 text-stone-500"></i>
                                            <span>View History</span>
                                        </button>

                                        <!-- Option 2: Edit Profile -->
                                        <button @click="openRowMenu = false; openEditModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ addslashes($customer->phone) }}', '{{ addslashes($customer->email) }}', '{{ addslashes($customer->address) }}')" class="dropdown-menu-item w-full">
                                            <i data-lucide="edit" class="w-3.5 h-3.5 text-stone-500"></i>
                                            <span>Edit Profile</span>
                                        </button>

                                        <!-- Option 3: Delete Customer -->
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Delete customer profile?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-menu-item w-full text-rose-600 hover:bg-rose-50">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5 text-rose-500"></i>
                                                <span>Delete Profile</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-stone-400">
                                    <i data-lucide="users" class="w-8 h-8 mx-auto text-stone-300 mb-1"></i>
                                    <p class="font-medium text-stone-600">No customers found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div>
                {{ $customers->links('vendor.pagination.tailwind') }}
            </div>
        </div>

        <!-- Add / Edit Customer Modal -->
        <div x-show="customerModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <div class="max-w-sm w-full bg-white rounded-2xl p-6 relative shadow-2xl border border-stone-100 space-y-4" @click.away="customerModalOpen = false">
                <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                    <h3 class="text-base font-bold text-stone-900" x-text="isEdit ? 'Edit Customer Profile' : 'Add New Customer'"></h3>
                    <button @click="customerModalOpen = false" class="w-7 h-7 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form :action="isEdit ? '/customers/' + customerId : '{{ route('customers.store') }}'" method="POST" class="space-y-3.5">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Full Name</label>
                        <input type="text" name="name" x-model="form.name" required class="salon-input w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Phone Number</label>
                        <input type="text" name="phone" x-model="form.phone" required class="salon-input w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Email Address</label>
                        <input type="email" name="email" x-model="form.email" class="salon-input w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Physical Address</label>
                        <textarea name="address" x-model="form.address" rows="2" class="w-full py-2 px-3 rounded-xl border border-stone-300 text-xs focus:ring-rose-500 focus:border-rose-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-stone-100">
                        <button type="button" @click="customerModalOpen = false" class="btn-salon-secondary text-xs px-4 py-2">Cancel</button>
                        <button type="submit" class="btn-salon-primary text-xs px-4 py-2">Save Profile</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Customer Booking History Drawer / Modal -->
        <div x-show="historyModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <div class="max-w-xl w-full bg-white rounded-2xl p-6 relative shadow-2xl border border-stone-100 max-h-[85vh] overflow-y-auto space-y-4" @click.away="historyModalOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div>
                        <h3 class="text-lg font-bold text-stone-900 tracking-tight" x-text="historyCustomer.name + '\'s Booking History'"></h3>
                        <p class="text-xs text-stone-400 mt-0.5" x-text="historyCustomer.phone + ' | ' + (historyCustomer.email || 'No email')"></p>
                    </div>
                    <button @click="historyModalOpen = false" class="w-8 h-8 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="py-2 space-y-3">
                    <template x-for="b in historyCustomer.bookings" :key="b.id">
                        <div class="p-3.5 rounded-xl border border-stone-100 bg-stone-50/50 flex flex-col md:flex-row md:items-center justify-between gap-2.5 text-xs">
                            <div>
                                <div class="font-bold text-stone-900 text-xs" x-text="b.booking_number"></div>
                                <div class="text-[11px] text-stone-500 mt-0.5" x-text="b.type.toUpperCase() + ' • ' + (b.created_at || '').substring(0, 10)"></div>
                                <div class="text-stone-600 mt-1">
                                    <template x-for="item in b.booking_items" :key="item.id">
                                        <span class="inline-block mr-1.5 text-[10px] bg-white px-2 py-0.5 rounded-md border border-stone-200 font-medium" x-text="item.service_item?.name"></span>
                                    </template>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-sm font-black text-rose-600" x-text="'$' + parseFloat(b.total_amount).toFixed(2)"></div>
                                <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-bold uppercase mt-1"
                                      :class="{
                                          'bg-emerald-100 text-emerald-800': b.status === 'completed',
                                          'bg-amber-100 text-amber-800': b.status === 'confirmed',
                                          'bg-stone-200 text-stone-700': b.status === 'pending',
                                          'bg-rose-100 text-rose-800': b.status === 'cancelled'
                                      }" x-text="b.status"></span>
                            </div>
                        </div>
                    </template>
                    <template x-if="!historyCustomer.bookings || historyCustomer.bookings.length === 0">
                        <p class="text-center py-6 text-stone-400 text-xs italic">No bookings recorded for this customer yet.</p>
                    </template>
                </div>

                <div class="pt-3 border-t border-stone-100 flex justify-end">
                    <button @click="historyModalOpen = false" class="btn-salon-secondary text-xs px-4 py-2">Close History</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function customersPage() {
            return {
                customerModalOpen: false,
                historyModalOpen: false,
                isEdit: false,
                customerId: null,
                form: { name: '', phone: '', email: '', address: '' },
                historyCustomer: {},

                openAddModal() {
                    this.isEdit = false;
                    this.form = { name: '', phone: '', email: '', address: '' };
                    this.customerModalOpen = true;
                },

                openEditModal(id, name, phone, email, address) {
                    this.isEdit = true;
                    this.customerId = id;
                    this.form = { name: name, phone: phone, email: email, address: address };
                    this.customerModalOpen = true;
                },

                openHistoryModal(id) {
                    fetch('/customers/' + id)
                        .then(res => res.json())
                        .then(data => {
                            if(data.success) {
                                this.historyCustomer = data.customer;
                                this.historyModalOpen = true;
                                setTimeout(() => lucide.createIcons(), 50);
                            }
                        });
                }
            }
        }
    </script>
</x-app-layout>
