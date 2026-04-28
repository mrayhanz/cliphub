@extends('layouts.admin')

@section('title', 'Review Submission')
@section('page_title', 'Review Submission')
@section('page_subtitle', 'Validasi link konten, bukti analytics, views, dan estimasi reward kreator')

@section('content')
@php
$submissions = [
    ['kreator' => 'Rafi Ananda', 'campaign' => 'Summer Glow 2026', 'brand' => 'Wardah', 'platform' => 'TikTok', 'views' => '154.000', 'reward' => 'Rp 3.850.000', 'submitted' => '2 jam lalu', 'status' => 'Menunggu'],
    ['kreator' => 'Luna Aesthetic', 'campaign' => 'Summer Glow 2026', 'brand' => 'Wardah', 'platform' => 'Instagram', 'views' => '82.500', 'reward' => 'Rp 2.062.500', 'submitted' => '5 jam lalu', 'status' => 'Menunggu'],
    ['kreator' => 'Hana Creator', 'campaign' => 'Harbolnas 5.5', 'brand' => 'Tokopedia', 'platform' => 'TikTok', 'views' => '211.000', 'reward' => 'Rp 4.220.000', 'submitted' => '8 jam lalu', 'status' => 'Menunggu'],
    ['kreator' => 'Dimas Viral', 'campaign' => 'Pagi Bareng Kenangan', 'brand' => 'Kopi Kenangan', 'platform' => 'YouTube', 'views' => '68.000', 'reward' => 'Rp 1.224.000', 'submitted' => '1 hari lalu', 'status' => 'Disetujui'],
    ['kreator' => 'Rizky Clips', 'campaign' => 'Harbolnas 5.5', 'brand' => 'Tokopedia', 'platform' => 'TikTok', 'views' => '12.400', 'reward' => 'Rp 248.000', 'submitted' => '1 hari lalu', 'status' => 'Ditolak'],
    ['kreator' => 'Nadia UGC', 'campaign' => 'Double Day Deals', 'brand' => 'Shopee', 'platform' => 'Instagram', 'views' => '109.000', 'reward' => 'Rp 2.398.000', 'submitted' => '2 hari lalu', 'status' => 'Disetujui'],
];
@endphp

<div class="space-y-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Menunggu Review','val'=>'12','icon'=>'clock','color'=>'amber'],['label'=>'Disetujui','val'=>'284','icon'=>'check-circle','color'=>'emerald'],['label'=>'Ditolak','val'=>'31','icon'=>'x-circle','color'=>'red'],['label'=>'Total Submission','val'=>'327','icon'=>'file-check-2','color'=>'brand']] as $s)
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 items-center justify-center">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color']==='brand'?'brand':$s['color'].'-400' }}"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 p-5 border-b border-neutral-800/60">
            <div>
                <h3 class="text-sm font-semibold text-white">Antrean Validasi Submission</h3>
                <p class="text-xs text-slate-500 mt-1">Fokus ke bukti performa yang menentukan saldo kreator.</p>
            </div>
            <div class="flex gap-2 overflow-x-auto">
                @foreach(['Semua','Menunggu','Disetujui','Ditolak'] as $f)
                <button class="px-3 py-1.5 text-xs rounded-lg {{ $loop->index===1?'bg-brand/10 text-brand border border-brand/20':'text-slate-500 hover:text-white hover:bg-white/5' }} transition-colors">{{ $f }}</button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800/60">
                        @foreach(['Kreator','Campaign','Platform','Views','Estimasi Reward','Dikirim','Status','Aksi'] as $h)
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @foreach($submissions as $submission)
                    @php
                    $statusClass = match($submission['status']){
                        'Menunggu' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                        'Disetujui' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                        default => 'bg-red-500/10 text-red-400 border-red-500/20'
                    };
                    @endphp
                    <tr class="hover:bg-white/[2%] transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-brand/10 border border-brand/20 flex items-center justify-center text-xs font-black text-brand">
                                    {{ strtoupper(substr($submission['kreator'], 0, 1)) }}
                                </div>
                                <p class="text-sm font-medium text-white">{{ $submission['kreator'] }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-sm font-medium text-white">{{ $submission['campaign'] }}</p>
                            <p class="text-xs text-slate-500">{{ $submission['brand'] }}</p>
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-400">{{ $submission['platform'] }}</td>
                        <td class="px-5 py-4 text-xs font-semibold text-slate-300">{{ $submission['views'] }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-white">{{ $submission['reward'] }}</td>
                        <td class="px-5 py-4 text-xs text-slate-500">{{ $submission['submitted'] }}</td>
                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusClass }}">{{ $submission['status'] }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($submission['status']==='Menunggu')
                            <div class="flex items-center gap-1.5">
                                <button class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-colors">
                                    <i data-lucide="check" class="w-3 h-3"></i> Approve
                                </button>
                                <button class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl hover:bg-red-500/20 transition-colors">
                                    <i data-lucide="x" class="w-3 h-3"></i> Reject
                                </button>
                            </div>
                            @else
                            <button class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
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
