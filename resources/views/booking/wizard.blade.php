<x-app-layout>
    @section('title', '6-Step Booking Wizard')

    <div x-data="bookingWizard()" class="max-w-4xl mx-auto space-y-4">

        <!-- Wizard Header Bar & 3-Option Actions Menu -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-1">
            <div>
                <h3 class="text-base sm:text-lg font-extrabold text-stone-900 tracking-tight">Interactive Booking Wizard</h3>
                <p class="text-xs text-stone-500">Select services, review total amount, and generate signed quotation PDF.</p>
            </div>

            <!-- Page Actions Dropdown Menu (3 Options) -->
            <div class="flex items-center space-x-2" x-data="{ wizardActions: false }">
                <div class="relative">
                    <button @click="wizardActions = !wizardActions" @click.away="wizardActions = false" class="btn-salon-primary text-xs">
                        <i data-lucide="wand-2" class="w-3.5 h-3.5"></i>
                        <span>Wizard Actions</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 ml-0.5"></i>
                    </button>

                    <div x-show="wizardActions" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-9 z-30 w-52 bg-white rounded-xl shadow-xl border border-stone-200 py-1 text-left">
                        
                        <button @click="wizardActions = false; step = 1; customerMode = 'new'" class="dropdown-menu-item w-full">
                            <i data-lucide="user-plus" class="w-3.5 h-3.5 text-rose-600"></i>
                            <span>Quick Register Client</span>
                        </button>

                        <button @click="wizardActions = false; clearAllSelections()" class="dropdown-menu-item w-full">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-amber-600"></i>
                            <span>Clear All Selections</span>
                        </button>

                        <a href="{{ route('services.index') }}" class="dropdown-menu-item">
                            <i data-lucide="arrow-left" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Back to Services</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wizard Progress Bar Header -->
        <div class="salon-card p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold uppercase tracking-wider text-rose-600" x-text="'Step ' + step + ' of 5'"></span>
                <span class="text-xs font-semibold text-stone-500" x-text="stepTitle"></span>
            </div>

            <!-- Progress Bar Tracker -->
            <div class="w-full bg-stone-100 h-2 rounded-full overflow-hidden flex">
                <div class="bg-gradient-to-r from-rose-500 to-pink-600 h-full transition-all duration-300 ease-out" 
                     :style="'width: ' + (step * 20) + '%'"></div>
            </div>

            <!-- Step Indicators -->
            <div class="grid grid-cols-5 gap-1.5 mt-3 text-center">
                <template x-for="(st, idx) in stepsList" :key="idx">
                    <button @click="goToStep(idx + 1)" :disabled="step < (idx + 1)" 
                            class="py-1 px-1 rounded-lg text-[10px] font-bold transition-colors text-stone-500 hover:text-stone-900"
                            :class="{
                                'text-rose-600 bg-rose-50 border border-rose-200': step === (idx + 1),
                                'text-stone-400 opacity-60': step < (idx + 1)
                            }">
                        <span x-text="(idx + 1) + '. ' + st"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- STEP 1: Select or Quick-Add Customer -->
        <div x-show="step === 1" x-transition.opacity class="salon-card p-5 space-y-5">
            <div>
                <h3 class="text-base font-extrabold text-stone-900">Step 1: Select or Add Customer</h3>
                <p class="text-xs text-stone-500 mt-0.5">Choose an existing salon client or quickly register a new customer.</p>
            </div>

            <!-- Mode Selector: Search existing vs. Quick Add -->
            <div class="flex items-center space-x-4 border-b border-stone-200 pb-3">
                <label class="flex items-center space-x-2 cursor-pointer text-xs font-semibold text-stone-700">
                    <input type="radio" name="customer_mode" value="select" x-model="customerMode" class="text-rose-600 focus:ring-rose-500">
                    <span>Select Existing Customer</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer text-xs font-semibold text-stone-700">
                    <input type="radio" name="customer_mode" value="new" x-model="customerMode" class="text-rose-600 focus:ring-rose-500">
                    <span>+ Quick Add New Customer</span>
                </label>
            </div>

            <!-- Option A: Custom Styled Searchable Dropdown -->
            <div x-show="customerMode === 'select'" class="space-y-3" x-data="{ dropdownOpen: false, customerSearch: '' }">
                <label class="block text-[10px] font-bold text-stone-400 uppercase tracking-wider">Choose Client</label>
                
                <!-- Custom Dropdown Container -->
                <div class="relative" @click.away="dropdownOpen = false">
                    
                    <!-- Trigger Button -->
                    <button type="button" 
                            @click="dropdownOpen = !dropdownOpen; if(dropdownOpen) setTimeout(() => lucide.createIcons(), 50)" 
                            class="w-full py-2.5 px-3.5 rounded-xl border border-stone-300 text-xs bg-white text-stone-800 shadow-xs hover:border-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 flex items-center justify-between transition-all group">
                        
                        <div class="flex items-center space-x-2.5 truncate">
                            <div class="w-6 h-6 rounded-lg bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                            </div>
                            
                            <template x-if="selectedCustomerId && getSelectedCustomer()">
                                <div class="truncate text-left">
                                    <span class="font-bold text-stone-900" x-text="getSelectedCustomer().name"></span>
                                    <span class="text-stone-500 text-[11px] ml-1.5" x-text="'(' + (getSelectedCustomer().phone || 'No phone') + ') — ' + (getSelectedCustomer().email || '')"></span>
                                </div>
                            </template>
                            <template x-if="!selectedCustomerId || !getSelectedCustomer()">
                                <span class="text-stone-400 font-medium">-- Click to Select Customer --</span>
                            </template>
                        </div>

                        <i data-lucide="chevron-down" 
                           class="w-4 h-4 text-stone-400 group-hover:text-stone-600 transition-transform duration-200 shrink-0 ml-2"
                           :class="dropdownOpen ? 'rotate-180 text-rose-600' : ''"></i>
                    </button>

                    <!-- Dropdown Menu Panel -->
                    <div x-show="dropdownOpen" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                         class="absolute left-0 right-0 top-full mt-1.5 z-50 bg-white rounded-2xl shadow-xl border border-stone-200/90 overflow-hidden py-2"
                         style="display: none;">
                        
                        <!-- Search Filter Bar Inside Dropdown -->
                        <div class="px-3 pb-2 pt-1 border-b border-stone-100">
                            <div class="relative">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-stone-400 absolute left-2.5 top-1/2 -translate-y-1/2"></i>
                                <input type="text" 
                                       x-model="customerSearch" 
                                       placeholder="Search customer by name, phone or email..." 
                                       class="w-full pl-8 pr-3 py-1.5 bg-stone-50 border border-stone-200 rounded-lg text-xs text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-1 focus:ring-rose-500 focus:border-rose-500">
                            </div>
                        </div>

                        <!-- Scrollable Customer Options List -->
                        <div class="max-h-56 overflow-y-auto px-1.5 pt-1.5 space-y-0.5">
                            <!-- Clear Choice Option -->
                            <button type="button" 
                                    @click="selectedCustomerId = ''; dropdownOpen = false" 
                                    class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-stone-400 hover:bg-stone-50 transition-colors flex items-center justify-between">
                                <span>-- Clear Selection --</span>
                            </button>

                            <template x-for="c in customersList.filter(item => !customerSearch || item.name.toLowerCase().includes(customerSearch.toLowerCase()) || (item.phone && item.phone.includes(customerSearch)) || (item.email && item.email.toLowerCase().includes(customerSearch.toLowerCase())))" :key="c.id">
                                <button type="button" 
                                        @click="selectedCustomerId = c.id; dropdownOpen = false" 
                                        class="w-full text-left px-3 py-2 rounded-xl text-xs transition-all flex items-center justify-between group"
                                        :class="selectedCustomerId == c.id ? 'bg-rose-50 text-rose-900 font-bold border border-rose-100' : 'hover:bg-stone-50 text-stone-700'">
                                    <div class="flex items-center space-x-2.5 truncate">
                                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold shrink-0 transition-colors"
                                             :class="selectedCustomerId == c.id ? 'bg-rose-600 text-white' : 'bg-stone-100 text-stone-600 group-hover:bg-rose-100 group-hover:text-rose-700'">
                                            <span x-text="c.name.substring(0, 2).toUpperCase()"></span>
                                        </div>
                                        <div class="truncate">
                                            <div class="font-bold text-stone-900 group-hover:text-rose-900" x-text="c.name"></div>
                                            <div class="text-[10px] text-stone-500 font-normal truncate" x-text="(c.phone || 'No phone') + ' • ' + (c.email || 'No email')"></div>
                                        </div>
                                    </div>
                                    <template x-if="selectedCustomerId == c.id">
                                        <i data-lucide="check" class="w-4 h-4 text-rose-600 shrink-0"></i>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Selected Customer Card -->
                <div x-show="selectedCustomerId && getSelectedCustomer()" class="p-3.5 bg-rose-50/70 rounded-xl border border-rose-100 text-xs space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-rose-700">Selected Client</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-200/80 text-rose-800">Active</span>
                    </div>
                    <p class="font-extrabold text-stone-900 text-sm" x-text="getSelectedCustomer()?.name"></p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 text-stone-600 text-xs pt-0.5">
                        <p x-text="'Phone: ' + (getSelectedCustomer()?.phone || 'N/A')"></p>
                        <p x-text="'Email: ' + (getSelectedCustomer()?.email || 'N/A')"></p>
                    </div>
                </div>
            </div>

            <!-- Option B: Quick Add New Customer Form -->
            <div x-show="customerMode === 'new'" class="space-y-3 bg-stone-50/60 p-4 rounded-xl border border-stone-200/80">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Full Name *</label>
                        <input type="text" x-model="newCustomer.name" placeholder="Sophia Montgomery" class="w-full py-2 px-3 rounded-xl border border-stone-300 text-xs focus:ring-rose-500 focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Phone Number *</label>
                        <input type="text" x-model="newCustomer.phone" placeholder="+1 (555) 019-2834" class="w-full py-2 px-3 rounded-xl border border-stone-300 text-xs focus:ring-rose-500 focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Email Address</label>
                        <input type="email" x-model="newCustomer.email" placeholder="sophia@example.com" class="w-full py-2 px-3 rounded-xl border border-stone-300 text-xs focus:ring-rose-500 focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Address</label>
                        <input type="text" x-model="newCustomer.address" placeholder="104 Beverly Hills Dr" class="w-full py-2 px-3 rounded-xl border border-stone-300 text-xs focus:ring-rose-500 focus:border-rose-500">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="quickSaveCustomer()" class="btn-salon-secondary text-xs py-1.5 px-3">
                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                        <span>Register & Attach Customer</span>
                    </button>
                </div>
            </div>

            <!-- Step 1 Navigation Buttons -->
            <div class="flex justify-between pt-4 border-t border-stone-100">
                <button type="button" disabled class="btn-salon-secondary opacity-50 cursor-not-allowed text-xs py-1.5">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back</span>
                </button>
                
                <button type="button" @click="nextStep()" :disabled="!selectedCustomerId" class="btn-salon-primary text-xs py-1.5">
                    <span>Next: Hair Care</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        </div>

        <!-- MODULE CHECKLIST TEMPLATES FOR STEPS 2, 3, 4 -->
        @foreach($modules as $mIndex => $module)
            <div x-show="step === {{ $mIndex + 2 }}" x-transition.opacity class="salon-card p-5 space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold shrink-0">
                            <i data-lucide="{{ $module->icon ?: 'sparkles' }}" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-stone-900">Step {{ $mIndex + 2 }}: {{ $module->name }}</h3>
                            <p class="text-xs text-stone-500">{{ $module->description }}</p>
                        </div>
                    </div>

                    <!-- Live Running Subtotal Badge -->
                    <div class="text-right bg-stone-100 px-3 py-1.5 rounded-xl border border-stone-200">
                        <span class="text-[9px] uppercase font-bold text-stone-400 block">Total Selected</span>
                        <span class="text-base font-black text-rose-600" x-text="'$' + calculateTotal().toFixed(2)"></span>
                    </div>
                </div>

                <!-- Sub-Modules Checklist -->
                <div class="space-y-4">
                    @foreach($module->subModules as $subModule)
                        <div class="p-4 rounded-xl bg-stone-50/70 border border-stone-200/80">
                            
                            <!-- Sub-Module Header + "Select All" Checkbox -->
                            <div class="flex items-center justify-between pb-2.5 border-b border-stone-200/70 mb-3">
                                <h4 class="font-bold text-xs text-stone-900 flex items-center">
                                    <i data-lucide="folder" class="w-3.5 h-3.5 mr-1.5 text-rose-500"></i>
                                    <span>{{ $subModule->name }}</span>
                                </h4>

                                <!-- Select All Checkbox -->
                                <label class="inline-flex items-center space-x-1.5 text-[11px] font-bold text-rose-600 cursor-pointer bg-white px-2.5 py-0.5 rounded-md border border-stone-200 hover:bg-rose-50 transition-colors">
                                    <input type="checkbox" 
                                           @change="toggleSelectAll({{ $subModule->id }}, $event.target.checked, @json($subModule->serviceItems->pluck('id')))"
                                           :checked="isSubModuleAllSelected(@json($subModule->serviceItems->pluck('id')))"
                                           class="rounded border-stone-300 text-rose-600 focus:ring-rose-500 w-3.5 h-3.5">
                                    <span>Select All ({{ $subModule->serviceItems->count() }})</span>
                                </label>
                            </div>

                            <!-- 5 Items Checklist -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                @foreach($subModule->serviceItems as $item)
                                    <label class="p-2.5 bg-white rounded-lg border border-stone-200 flex items-center justify-between cursor-pointer hover:border-rose-300 transition-all select-none shadow-2xs"
                                           :class="{ 'border-rose-500 bg-rose-50/30 ring-1 ring-rose-500': selectedItems.includes({{ $item->id }}) }">
                                        <div class="flex items-center space-x-2 truncate">
                                            <input type="checkbox" 
                                                   value="{{ $item->id }}" 
                                                   x-model="selectedItems"
                                                   @change="updateItemDetails({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }}, '{{ addslashes($module->name) }}', '{{ addslashes($subModule->name) }}')"
                                                   class="rounded border-stone-300 text-rose-600 focus:ring-rose-500 w-3.5 h-3.5">
                                            <span class="text-xs font-semibold text-stone-800 truncate">{{ $item->name }}</span>
                                        </div>
                                        <span class="text-xs font-extrabold text-rose-600 ml-1.5 shrink-0">
                                            ${{ number_format($item->price, 2) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between pt-4 border-t border-stone-100">
                    <button type="button" @click="prevStep()" class="btn-salon-secondary text-xs py-1.5">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                        <span>Back</span>
                    </button>
                    
                    <button type="button" @click="nextStep()" class="btn-salon-primary text-xs py-1.5">
                        <span>Next: {{ $mIndex < 2 ? $modules[$mIndex + 1]->name : 'Review Summary' }}</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </div>
        @endforeach

        <!-- STEP 5: Review Summary Screen -->
        <div x-show="step === 5" x-transition.opacity class="salon-card p-5 space-y-5">
            <div>
                <h3 class="text-base font-extrabold text-stone-900">Step 5: Review Booking Summary</h3>
                <p class="text-xs text-stone-500 mt-0.5">Review all selected service items, customer details, and total running balance.</p>
            </div>

            <!-- Customer Summary Card -->
            <div class="p-3 bg-stone-50 rounded-xl border border-stone-200 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-stone-400 block">Client Profile</span>
                    <h4 class="font-bold text-stone-900 text-xs" x-text="getSelectedCustomer()?.name"></h4>
                    <p class="text-[11px] text-stone-500" x-text="getSelectedCustomer()?.phone + ' | ' + (getSelectedCustomer()?.email || 'No email')"></p>
                </div>
                <button type="button" @click="step = 1" class="text-xs font-semibold text-rose-600 hover:text-rose-800 underline">
                    Change Client
                </button>
            </div>

            <!-- Grouped Selected Items List -->
            <div class="space-y-3">
                <h4 class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Selected Services Breakdown</h4>

                <template x-if="selectedItems.length === 0">
                    <div class="p-6 text-center bg-rose-50/50 rounded-xl border border-dashed border-rose-200 text-rose-700 text-xs">
                        <i data-lucide="alert-circle" class="w-6 h-6 mx-auto mb-1 text-rose-500"></i>
                        <p class="font-bold">No service items selected yet!</p>
                        <p class="text-[11px] text-stone-500 mt-0.5">Go back to Steps 2-4 and select at least one item.</p>
                    </div>
                </template>

                <template x-if="selectedItems.length > 0">
                    <div class="divide-y divide-stone-100 border border-stone-200 rounded-xl overflow-hidden bg-white">
                        <template x-for="item in getSelectedItemsList()" :key="item.id">
                            <div class="p-3 flex items-center justify-between text-xs hover:bg-stone-50/50 transition-colors">
                                <div>
                                    <span class="font-bold text-stone-900 block" x-text="item.name"></span>
                                    <span class="text-stone-400 text-[10px]" x-text="item.module + ' → ' + item.subModule"></span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="font-black text-stone-900" x-text="'$' + parseFloat(item.price).toFixed(2)"></span>
                                    <button type="button" @click="removeItem(item.id)" class="text-stone-300 hover:text-rose-600">
                                        <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Total Calculation Box -->
            <div class="p-4 bg-gradient-to-r from-stone-900 to-stone-800 rounded-xl text-white flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-semibold text-rose-300 uppercase tracking-wider block">Grand Total</span>
                    <span class="text-[11px] text-stone-300">All selected treatments</span>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black text-rose-400" x-text="'$' + calculateTotal().toFixed(2)"></span>
                </div>
            </div>

            <!-- Step 5 Navigation Buttons -->
            <div class="flex justify-between pt-4 border-t border-stone-100">
                <button type="button" @click="prevStep()" class="btn-salon-secondary text-xs py-1.5">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back</span>
                </button>
                
                <!-- "Generate" Button -->
                <button type="button" 
                        @click="openGenerateModal()" 
                        :disabled="selectedItems.length === 0" 
                        class="btn-salon-primary text-xs py-1.5">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    <span>Generate Transaction...</span>
                </button>
            </div>
        </div>

        <!-- STEP 6: ANIMATED POPUP MODAL (LEAD vs QUOTATION SIGNATURE) -->
        <div x-show="generateModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <div class="max-w-lg w-full bg-white rounded-2xl p-6 relative shadow-2xl border border-stone-100 space-y-5" @click.away="generateModalOpen = false">
                
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div>
                        <h3 class="text-lg font-bold text-stone-900 tracking-tight">Choose Output Action</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Save as lead inquiry or generate official Quotation PDF.</p>
                    </div>
                    <button @click="generateModalOpen = false" class="w-8 h-8 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- 2 Primary Choices -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    
                    <!-- Choice A: Save as Lead -->
                    <div @click="actionChoice = 'lead'" 
                         class="p-4 rounded-xl border-2 cursor-pointer transition-all flex flex-col justify-between"
                         :class="actionChoice === 'lead' ? 'border-rose-500 bg-rose-50/50 shadow-xs' : 'border-stone-200/80 hover:border-stone-300 bg-stone-50/30'">
                        <div>
                            <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center mb-3">
                                <i data-lucide="user-check" class="w-4.5 h-4.5"></i>
                            </div>
                            <h4 class="font-bold text-stone-900 text-sm">Save as Lead</h4>
                            <p class="text-xs text-stone-500 mt-1 leading-relaxed">Save database record only without downloading PDF.</p>
                        </div>
                    </div>

                    <!-- Choice B: Generate Quotation PDF with Digital Signature -->
                    <div @click="actionChoice = 'quotation'; initSignaturePad();" 
                         class="p-4 rounded-xl border-2 cursor-pointer transition-all flex flex-col justify-between"
                         :class="actionChoice === 'quotation' ? 'border-purple-600 bg-purple-50/50 shadow-xs' : 'border-stone-200/80 hover:border-stone-300 bg-stone-50/30'">
                        <div>
                            <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mb-3">
                                <i data-lucide="file-signature" class="w-4.5 h-4.5"></i>
                            </div>
                            <h4 class="font-bold text-stone-900 text-sm">Quotation PDF</h4>
                            <p class="text-xs text-stone-500 mt-1 leading-relaxed">Capture digital signature & download PDF.</p>
                        </div>
                    </div>
                </div>

                <!-- Digital Signature Canvas Area -->
                <div x-show="actionChoice === 'quotation'" class="p-4 bg-purple-50/40 rounded-xl border border-purple-200/80 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-purple-900 tracking-wide">
                            Customer Digital Signature
                        </label>
                        <button type="button" @click="clearSignature()" class="text-xs text-stone-500 hover:text-rose-600 font-medium">
                            Clear
                        </button>
                    </div>

                    <div class="border border-purple-200 rounded-xl bg-white overflow-hidden shadow-2xs flex justify-center">
                        <canvas id="signatureCanvas" width="400" height="120" class="touch-none cursor-crosshair w-full h-[120px]"></canvas>
                    </div>
                </div>

                <!-- Modal Submit Button -->
                <div class="pt-4 border-t border-stone-100 flex items-center justify-end space-x-3">
                    <button type="button" @click="generateModalOpen = false" class="btn-salon-secondary text-xs px-4 py-2">Cancel</button>
                    
                    <button type="button" 
                            @click="submitWizard()" 
                            :disabled="isSubmitting" 
                            class="btn-salon-primary text-xs px-4 py-2">
                        <span x-show="!isSubmitting" x-text="actionChoice === 'lead' ? 'Confirm Lead' : 'Sign & Download PDF'"></span>
                        <span x-show="isSubmitting">Processing...</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Alpine Controller Script for Wizard -->
    <script>
        function bookingWizard() {
            return {
                step: 1,
                stepsList: ['Customer', 'Hair Care', 'Skin Care', 'Body Treatment', 'Review & Generate'],
                stepTitle: 'Select or Register Salon Client',
                customerMode: 'select',
                selectedCustomerId: '',
                newCustomer: { name: '', phone: '', email: '', address: '' },
                customersList: @json($customers),
                
                selectedItems: [],
                itemDetailsMap: {},

                generateModalOpen: false,
                actionChoice: 'lead',
                signaturePadInstance: null,
                isSubmitting: false,

                init() {
                    const rawModules = @json($modules);
                    rawModules.forEach(m => {
                        m.sub_modules.forEach(sm => {
                            sm.service_items.forEach(item => {
                                this.itemDetailsMap[item.id] = {
                                    id: item.id,
                                    name: item.name,
                                    price: parseFloat(item.price),
                                    module: m.name,
                                    subModule: sm.name
                                };
                            });
                        });
                    });
                },

                clearAllSelections() {
                    this.selectedItems = [];
                    alert('All selected service items cleared.');
                },

                goToStep(s) {
                    if (s <= this.step || (s === 2 && this.selectedCustomerId)) {
                        this.step = s;
                        this.updateStepTitle();
                    }
                },

                nextStep() {
                    if (this.step === 1 && !this.selectedCustomerId) {
                        alert('Please select or add a customer first.');
                        return;
                    }
                    if (this.step < 5) {
                        this.step++;
                        this.updateStepTitle();
                    }
                },

                prevStep() {
                    if (this.step > 1) {
                        this.step--;
                        this.updateStepTitle();
                    }
                },

                updateStepTitle() {
                    const titles = [
                        'Select or Register Salon Client',
                        'Select Hair Care Services',
                        'Select Skin Care Services',
                        'Select Body Treatment Services',
                        'Review & Finalize Order'
                    ];
                    this.stepTitle = titles[this.step - 1];
                    setTimeout(() => lucide.createIcons(), 50);
                },

                getSelectedCustomer() {
                    return this.customersList.find(c => c.id == this.selectedCustomerId);
                },

                quickSaveCustomer() {
                    if(!this.newCustomer.name || !this.newCustomer.phone) {
                        alert('Name and Phone are required.');
                        return;
                    }

                    fetch('{{ route("customers.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.newCustomer)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            this.customersList.push(data.customer);
                            this.selectedCustomerId = data.customer.id;
                            this.customerMode = 'select';
                            alert('Customer registered successfully!');
                        }
                    });
                },

                updateItemDetails(id, name, price, moduleName, subModuleName) {
                    this.itemDetailsMap[id] = { id, name, price: parseFloat(price), module: moduleName, subModule: subModuleName };
                },

                toggleSelectAll(subModuleId, checked, itemIds) {
                    itemIds.forEach(id => {
                        const numId = parseInt(id);
                        if(checked) {
                            if(!this.selectedItems.includes(numId)) this.selectedItems.push(numId);
                        } else {
                            this.selectedItems = this.selectedItems.filter(i => i !== numId);
                        }
                    });
                },

                isSubModuleAllSelected(itemIds) {
                    if(!itemIds || itemIds.length === 0) return false;
                    return itemIds.every(id => this.selectedItems.includes(parseInt(id)));
                },

                getSelectedItemsList() {
                    return this.selectedItems.map(id => this.itemDetailsMap[id]).filter(Boolean);
                },

                removeItem(id) {
                    this.selectedItems = this.selectedItems.filter(i => i !== id);
                },

                calculateTotal() {
                    return this.getSelectedItemsList().reduce((sum, item) => sum + item.price, 0);
                },

                openGenerateModal() {
                    this.generateModalOpen = true;
                    this.actionChoice = 'lead';
                    setTimeout(() => lucide.createIcons(), 50);
                },

                initSignaturePad() {
                    this.$nextTick(() => {
                        const canvas = document.getElementById('signatureCanvas');
                        if (canvas && !this.signaturePadInstance) {
                            this.signaturePadInstance = new SignaturePad(canvas, {
                                backgroundColor: 'rgb(255, 255, 255)',
                                penColor: 'rgb(15, 23, 42)'
                            });
                        }
                    });
                },

                clearSignature() {
                    if (this.signaturePadInstance) {
                        this.signaturePadInstance.clear();
                    }
                },

                submitWizard() {
                    if(!this.selectedCustomerId) {
                        alert('Customer is required.');
                        return;
                    }
                    if(this.selectedItems.length === 0) {
                        alert('Please select at least one service item.');
                        return;
                    }

                    this.isSubmitting = true;

                    if (this.actionChoice === 'lead') {
                        fetch('{{ route("booking.store-lead") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                customer_id: this.selectedCustomerId,
                                items: this.selectedItems
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.isSubmitting = false;
                            if(data.success) {
                                window.location.href = data.redirect_url;
                            }
                        })
                        .catch(() => this.isSubmitting = false);
                    } else {
                        if(!this.signaturePadInstance || this.signaturePadInstance.isEmpty()) {
                            alert('Please draw a customer signature on the canvas first.');
                            this.isSubmitting = false;
                            return;
                        }

                        const signatureData = this.signaturePadInstance.toDataURL();

                        fetch('{{ route("booking.store-quotation") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                customer_id: this.selectedCustomerId,
                                items: this.selectedItems,
                                signature: signatureData
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.isSubmitting = false;
                            if(data.success) {
                                window.open(data.pdf_url, '_blank');
                                window.location.href = data.redirect_url;
                            }
                        })
                        .catch(() => this.isSubmitting = false);
                    }
                }
            }
        }
    </script>
</x-app-layout>
