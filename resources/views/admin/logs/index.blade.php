@extends('layouts.admin')
@section('title', 'Log Aktivitas')
@section('page_title', 'Log Aktivitas')
@section('page_subtitle', 'Audit trail semua aktivitas admin dan pengguna penting')

@section('content')
@php
$logs = [
    ['action'=>'User Diblokir','actor'=>'Super Admin','target'=>'dimas@gmail.com','ip'=>'103.12.45.1','time'=>'5 menit lalu','type'=>'admin'],
    ['action'=>'KYC Diverifikasi','actor'=>'Super Admin','target'=>'Kevin Creator','ip'=>'103.12.45.1','time'=>'22 menit lalu','type'=>'admin'],
    ['action'=>'Login Berhasil','actor'=>'brand@cliphub.com','target'=>'–','ip'=>'180.252.3.77','time'=>'1 jam lalu','type'=>'auth'],
    ['action'=>'Campaign Dibuat','actor'=>'wardah@brand.com','target'=>'Summer Glow 2026','ip'=>'36.75.12.99','time'=>'2 jam lalu','type'=>'campaign'],
    ['action'=>'Konten UGC Ditolak','actor'=>'Super Admin','target'=>'Rafi Ananda – Video #003','ip'=>'103.12.45.1','time'=>'3 jam lalu','type'=>'admin'],
    ['action'=>'Penarikan Dicairkan','actor'=>'Super Admin','target'=>'Hana Creator – Rp 5.1 Jt','ip'=>'103.12.45.1','time'=>'5 jam lalu','type'=>'finance'],
    ['action'=>'Login Gagal (3x)','actor'=>'unknown@mail.com','target'=>'–','ip'=>'201.33.88.12','time'=>'8 jam lalu','type'=>'warning'],
    ['action'=>'Notifikasi Dikirim','actor'=>'Super Admin','target'=>'1,248 pengguna','ip'=>'103.12.45.1','time'=>'1 hari lalu','type'=>'admin'],
];
$typeClass = ['admin'=>'bg-brand/10 text-brand border-brand/20','auth'=>'bg-emerald-500/10 text-emerald-400 border-emerald-500/20','campaign'=>'bg-violet-500/10 text-violet-400 border-violet-500/20','finance'=>'bg-amber-500/10 text-amber-400 border-amber-500/20','warning'=>'bg-red-500/10 text-red-400 border-red-500/20'];
$typeIcon = ['admin'=>'shield','auth'=>'log-in','campaign'=>'megaphone','finance'=>'coins','warning'=>'alert-triangle'];
@endphp
<div class="space-y-4">
    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Audit Trail</h3>
            <div class="flex items-center gap-2">
                <select class="bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-1.5 text-xs text-slate-300 outline-none">
                    <option>Semua Tipe</option><option>Admin</option><option>Auth</option><option>Finance</option>
                </select>
            </div>
        </div>
        <div class="divide-y divide-neutral-800/40">
            @foreach($logs as $l)
            <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-white/[2%] transition-colors">
                <div class="w-8 h-8 rounded-xl bg-neutral-800 border border-neutral-700 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="{{ $typeIcon[$l['type']] }}" class="w-3.5 h-3.5 text-slate-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white">{{ $l['action'] }}</p>
                    <p class="text-xs text-slate-500 truncate">Oleh: {{ $l['actor'] }} · Target: {{ $l['target'] }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full border {{ $typeClass[$l['type']] }}">{{ ucfirst($l['type']) }}</span>
                    <p class="text-xs text-slate-600 mt-1">{{ $l['ip'] }} · {{ $l['time'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
