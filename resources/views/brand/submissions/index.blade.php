@extends('layouts.brand')

@section('title', 'Review Submission')

@section('content')
<div class="w-full pb-12 pt-2 space-y-6" x-data="{ 
    showRejectModal: false, 
    showProofModal: false,
    proofImageUrl: '',
    rejectionActionUrl: '',
    rejectionCreatorName: '',
    rejectionCampaignTitle: ''
}">
    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 animate-fade-in-up">
        <div>
            <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight mb-2">Review Submission</h1>
            <p class="text-xs text-slate-400 max-w-xl leading-relaxed">
                Validasi link konten, bukti analytics, dan views yang diklaim sebelum reward kreator disetujui.
            </p>
        </div>
        <a href="{{ route('brand.campaigns') }}" class="btn-ghost w-fit px-5 py-3 text-xs lg:text-sm">
            <i data-lucide="megaphone" class="w-4 h-4 text-emerald-400"></i> Lihat Campaign
        </a>
    </div>

    {{-- Session Notifications --}}
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs lg:text-sm font-bold flex items-center gap-2 animate-fade-in-up">
        <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('warning'))
    <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs lg:text-sm font-bold flex items-center gap-2 animate-fade-in-up">
        <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0"></i>
        <span>{{ session('warning') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs lg:text-sm font-bold flex items-center gap-2 animate-fade-in-up">
        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 animate-fade-in-up">
        {{-- Stat: Menunggu Review --}}
        <div class="stat-card">
            <div class="flex w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 items-center justify-center mb-3">
                <i data-lucide="clock" class="w-5 h-5 text-amber-400"></i>
            </div>
            <p class="text-xl lg:text-2xl font-black text-white leading-none mb-1">{{ $pendingCount }}</p>
            <p class="text-[10px] lg:text-xs text-slate-500 font-bold uppercase tracking-wider">Menunggu Review</p>
        </div>

        {{-- Stat: Disetujui --}}
        <div class="stat-card">
            <div class="flex w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 items-center justify-center mb-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400"></i>
            </div>
            <p class="text-xl lg:text-2xl font-black text-white leading-none mb-1">{{ $approvedCount }}</p>
            <p class="text-[10px] lg:text-xs text-slate-500 font-bold uppercase tracking-wider">Disetujui</p>
        </div>

        {{-- Stat: Ditolak --}}
        <div class="stat-card">
            <div class="flex w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 items-center justify-center mb-3">
                <i data-lucide="x-circle" class="w-5 h-5 text-rose-400"></i>
            </div>
            <p class="text-xl lg:text-2xl font-black text-white leading-none mb-1">{{ $rejectedCount }}</p>
            <p class="text-[10px] lg:text-xs text-slate-500 font-bold uppercase tracking-wider">Ditolak</p>
        </div>

        {{-- Stat: Estimasi Reward Terbayar --}}
        <div class="stat-card">
            <div class="flex w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 items-center justify-center mb-3">
                <i data-lucide="wallet" class="w-5 h-5 text-blue-400"></i>
            </div>
            <p class="text-xl lg:text-2xl font-black text-white leading-none mb-1">
                <span class="text-xs lg:text-sm text-slate-500 font-bold mr-0.5">Rp</span>{{ number_format($totalEstimatedReward, 0, ',', '.') }}
            </p>
            <p class="text-[10px] lg:text-xs text-slate-500 font-bold uppercase tracking-wider">Total Reward Terbayar</p>
        </div>
    </div>

    @include('brand.partials.filter-search', [
        'action' => route('brand.submissions'),
        'filters' => $filters,
        'currentStatus' => $statusFilter ?? '',
        'search' => $search ?? '',
        'searchPlaceholder' => 'Cari kreator atau judul campaign...',
        'sortOptions' => $sortOptions ?? [],
        'currentSort' => $currentSort ?? 'newest'
    ])

    {{-- Main Submissions Container --}}
    <div class="bg-[#111111] border border-white/5 rounded-[1.5rem] overflow-hidden animate-fade-in-up">
        {{-- Header Controls --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-5 border-b border-white/5">
            <div>
                <h3 class="text-sm lg:text-base font-black text-white">Daftar Submission</h3>
                <p class="text-[10px] lg:text-xs text-slate-500 mt-1">Approve untuk memproses pembayaran reward, reject jika konten tidak valid.</p>
            </div>
        </div>

        {{-- Table Element --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/5">
                        @foreach(['Kreator','Campaign','Platform','Views','Reward','Dikirim','Status','Aksi'] as $h)
                        <th class="text-left px-5 py-4 text-[10px] lg:text-xs font-black text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.05]">
                    @forelse($submissions as $s)
                    @php
                    $statusClass = match($s->status){
                        'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                        'approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                        default => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                    };
                    $statusLabel = match($s->status){
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        default => 'Ditolak',
                    };
                    $estimatedReward = ($s->views_claimed * $s->campaign->price_per_1k) / 1000;
                    @endphp
                    <tr class="hover:bg-white/[0.01] transition-colors">
                        <td class="px-5 py-4">
                            <p class="text-xs lg:text-sm font-black text-white">{{ $s->user->name }}</p>
                        </td>
                        <td class="px-5 py-4 text-xs font-semibold text-slate-300">{{ $s->campaign->title }}</td>
                        <td class="px-5 py-4 text-xs text-slate-400 font-bold">{{ $s->platform }}</td>
                        <td class="px-5 py-4 text-xs font-bold text-white">{{ number_format($s->views_claimed, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-xs font-black text-emerald-400">
                            Rp {{ number_format($s->estimated_reward ?: $estimatedReward, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-500 font-medium">{{ $s->created_at->diffForHumans() }}</td>
                        <td class="px-5 py-4">
                            <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg border {{ $statusClass }} uppercase tracking-wider">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($s->status === 'pending')
                            <div class="flex items-center gap-1.5">
                                {{-- View Details Trigger Button for pending --}}
                                <a href="{{ route('brand.submissions.show', $s->id) }}" class="h-8 px-3 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 transition-colors flex items-center justify-center gap-1.5" title="Detail Workspace Submisi">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold">Detail</span>
                                </a>
                                
                                {{-- Reject Action Button --}}
                                <button class="h-8 px-3 flex items-center justify-center gap-1.5 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-colors"
                                        @click="rejectionActionUrl = '{{ route('brand.submissions.reject', $s->id) }}'; rejectionCreatorName = '{{ addslashes($s->user->name) }}'; rejectionCampaignTitle = '{{ addslashes($s->campaign->title) }}'; showRejectModal = true">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold">Reject</span>
                                </button>
                                
                                {{-- Approve Action Button --}}
                                 <button class="h-8 px-3 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white transition-colors flex items-center justify-center gap-1.5"
                                         @click.prevent="$dispatch('open-confirm-modal', { 
                                             title: 'Setujui Submission', 
                                             message: 'Apakah Anda yakin ingin menyetujui submission dari &lt;strong class=&quot;text-white&quot;&gt;{{ addslashes($s->user->name) }}&lt;/strong&gt;?&lt;br&gt;&lt;br&gt;Dana escrow sebesar &lt;span class=&quot;text-emerald-400 font-extrabold&quot;&gt;Rp {{ number_format($estimatedReward, 0, ',', '.') }}&lt;/span&gt; akan langsung ditransfer ke dompet kreator.&lt;br&gt;&lt;br&gt;&lt;div class=&quot;p-3.5 rounded-xl bg-white/[0.02] border border-white/5 space-y-1.5 mt-2&quot;&gt;&lt;div class=&quot;text-[9px] font-black text-emerald-400 uppercase tracking-widest&quot;&gt;Kalkulasi Payout&lt;/div&gt;&lt;div class=&quot;flex justify-between&quot;&gt;&lt;span class=&quot;text-slate-400&quot;&gt;Formula:&lt;/span&gt;&lt;span class=&quot;text-white font-semibold&quot;&gt;(Views/1K) &times; Rate/1K&lt;/span&gt;&lt;/div&gt;&lt;div class=&quot;flex justify-between&quot;&gt;&lt;span class=&quot;text-slate-400&quot;&gt;Rincian:&lt;/span&gt;&lt;span class=&quot;text-slate-300 font-bold&quot;&gt;({{ number_format($s->views_claimed, 0, ',', '.') }} / 1.000) &times; Rp {{ number_format($s->campaign->price_per_1k, 0, ',', '.') }}&lt;/span&gt;&lt;/div&gt;&lt;/div&gt;', 
                                             action: '{{ route('brand.submissions.approve', $s->id) }}', 
                                             method: 'POST', 
                                             buttonText: 'Ya, Setujui', 
                                             buttonClass: 'bg-emerald-500 hover:bg-emerald-400 text-black' 
                                         })">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold">Approve</span>
                                </button>
                            </div>
                            @else
                            {{-- View Details Trigger Button for processed items --}}
                            <a href="{{ route('brand.submissions.show', $s->id) }}" class="h-8 px-3 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 transition-colors flex items-center justify-center gap-1.5 inline-flex" title="Detail Workspace Submisi">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span class="text-[10px] font-bold">Detail</span>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                                <i data-lucide="inbox" class="w-5 h-5 text-slate-600"></i>
                            </div>
                            <p class="text-xs text-slate-600 font-medium">Tidak ada submission yang ditemukan untuk filter ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($submissions->hasPages())
            <div class="p-5 border-t border-white/5 bg-black/10">
                {{ $submissions->appends(request()->query())->links('brand.partials.pagination') }}
            </div>
        @endif
    </div>

    {{-- MODAL: PROOF PREVIEW (Alpine inline modal) --}}
    <template x-teleport="body">
        <div class="fixed inset-0 z-[9999] flex items-center justify-center px-4 overflow-hidden" 
             x-show="showProofModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="showProofModal = false"></div>
            <div class="relative w-full max-w-2xl bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl p-4 animate-fade-in-up">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-white/5">
                    <h3 class="text-sm lg:text-base font-black text-white">Bukti Analytics Konten</h3>
                    <button class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors" @click="showProofModal = false">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="flex justify-center max-h-[70vh] overflow-y-auto">
                    <img :src="proofImageUrl" class="rounded-xl w-full object-contain" alt="Bukti Analytics">
                </div>
            </div>
        </div>
    </template>

    {{-- MODAL: REJECT SUBMISSION --}}
    <template x-teleport="body">
        <div class="fixed inset-0 z-[9999] flex items-center justify-center px-4 overflow-hidden" 
             x-show="showRejectModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="showRejectModal = false"></div>
            <div class="relative w-full max-w-lg bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl p-6 animate-fade-in-up">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-white/5">
                    <h3 class="text-sm lg:text-base font-black text-white">Tolak Submission</h3>
                    <button class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors" @click="showRejectModal = false">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="mb-4 space-y-1">
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Kreator</p>
                    <p class="text-sm font-black text-white" x-text="rejectionCreatorName"></p>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-2">Campaign</p>
                    <p class="text-xs font-semibold text-slate-300" x-text="rejectionCampaignTitle"></p>
                </div>

                <form :action="rejectionActionUrl" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest block">Alasan Penolakan</label>
                        <textarea name="rejection_reason" required placeholder="Berikan alasan yang jelas mengapa submission ini ditolak (misalnya: tidak sesuai brief, video terpotong, dll)." 
                                  class="w-full h-32 rounded-xl bg-white/5 border border-white/10 p-4 text-sm text-white focus:outline-none focus:border-rose-500 transition-colors"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" class="px-5 py-3 rounded-xl text-xs lg:text-sm font-bold text-slate-400 hover:text-white transition-colors" @click="showRejectModal = false">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-3 rounded-xl bg-rose-500 hover:bg-rose-400 text-white text-xs lg:text-sm font-black transition-all">
                            Tolak Konten
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>


</div>
@endsection
