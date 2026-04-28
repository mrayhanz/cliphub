@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Admin')
@section('page_subtitle', 'Fokus kerja harian: submission, withdrawal, campaign, dan pengguna')

@section('content')
@php
$stats = [
    ['label' => 'Submission Menunggu', 'val' => '12', 'icon' => 'file-check-2', 'color' => 'amber', 'href' => route('admin.submissions')],
    ['label' => 'Withdrawal Menunggu', 'val' => '3', 'icon' => 'banknote', 'color' => 'emerald', 'href' => route('admin.withdrawals')],
    ['label' => 'Campaign Aktif', 'val' => '71', 'icon' => 'megaphone', 'color' => 'brand', 'href' => route('admin.campaigns')],
    ['label' => 'Pengguna Aktif', 'val' => '1,224', 'icon' => 'users', 'color' => 'violet', 'href' => route('admin.users')],
];

$tasks = [
    [
        'title' => 'Validasi submission kreator',
        'desc' => 'Cek link video, bukti analytics, views yang diklaim, dan estimasi reward.',
        'count' => '12',
        'icon' => 'file-check-2',
        'color' => 'amber',
        'href' => route('admin.submissions'),
        'action' => 'Review Submission',
    ],
    [
        'title' => 'Proses withdrawal kreator',
        'desc' => 'Pastikan rekening/e-wallet benar sebelum status pencairan diperbarui.',
        'count' => '3',
        'icon' => 'banknote',
        'color' => 'emerald',
        'href' => route('admin.withdrawals'),
        'action' => 'Proses Withdrawal',
    ],
    [
        'title' => 'Pantau campaign aktif',
        'desc' => 'Lihat campaign yang berjalan, budget, deadline, dan progres submission.',
        'count' => '71',
        'icon' => 'megaphone',
        'color' => 'brand',
        'href' => route('admin.campaigns'),
        'action' => 'Kelola Campaign',
    ],
];

$recentSubmissions = [
    ['creator' => 'Rafi Ananda', 'campaign' => 'Summer Glow 2026', 'views' => '154.000', 'reward' => 'Rp 3.850.000', 'status' => 'Menunggu'],
    ['creator' => 'Luna Aesthetic', 'campaign' => 'Summer Glow 2026', 'views' => '82.500', 'reward' => 'Rp 2.062.500', 'status' => 'Menunggu'],
    ['creator' => 'Hana Creator', 'campaign' => 'Harbolnas 5.5', 'views' => '211.000', 'reward' => 'Rp 4.220.000', 'status' => 'Menunggu'],
];
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach($stats as $s)
        <a href="{{ $s['href'] }}" class="stat-card group block hover:border-white/15 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 flex items-center justify-center">
                    <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color'] === 'brand' ? 'brand' : $s['color'].'-400' }}"></i>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-700 group-hover:text-white transition-colors"></i>
            </div>
            <p class="text-2xl font-black text-white tracking-tight">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500 mt-1">{{ $s['label'] }}</p>
        </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
        <div class="xl:col-span-1 glass-card p-5">
            <div class="mb-5">
                <h3 class="text-sm font-bold text-white">Antrean Kerja Admin</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Urutan kerja yang paling berdampak ke operasional</p>
            </div>

            <div class="space-y-3">
                @foreach($tasks as $task)
                <a href="{{ $task['href'] }}" class="block rounded-2xl p-4 border border-white/[0.06] bg-white/[0.02] hover:bg-white/[0.04] transition-colors group">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-{{ $task['color'] }}/10 border border-{{ $task['color'] }}/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="{{ $task['icon'] }}" class="w-4 h-4 text-{{ $task['color'] === 'brand' ? 'brand' : $task['color'].'-400' }}"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-bold text-white">{{ $task['title'] }}</p>
                                <span class="text-xs font-black text-white">{{ $task['count'] }}</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed mt-1.5">{{ $task['desc'] }}</p>
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-brand mt-3 group-hover:text-brand-light">
                                {{ $task['action'] }} <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <div class="xl:col-span-2 glass-card p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-white">Submission Terbaru</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Data ini menggantikan dashboard analytics placeholder</p>
                </div>
                <a href="{{ route('admin.submissions') }}" class="text-[11px] text-brand hover:text-brand-light font-semibold flex items-center gap-1">
                    Lihat Semua <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-white/[0.06]">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/[0.06] bg-white/[0.02]">
                            <th class="text-left px-4 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kreator</th>
                            <th class="text-left px-4 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Campaign</th>
                            <th class="text-left px-4 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Views</th>
                            <th class="text-left px-4 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Reward</th>
                            <th class="text-left px-4 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.05]">
                        @foreach($recentSubmissions as $submission)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-4 py-3 text-sm font-semibold text-white">{{ $submission['creator'] }}</td>
                            <td class="px-4 py-3 text-xs text-slate-400">{{ $submission['campaign'] }}</td>
                            <td class="px-4 py-3 text-xs text-slate-300">{{ $submission['views'] }}</td>
                            <td class="px-4 py-3 text-xs font-bold text-white">{{ $submission['reward'] }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border bg-amber-500/10 text-amber-400 border-amber-500/20">{{ $submission['status'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
