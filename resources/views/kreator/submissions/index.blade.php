@extends('layouts.kreator')

@section('title', 'Riwayat Submission')

@section('content')
@php
$history = [
    ['campaign' => 'Tokopedia 12.12 Mega Sale', 'platform' => 'TikTok', 'status' => 'Pending Review', 'color' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'views' => '120.000', 'reward' => 'Rp 2.400.000', 'date' => 'Hari ini, 14:30'],
    ['campaign' => 'Skincare Routine Challenge - Wardah', 'platform' => 'Instagram Reels', 'status' => 'Pending Review', 'color' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'views' => '45.000', 'reward' => 'Rp 1.125.000', 'date' => 'Hari ini, 09:15'],
    ['campaign' => 'Review Kopi Kenangan Edisi Gold', 'platform' => 'TikTok', 'status' => 'Disetujui', 'color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'views' => '210.000', 'reward' => 'Rp 4.200.000', 'date' => 'Kemarin, 11:20'],
    ['campaign' => 'Unboxing Raket Badminton Pro', 'platform' => 'YouTube Shorts', 'status' => 'Ditolak', 'color' => 'bg-red-500/10 text-red-300 border-red-500/20', 'views' => '8.000', 'reward' => 'Rp 160.000', 'date' => '24 Mar 2026', 'reason' => 'Screenshot terlalu buram. Upload ulang bukti analytics asli dari platform.'],
];
@endphp

<div class="max-w-6xl mx-auto pb-12 space-y-7 pt-2">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5 mb-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-bold mb-3" style="box-shadow: inset 0 0 0 1px rgba(16,185,129,0.2)">
                <i data-lucide="history" class="w-3 h-3"></i> Klaim Views
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-white leading-tight mb-2">Riwayat Submission</h1>
            <p class="text-xs text-slate-500 max-w-sm">Pantau status klaim views dan estimasi reward dari campaign yang kamu kerjakan.</p>
        </div>

        <a href="{{ route('kreator.submissions.create') }}" class="self-start sm:self-auto btn-primary px-6">
            <i data-lucide="plus" class="w-4 h-4"></i> Klaim Views Baru
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Pending Review','val'=>'2','icon'=>'clock','color'=>'amber'],['label'=>'Disetujui','val'=>'1','icon'=>'check-circle','color'=>'emerald'],['label'=>'Ditolak','val'=>'1','icon'=>'x-circle','color'=>'red'],['label'=>'Estimasi Reward','val'=>'Rp 7.9Jt','icon'=>'wallet','color'=>'brand']] as $s)
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 items-center justify-center mb-3">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color']==='brand'?'brand':$s['color'].'-400' }}"></i>
            </div>
            <p class="text-xl font-black text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-[#0a0a0a] rounded-3xl overflow-hidden shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)]">
        <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" style="box-shadow: 0 1px 0 rgba(255,255,255,0.04); background: rgba(255,255,255,0.01)">
            <div class="col-span-5">Campaign & Platform</div>
            <div class="col-span-2 text-right">Views</div>
            <div class="col-span-2 text-right">Reward</div>
            <div class="col-span-2 text-center">Status</div>
            <div class="col-span-1 text-right">Detail</div>
        </div>

        <div class="flex flex-col">
            @foreach($history as $h)
            <div class="p-5 transition-colors duration-200 flex flex-col md:grid md:grid-cols-12 md:gap-4 md:items-center border-b border-white/5 last:border-0 hover:bg-white/[0.02] relative group">
                <div class="md:col-span-5 flex items-center gap-4 min-w-0">
                    <div class="w-11 h-11 rounded-2xl shrink-0 flex items-center justify-center bg-white/[0.04] border border-white/[0.06]">
                        <i data-lucide="video" class="w-5 h-5 text-brand"></i>
                    </div>
                    <div class="min-w-0 pr-2">
                        <h3 class="text-[13px] font-bold text-white mb-0.5 truncate group-hover:text-emerald-300 transition-colors">{{ $h['campaign'] }}</h3>
                        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest">{{ $h['platform'] }} <span class="mx-1">-</span> {{ $h['date'] }}</p>
                    </div>
                </div>

                <div class="md:hidden w-full h-px bg-white/5 my-3"></div>

                <div class="md:col-span-2 md:text-right">
                    <p class="text-sm font-black text-white">{{ $h['views'] }}</p>
                    <p class="text-[10px] font-semibold text-slate-500 mt-0.5 uppercase tracking-widest">Views</p>
                </div>

                <div class="mt-3 md:mt-0 md:col-span-2 md:text-right">
                    <p class="text-[13px] font-black {{ $h['status'] === 'Ditolak' ? 'text-slate-500 line-through opacity-50' : ($h['status'] === 'Disetujui' ? 'text-emerald-400' : 'text-amber-400') }}">{{ $h['reward'] }}</p>
                    <p class="text-[10px] font-semibold text-slate-500 mt-0.5 uppercase tracking-widest">Reward</p>
                </div>

                <div class="mt-3 md:mt-0 md:col-span-2 md:flex md:justify-center">
                    <span class="px-3 py-1.5 rounded-full text-[0.65rem] font-black tracking-widest uppercase inline-flex items-center justify-center whitespace-nowrap border {{ $h['color'] }}">{{ $h['status'] }}</span>
                </div>

                <div class="hidden md:flex md:col-span-1 justify-end">
                    <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="Lihat Detail">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>

                @if(isset($h['reason']))
                    <div class="md:col-span-12 mt-4 w-full">
                        <div class="p-3 rounded-xl bg-red-500/10 border border-red-500/20 flex gap-3 items-start">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-0.5">Alasan Penolakan</p>
                                <p class="text-xs text-red-200/80 leading-relaxed">{{ $h['reason'] }}</p>
                                <a href="{{ route('kreator.submissions.create') }}" class="mt-2 inline-flex text-[10px] font-bold text-white bg-red-500 hover:bg-red-400 px-3 py-1.5 rounded-lg transition-colors">Klaim Ulang</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
