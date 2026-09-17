<x-app-layout>
    @section('title', 'Service Pricing Management')

    <div class="space-y-5" x-data="pricingPage()">

        <!-- Page Header & Actions Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-stone-200/60">
            <div>
                <h3 class="text-base sm:text-lg font-extrabold text-stone-900 tracking-tight">Service Catalog & Pricing</h3>
                <p class="text-xs text-stone-500">Manage Main Modules, Sub-Modules, and individual service prices.</p>
            </div>

            <!-- Page Actions Dropdown Menu (3 Options) -->
            <div class="flex items-center space-x-2" x-data="{ pricingActions: false }">
                <div class="relative">
                    <button @click="pricingActions = !pricingActions" @click.away="pricingActions = false" class="btn-salon-primary text-xs">
                        <i data-lucide="folder-cog" class="w-3.5 h-3.5"></i>
                        <span>Catalog Actions</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 ml-0.5"></i>
                    </button>

                    <div x-show="pricingActions" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-9 z-30 w-52 bg-white rounded-xl shadow-xl border border-stone-200 py-1 text-left">
                        
                        <button @click="pricingActions = false; openAddMainModal()" class="dropdown-menu-item w-full">
                            <i data-lucide="plus" class="w-3.5 h-3.5 text-rose-600"></i>
                            <span>Add Main Module</span>
                        </button>

                        <button @click="pricingActions = false; openAddSubModal()" class="dropdown-menu-item w-full">
                            <i data-lucide="folder-plus" class="w-3.5 h-3.5 text-amber-600"></i>
                            <span>Add Sub-Module</span>
                        </button>

                        <button @click="pricingActions = false; openAddItemModal()" class="dropdown-menu-item w-full">
                            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Add Service Item</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Filter Pills Bar -->
        <div class="flex items-center space-x-2 overflow-x-auto pb-1 scrollbar-none">
            <button @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' 
                        ? 'bg-stone-900 text-white shadow-xs' 
                        : 'bg-white text-stone-600 hover:bg-stone-100 hover:text-stone-900 border border-stone-200/80'"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all shrink-0">
                All Categories
            </button>
            @foreach($modules as $m)
                <button @click="activeCategory = {{ $m->id }}"
                        :class="activeCategory === {{ $m->id }} 
                            ? 'bg-stone-900 text-white shadow-xs' 
                            : 'bg-white text-stone-600 hover:bg-stone-100 hover:text-stone-900 border border-stone-200/80'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5 shrink-0">
                    <i data-lucide="{{ $m->icon ?: 'sparkles' }}" class="w-3.5 h-3.5"></i>
                    <span>{{ $m->name }}</span>
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                          :class="activeCategory === {{ $m->id }} ? 'bg-stone-800 text-stone-200' : 'bg-stone-100 text-stone-500'">
                        {{ $m->subModules->flatMap->serviceItems->count() }}
                    </span>
                </button>
            @endforeach
        </div>

        <!-- Main Modules & Modern Minimal Cards Display -->
        <div class="space-y-6">
            @foreach($modules as $module)
                <div x-show="activeCategory === 'all' || activeCategory === {{ $module->id }}"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-3">
                    
                    <!-- Section Title Bar -->
                    <div class="flex items-center justify-between pb-2 border-b border-stone-200/70">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-7 h-7 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                                <i data-lucide="{{ $module->icon ?: 'sparkles' }}" class="w-3.5 h-3.5"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-stone-900 tracking-tight">{{ $module->name }}</h4>
                                    <span class="text-[10px] font-medium text-stone-400">&bull; {{ $module->subModules->count() }} Sub-Categories</span>
                                </div>
                                @if($module->description)
                                    <p class="text-[11px] text-stone-400 line-clamp-1">{{ $module->description }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center space-x-1">
                            <button @click="openEditMainModal({{ $module->id }}, '{{ addslashes($module->name) }}', '{{ addslashes($module->description) }}', '{{ $module->icon }}')" class="w-7 h-7 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 flex items-center justify-center transition-colors" title="Edit Main Module">
                                <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                            </button>
                            <form action="{{ route('pricing.main-module.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Delete main module and all its contents?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-lg text-stone-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors" title="Delete Main Module">
                                    <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Clean, Modern Sub-Category Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3.5">
                        @foreach($module->subModules as $subModule)
                            <div class="bg-white rounded-2xl border border-stone-200/80 shadow-2xs hover:border-stone-300 hover:shadow-xs transition-all flex flex-col justify-between overflow-hidden h-full">
                                <div>
                                    <!-- Card Header -->
                                    <div class="px-3.5 py-2.5 bg-stone-50/70 border-b border-stone-100 flex items-center justify-between">
                                        <div class="flex items-center space-x-2 min-w-0">
                                            <div class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></div>
                                            <h5 class="font-bold text-xs text-stone-900 tracking-tight truncate">{{ $subModule->name }}</h5>
                                        </div>

                                        <div class="flex items-center space-x-1 shrink-0 ml-1">
                                            <span class="text-[10px] font-medium text-stone-400 mr-0.5">{{ $subModule->serviceItems->count() }} items</span>
                                            <button @click="openEditSubModal({{ $subModule->id }}, '{{ addslashes($subModule->name) }}')" class="w-5 h-5 rounded text-stone-400 hover:text-stone-700 hover:bg-stone-200/50 flex items-center justify-center transition-colors" title="Edit Sub-Category">
                                                <i data-lucide="edit" class="w-2.5 h-2.5"></i>
                                            </button>
                                            <form action="{{ route('pricing.sub-module.destroy', $subModule->id) }}" method="POST" onsubmit="return confirm('Delete sub-category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-5 h-5 rounded text-stone-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors" title="Delete Sub-Category">
                                                    <i data-lucide="trash-2" class="w-2.5 h-2.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Service Items Clean List -->
                                    <div class="divide-y divide-stone-100/70 flex-1">
                                        @forelse($subModule->serviceItems as $item)
                                            <div class="px-3.5 py-2 flex items-center justify-between hover:bg-rose-50/20 transition-colors group">
                                                <div class="min-w-0 pr-2 truncate">
                                                    <span class="text-xs font-medium text-stone-800 group-hover:text-stone-900 block truncate">{{ $item->name }}</span>
                                                </div>
                                                <div class="flex items-center space-x-1.5 shrink-0">
                                                    <span class="text-xs font-bold text-stone-900 group-hover:text-rose-600 transition-colors">${{ number_format($item->price, 2) }}</span>
                                                    
                                                    <div class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity ml-1">
                                                        <button @click="openEditItemModal({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})" class="w-5 h-5 rounded text-stone-400 hover:text-stone-700 hover:bg-stone-100 flex items-center justify-center transition-colors" title="Edit Price">
                                                            <i data-lucide="edit-3" class="w-2.5 h-2.5"></i>
                                                        </button>
                                                        <form action="{{ route('pricing.service-item.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete service?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-5 h-5 rounded text-stone-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors" title="Delete Service">
                                                                <i data-lucide="x" class="w-2.5 h-2.5"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="py-4 text-center text-xs text-stone-400 italic">No services in this category yet.</div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Add Item Action -->
                                <div class="p-2 border-t border-stone-100 bg-stone-50/40">
                                    <button @click="openAddItemModal({{ $subModule->id }})" class="w-full py-1.5 px-3 rounded-lg text-xs font-medium text-stone-500 hover:text-rose-600 hover:bg-rose-50/60 border border-dashed border-stone-200 hover:border-rose-300 flex items-center justify-center gap-1.5 transition-all">
                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                        <span>Add Service</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- MODALS FOR PRICING MANAGEMENT -->

        <!-- 1. Add / Edit Main Module Modal -->
        <div x-show="mainModuleModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <div class="max-w-sm w-full bg-white rounded-2xl p-6 relative shadow-2xl border border-stone-100 space-y-4" @click.away="mainModuleModalOpen = false">
                <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                    <h3 class="text-base font-bold text-stone-900" x-text="isEditMain ? 'Edit Main Module' : 'Create Main Module'"></h3>
                    <button @click="mainModuleModalOpen = false" class="w-7 h-7 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form :action="isEditMain ? '/pricing/main-module/' + mainModuleId : '{{ route('pricing.main-module.store') }}'" method="POST" class="space-y-3.5">
                    @csrf
                    <template x-if="isEditMain">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Module Name</label>
                        <input type="text" name="name" x-model="mainForm.name" required class="salon-input w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Description</label>
                        <textarea name="description" x-model="mainForm.description" rows="2" class="w-full py-2 px-3 rounded-xl border border-stone-300 text-xs focus:ring-rose-500 focus:border-rose-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Lucide Icon Name</label>
                        <input type="text" name="icon" x-model="mainForm.icon" placeholder="scissors, sparkles, heart-pulse..." class="salon-input w-full">
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-stone-100">
                        <button type="button" @click="mainModuleModalOpen = false" class="btn-salon-secondary text-xs px-4 py-2">Cancel</button>
                        <button type="submit" class="btn-salon-primary text-xs px-4 py-2">Save Module</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Add / Edit Sub Module Modal -->
        <div x-show="subModuleModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <div class="max-w-sm w-full bg-white rounded-2xl p-6 relative shadow-2xl border border-stone-100 space-y-4" @click.away="subModuleModalOpen = false">
                <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                    <h3 class="text-base font-bold text-stone-900" x-text="isEditSub ? 'Edit Sub-Module' : 'Create Sub-Module'"></h3>
                    <button @click="subModuleModalOpen = false" class="w-7 h-7 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form :action="isEditSub ? '/pricing/sub-module/' + subModuleId : '{{ route('pricing.sub-module.store') }}'" method="POST" class="space-y-3.5">
                    @csrf
                    <template x-if="isEditSub">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <template x-if="!isEditSub">
                        <div>
                            <label class="block text-xs font-semibold text-stone-600 mb-1">Parent Main Module</label>
                            <select name="main_module_id" class="salon-select w-full">
                                @foreach($modules as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>

                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Sub-Module Name</label>
                        <input type="text" name="name" x-model="subForm.name" required class="salon-input w-full">
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-stone-100">
                        <button type="button" @click="subModuleModalOpen = false" class="btn-salon-secondary text-xs px-4 py-2">Cancel</button>
                        <button type="submit" class="btn-salon-primary text-xs px-4 py-2">Save Sub-Module</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Add / Edit Service Item Modal -->
        <div x-show="itemModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-md" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <div class="max-w-sm w-full bg-white rounded-2xl p-6 relative shadow-2xl border border-stone-100 space-y-4" @click.away="itemModalOpen = false">
                <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                    <h3 class="text-base font-bold text-stone-900" x-text="isEditItem ? 'Edit Service Item' : 'Create Service Item'"></h3>
                    <button @click="itemModalOpen = false" class="w-7 h-7 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-stone-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form :action="isEditItem ? '/pricing/service-item/' + itemId : '{{ route('pricing.service-item.store') }}'" method="POST" class="space-y-3.5">
                    @csrf
                    <template x-if="isEditItem">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <template x-if="!isEditItem">
                        <div>
                            <label class="block text-xs font-semibold text-stone-600 mb-1">Target Sub-Module</label>
                            <select name="sub_module_id" x-model="itemForm.sub_module_id" class="salon-select w-full">
                                @foreach($modules as $m)
                                    <optgroup label="{{ $m->name }}">
                                        @foreach($m->subModules as $sm)
                                            <option value="{{ $sm->id }}">{{ $sm->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </template>

                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Service Item Name</label>
                        <input type="text" name="name" x-model="itemForm.name" required placeholder="Executive Cut" class="salon-input w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-600 mb-1">Price ($ USD)</label>
                        <input type="number" step="0.01" name="price" x-model="itemForm.price" required placeholder="45.00" class="salon-input w-full">
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-stone-100">
                        <button type="button" @click="itemModalOpen = false" class="btn-salon-secondary text-xs px-4 py-2">Cancel</button>
                        <button type="submit" class="btn-salon-primary text-xs px-4 py-2">Save Service Item</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function pricingPage() {
            return {
                activeCategory: 'all',
                mainModuleModalOpen: false,
                isEditMain: false,
                mainModuleId: null,
                mainForm: { name: '', description: '', icon: '' },

                subModuleModalOpen: false,
                isEditSub: false,
                subModuleId: null,
                subForm: { name: '' },

                itemModalOpen: false,
                isEditItem: false,
                itemId: null,
                itemForm: { sub_module_id: null, name: '', price: '' },

                openAddMainModal() {
                    this.isEditMain = false;
                    this.mainForm = { name: '', description: '', icon: 'sparkles' };
                    this.mainModuleModalOpen = true;
                },

                openEditMainModal(id, name, desc, icon) {
                    this.isEditMain = true;
                    this.mainModuleId = id;
                    this.mainForm = { name: name, description: desc, icon: icon };
                    this.mainModuleModalOpen = true;
                },

                openAddSubModal() {
                    this.isEditSub = false;
                    this.subForm = { name: '' };
                    this.subModuleModalOpen = true;
                },

                openEditSubModal(id, name) {
                    this.isEditSub = true;
                    this.subModuleId = id;
                    this.subForm = { name: name };
                    this.subModuleModalOpen = true;
                },

                openAddItemModal(subModuleId = null) {
                    this.isEditItem = false;
                    this.itemForm = { sub_module_id: subModuleId, name: '', price: '' };
                    this.itemModalOpen = true;
                },

                openEditItemModal(id, name, price) {
                    this.isEditItem = true;
                    this.itemId = id;
                    this.itemForm = { name: name, price: price };
                    this.itemModalOpen = true;
                }
            }
        }
    </script>
</x-app-layout>
