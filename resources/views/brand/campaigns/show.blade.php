@extends('layouts.brand')

@section('title', 'Detail Campaign')

@section('content')
<div class="max-w-7xl mx-auto pb-12 pt-2">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('brand.campaigns') }}" class="btn-secondary px-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Campaign Header -->
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl overflow-hidden">
                <div class="relative w-full aspect-[21/9] bg-neutral-900">
                    <img src="{{ $campaign->thumbnail_url }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#111111] via-[#111111]/40 to-transparent"></div>
                </div>
                
                <div class="p-6">
                    <h1 class="text-2xl font-black text-white mb-2">{{ $campaign->title }}</h1>
                    <p class="text-sm text-slate-400">{{ $campaign->desc }}</p>
                </div>
            </div>

            <!-- Campaign Details -->
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Detail Campaign</h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Platform</label>
                            <p class="text-sm font-semibold text-white mt-1">{{ ucfirst($campaign->platform) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Slots</label>
                            <p class="text-sm font-semibold text-white mt-1">{{ $campaign->slots }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Deadline</label>
                            <p class="text-sm font-semibold text-white mt-1">{{ $campaign->deadline ? \Carbon\Carbon::parse($campaign->deadline)->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Video Length</label>
                            <p class="text-sm font-semibold text-white mt-1">{{ $campaign->video_length ?? '-' }}</p>
                        </div>
                    </div>

                    @if($campaign->full_brief)
                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Full Brief</label>
                        <div class="mt-2 p-4 bg-neutral-900 rounded-xl text-sm text-slate-300">
                            {!! nl2br(e($campaign->full_brief)) !!}
                        </div>
                    </div>
                    @endif

                    @if($campaign->donts)
                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Don'ts</label>
                        <div class="mt-2 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-sm text-slate-300">
                            {!! nl2br(e($campaign->donts)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white mb-4">Status</h3>
                
                @php
                $statusConfig = match(strtolower($campaign->status)){
                    'active' => ['label' => 'Active', 'color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'],
                    'completed' => ['label' => 'Completed', 'color' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'],
                    'draft' => ['label' => 'Draft', 'color' => 'bg-amber-500/10 text-amber-400 border-amber-500/20'],
                    default => ['label' => 'Unknown', 'color' => 'bg-slate-500/10 text-slate-400 border-slate-500/20']
                };
                @endphp

                <div class="flex items-center justify-center">
                    <span class="text-sm font-semibold px-4 py-2 rounded-xl border {{ $statusConfig['color'] }}">
                        {{ $statusConfig['label'] }}
                    </span>
                </div>
            </div>

            <!-- Budget Info -->
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white mb-4">Budget</h3>
                
                @php
                $budgetSpent = $campaign->budget_spent ?? 0;
                $remainingBudget = $campaign->budget - $budgetSpent;
                $progressPercent = $campaign->budget > 0 ? ($budgetSpent / $campaign->budget) * 100 : 0;
                $isLowBudget = $remainingBudget < ($campaign->budget * 0.2); // Less than 20%
                $isOverBudget = $budgetSpent > $campaign->budget;
                @endphp

                @if($isOverBudget)
                <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-xl">
                    <div class="flex items-start gap-2">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-red-400 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-red-400">Budget Terlampaui!</p>
                            <p class="text-xs text-slate-400 mt-1">Campaign telah melebihi budget yang dialokasikan.</p>
                        </div>
                    </div>
                </div>
                @elseif($isLowBudget && $remainingBudget > 0)
                <div class="mb-4 p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                    <div class="flex items-start gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-amber-400 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-amber-400">Budget Hampir Habis!</p>
                            <p class="text-xs text-slate-400 mt-1">Pertimbangkan untuk menambah budget campaign.</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Budget</label>
                        <p class="text-xl font-black text-white mt-1">Rp {{ number_format($campaign->budget, 0, ',', '.') }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Budget Terpakai</label>
                        <p class="text-xl font-black {{ $isOverBudget ? 'text-red-400' : 'text-emerald-400' }} mt-1">
                            Rp {{ number_format($budgetSpent, 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Sisa Budget</label>
                        <p class="text-xl font-black {{ $remainingBudget < 0 ? 'text-red-400' : ($isLowBudget ? 'text-amber-400' : 'text-emerald-400') }} mt-1">
                            Rp {{ number_format($remainingBudget, 0, ',', '.') }}
                        </p>
                        @if($remainingBudget < 0)
                        <p class="text-xs text-red-400 mt-1">⚠️ Budget defisit</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Price per 1K Views</label>
                        <p class="text-sm font-semibold text-white mt-1">Rp {{ number_format($campaign->price_per_1k, 0, ',', '.') }}</p>
                    </div>

                    <div class="pt-2">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs text-slate-500">Progress</span>
                            <span class="text-xs font-bold {{ $isOverBudget ? 'text-red-400' : 'text-emerald-400' }}">
                                {{ number_format($progressPercent, 1) }}%
                            </span>
                        </div>
                        <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full {{ $isOverBudget ? 'bg-gradient-to-r from-red-600 to-red-500' : 'bg-gradient-to-r from-emerald-600 to-green-600' }} rounded-full transition-all duration-500" 
                                 style="width: {{ min($progressPercent, 100) }}%;"></div>
                        </div>
                        @if($isOverBudget)
                        <p class="text-xs text-red-400 mt-2 text-center">Over budget: {{ number_format($progressPercent - 100, 1) }}%</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white mb-4">Actions</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('brand.submissions.index') }}" class="w-full btn-primary">
                        <i data-lucide="file-check-2" class="w-4 h-4"></i>
                        Review Submissions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
