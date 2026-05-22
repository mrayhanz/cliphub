{{-- 
    Reusable Filter & Search Bar Form Component
    Expected Variables:
    - $action (string) Route/URL for form submission
    - $filters (array) Key-value pairs for status filters ['value' => ['label' => 'Label', 'icon' => 'lucide-icon-name']]
    - $currentStatus (string|null) The currently selected status filter
    - $search (string|null) The currently searched text
    - $searchPlaceholder (string|null) Placeholder for search input
--}}
<form id="filter-form-{{ Str::random(5) }}" method="GET" action="{{ $action }}" class="bg-[#111] rounded-2xl p-4 lg:p-5 border border-white/5 flex {{ isset($compact) && $compact ? 'flex-col' : 'flex-col xl:flex-row' }} justify-between items-start xl:items-center gap-5 mb-8 animate-fade-in-up">
    <input type="hidden" name="status" id="filter-status-{{ md5($action) }}" value="{{ $currentStatus ?? '' }}">
    
    <div class="flex gap-3 overflow-x-auto [&::-webkit-scrollbar]:hidden w-full {{ isset($compact) && $compact ? '' : 'xl:w-auto xl:flex-1' }}">
        @foreach($filters as $value => $data)
            <button type="button" onclick="document.getElementById('filter-status-{{ md5($action) }}').value = '{{ $value }}'; this.form.submit();" 
                class="px-5 py-3 rounded-xl text-xs lg:text-sm font-bold transition-all duration-200 whitespace-nowrap flex items-center gap-2 {{ ((string)$currentStatus === (string)$value) ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 shadow-[0_0_15px_rgba(16,185,129,0.1)]' : 'bg-transparent text-zinc-500 hover:text-zinc-100 hover:bg-white/5 border border-transparent' }}">
                @if(isset($data['icon']) && $data['icon'])
                    <i data-lucide="{{ $data['icon'] }}" class="w-4.5 h-4.5 {{ ((string)$currentStatus === (string)$value) ? 'text-emerald-400' : 'text-zinc-500' }}"></i>
                @endif
                {{ $data['label'] ?? $data }}
            </button>
        @endforeach
    </div>
    
    <div class="relative w-full flex {{ isset($compact) && $compact ? 'flex-col' : 'flex-col lg:flex-row' }} items-center gap-3 {{ isset($compact) && $compact ? '' : 'xl:w-auto' }}">
        @if(isset($sortOptions) && count($sortOptions) > 0)
        <div class="relative w-full {{ isset($compact) && $compact ? '' : 'lg:w-48 shrink-0' }}">
            <i data-lucide="arrow-up-down" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
            <select name="sort" onchange="this.form.submit()" class="bg-[#070707] border border-white/5 text-sm lg:text-base font-semibold text-white rounded-2xl py-3.5 pr-8 pl-10 outline-none transition-colors duration-200 w-full focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/15 appearance-none cursor-pointer">
                @foreach($sortOptions as $val => $label)
                    <option value="{{ $val }}" {{ ($currentSort ?? 'newest') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <i data-lucide="chevron-down" class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
        </div>
        @endif

        <div class="relative w-full flex {{ isset($compact) && $compact ? 'flex-col' : 'flex-col lg:flex-row' }} items-center gap-3">
            <div class="relative w-full {{ isset($compact) && $compact ? '' : 'lg:w-[320px]' }}">
                <i data-lucide="search" class="w-4.5 h-4.5 absolute left-5 top-1/2 -translate-y-1/2 text-slate-500"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ $searchPlaceholder ?? 'Cari...' }}" 
                    class="bg-[#070707] border border-white/5 text-sm lg:text-base font-semibold text-white rounded-2xl py-3.5 pr-5 pl-12 outline-none transition-colors duration-200 w-full focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/15">
            </div>
            <button type="submit" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white px-6 py-3.5 rounded-2xl text-xs lg:text-sm w-full {{ isset($compact) && $compact ? '' : 'lg:w-auto' }} font-extrabold transition-all duration-200 flex items-center justify-center gap-2 shrink-0">
                Cari
            </button>
        </div>
    </div>
</form>
