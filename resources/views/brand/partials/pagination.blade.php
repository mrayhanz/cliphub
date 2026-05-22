@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        {{-- Mobile View Layout (Simple Previous/Next Buttons) --}}
        <div class="flex justify-between flex-1 sm:hidden gap-3">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2.5 text-xs font-black text-slate-600 bg-[#111] border border-white/5 rounded-xl cursor-default select-none">
                    « {{ __('Sebelumnya') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2.5 text-xs font-black text-slate-300 hover:text-white bg-[#111] border border-white/10 hover:border-emerald-500/20 rounded-xl transition-all duration-200 active:scale-95">
                    « {{ __('Sebelumnya') }}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2.5 text-xs font-black text-slate-300 hover:text-white bg-[#111] border border-white/10 hover:border-emerald-500/20 rounded-xl transition-all duration-200 active:scale-95">
                    {{ __('Berikutnya') }} »
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2.5 text-xs font-black text-slate-600 bg-[#111] border border-white/5 rounded-xl cursor-default select-none">
                    {{ __('Berikutnya') }} »
                </span>
            @endif
        </div>

        {{-- Desktop & Tablet View Layout (Rich pagination details and number indicators) --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-6">
            <div>
                <p class="text-xs text-slate-500 font-bold">
                    {!! __('Menampilkan') !!}
                    <span class="font-black text-slate-300">{{ $paginator->firstItem() }}</span>
                    {!! __('sampai') !!}
                    <span class="font-black text-slate-300">{{ $paginator->lastItem() }}</span>
                    {!! __('dari') !!}
                    <span class="font-black text-slate-300">{{ $paginator->total() }}</span>
                    {!! __('hasil') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-xl gap-1">
                    {{-- Previous Page Chevron --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('Sebelumnya') }}">
                            <span class="relative inline-flex items-center p-2.5 text-slate-600 bg-[#111] border border-white/5 rounded-xl cursor-default" aria-hidden="true">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center p-2.5 text-slate-400 hover:text-white bg-[#111] border border-white/10 hover:border-emerald-500/20 rounded-xl transition-all duration-200 active:scale-95" aria-label="{{ __('Sebelumnya') }}">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    {{-- Numeric Page Buttons --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-4 py-2 text-xs font-black text-slate-600 bg-[#111] border border-white/5 rounded-xl cursor-default select-none">{{ $element }}</span>
                        @endif

                        {{-- Links Array --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 text-xs font-black text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 rounded-xl cursor-default select-none shadow-[0_0_15px_rgba(16,185,129,0.15)]">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-xs font-black text-slate-400 hover:text-white bg-[#111] border border-white/10 hover:border-emerald-500/20 rounded-xl transition-all duration-200 active:scale-95">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Chevron --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center p-2.5 text-slate-400 hover:text-white bg-[#111] border border-white/10 hover:border-emerald-500/20 rounded-xl transition-all duration-200 active:scale-95" aria-label="{{ __('Berikutnya') }}">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('Berikutnya') }}">
                            <span class="relative inline-flex items-center p-2.5 text-slate-600 bg-[#111] border border-white/5 rounded-xl cursor-default" aria-hidden="true">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
