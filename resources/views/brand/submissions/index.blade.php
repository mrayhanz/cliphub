@extends('layouts.brand')

@section('title', 'Review Submission')

@section('content')
@php
$submissions = [
    ['creator' => '@sarah.tiktok', 'platform' => 'TikTok', 'status' => 'pending', 'campaign' => 'Review Serum Skincare X', 'views' => 45000, 'cost' => 900000, 'time' => '10 menit'],
    ['creator' => '@budi_gaming', 'platform' => 'Instagram', 'status' => 'pending', 'campaign' => 'Tokopedia 12.12 Mega Sale', 'views' => 120500, 'cost' => 2410000, 'time' => '1 jam'],
    ['creator' => '@indomie_lover', 'platform' => 'YouTube', 'status' => 'pending', 'campaign' => 'Makan Indomie Hack', 'views' => 210000, 'cost' => 4200000, 'time' => '3 jam'],
    ['creator' => '@dina_style', 'platform' => 'TikTok', 'status' => 'approved', 'campaign' => 'OOTD Keren 2025', 'views' => 5000, 'cost' => 100000, 'time' => '1 hari'],
    ['creator' => '@ryan.review', 'platform' => 'Instagram', 'status' => 'rejected', 'campaign' => 'Spill Gadget 2025', 'views' => 3200, 'cost' => 64000, 'time' => '2 hari'],
];
@endphp

<div class="w-full pb-12 pt-2 space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight mb-2">Review Submission</h1>
            <p class="text-xs text-slate-400 max-w-xl leading-relaxed">
                Validasi link konten, bukti analytics, dan views yang diklaim sebelum reward kreator disetujui.
            </p>
        </div>
        <a href="{{ route('brand.campaigns') }}" class="btn-ghost w-fit px-4">
            <i data-lucide="megaphone" class="w-4 h-4"></i> Lihat Campaign
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Menunggu Review','val'=>'3','icon'=>'clock','color'=>'amber'],['label'=>'Disetujui','val'=>'1','icon'=>'check-circle','color'=>'emerald'],['label'=>'Ditolak','val'=>'1','icon'=>'x-circle','color'=>'red'],['label'=>'Estimasi Reward','val'=>'Rp 7.6Jt','icon'=>'wallet','color'=>'brand']] as $s)
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 items-center justify-center mb-3">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color']==='brand'?'brand':$s['color'].'-400' }}"></i>
            </div>
            <p class="text-xl font-black text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.5rem] overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-5 border-b border-white/5">
            <div>
                <h3 class="text-sm lg:text-base font-black text-white">Daftar Submission</h3>
                <p class="text-xs text-slate-500 mt-1">Approve untuk meneruskan proses reward, reject dengan alasan jika bukti tidak valid.</p>
            </div>

            <div class="flex gap-1.5 p-1 bg-white/[0.02] border border-white/5 rounded-xl overflow-x-auto">
                <button class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold bg-brand/10 text-brand border border-brand/20 whitespace-nowrap">Semua</button>
                <button class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all whitespace-nowrap border border-transparent">Menunggu</button>
                <button class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all whitespace-nowrap border border-transparent">Selesai</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/5">
                        @foreach(['Kreator','Campaign','Platform','Views','Reward','Dikirim','Status','Aksi'] as $h)
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.05]">
                    @foreach($submissions as $s)
                    @php
                    $statusClass = match($s['status']){
                        'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                        'approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                        default => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                    };
                    $statusLabel = match($s['status']){
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        default => 'Ditolak',
                    };
                    @endphp
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-5 py-4">
                            <p class="text-sm font-black text-white">{{ $s['creator'] }}</p>
                        </td>
                        <td class="px-5 py-4 text-xs font-semibold text-slate-300">{{ $s['campaign'] }}</td>
                        <td class="px-5 py-4 text-xs text-slate-400">{{ $s['platform'] }}</td>
                        <td class="px-5 py-4 text-xs font-bold text-white">{{ number_format($s['views'], 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-xs font-black text-emerald-400">Rp {{ number_format($s['cost'], 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-xs text-slate-500">{{ $s['time'] }}</td>
                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($s['status'] === 'pending')
                            <div class="flex items-center gap-1.5">
                                <button class="h-8 px-3 flex items-center justify-center gap-1.5 rounded-xl bg-white/[0.04] border border-white/[0.08] hover:bg-white/[0.08] text-slate-300 transition-colors" title="Lihat bukti analytics">
                                    <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold">Bukti</span>
                                </button>
                                <button class="h-8 px-3 flex items-center justify-center gap-1.5 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-colors">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold">Reject</span>
                                </button>
                                <button class="h-8 px-3 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white transition-colors flex items-center justify-center gap-1.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold">Approve</span>
                                </button>
                            </div>
                            @else
                            <button class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
