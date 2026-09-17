@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between pt-4 border-t border-stone-100 sm:px-1">
        
        <!-- Record Counter Summary -->
        <div>
            <p class="text-xs text-stone-500 font-medium">
                {!! __('Showing') !!}
                <span class="font-bold text-stone-900">{{ $paginator->firstItem() }}</span>
                {!! __('to') !!}
                <span class="font-bold text-stone-900">{{ $paginator->lastItem() }}</span>
                {!! __('of') !!}
                <span class="font-bold text-stone-900">{{ $paginator->total() }}</span>
                {!! __('records') !!}
            </p>
        </div>

        <!-- Single Current Page + Next Navigation Controls -->
        <div>
            <div class="relative z-0 inline-flex items-center space-x-1.5">
                
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <span class="relative inline-flex items-center px-3 py-1.5 rounded-xl border border-stone-200 bg-stone-50 text-stone-300 text-xs font-semibold cursor-not-allowed opacity-60">
                            <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
                            <span>Previous</span>
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" data-ajax-pagination="true" rel="prev" class="relative inline-flex items-center px-3 py-1.5 rounded-xl border border-stone-200 bg-white text-xs font-semibold text-stone-700 hover:bg-stone-50 hover:text-stone-900 transition-all shadow-2xs" aria-label="{{ __('pagination.previous') }}">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1 text-stone-500"></i>
                        <span>Previous</span>
                    </a>
                @endif

                {{-- Single Current Page Indicator Only (e.g., "1", "2", "3") --}}
                <span aria-current="page">
                    <span class="relative inline-flex items-center px-3.5 py-1.5 text-xs font-black text-white bg-gradient-to-r from-rose-600 to-rose-500 border border-rose-600 rounded-xl shadow-xs cursor-default">
                        {{ $paginator->currentPage() }}
                    </span>
                </span>

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" data-ajax-pagination="true" rel="next" class="relative inline-flex items-center px-3 py-1.5 rounded-xl border border-stone-200 bg-white text-xs font-bold text-rose-600 hover:bg-rose-50 hover:border-rose-300 transition-all shadow-2xs" aria-label="{{ __('pagination.next') }}">
                        <span>Next</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1 text-rose-600"></i>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                        <span class="relative inline-flex items-center px-3 py-1.5 rounded-xl border border-stone-200 bg-stone-50 text-stone-300 text-xs font-semibold cursor-not-allowed opacity-60">
                            <span>Next</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
                        </span>
                    </span>
                @endif

            </div>
        </div>
    </nav>
@endif
