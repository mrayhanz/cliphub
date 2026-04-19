@extends('layouts.admin')
@section('title', 'Anti-Fraud Monitor')
@section('page_title', 'Anti-Fraud Monitor')
@section('page_subtitle', 'Deteksi aktivitas mencurigakan dan akun bot di platform')

@section('content')
@php
$alerts = [
    ['account'=>'bot_views99','type'=>'Bot Activity','detail'=>'Views naik 50K dalam 2 menit secara anomali','risk'=>'Kritis','time'=>'5 menit lalu','status'=>'Aktif'],
    ['account'=>'spam_creator1','type'=>'Spam Submission','detail'=>'Mengirim 40+ konten identik dalam 1 jam','risk'=>'Tinggi','time'=>'30 menit lalu','status'=>'Aktif'],
    ['account'=>'fake_account33','type'=>'Fake Account','detail'=>'Profil dibuat dengan foto AI, no konten asli','risk'=>'Sedang','time'=>'2 jam lalu','status'=>'Diperiksa'],
    ['account'=>'brandspoof_id','type'=>'Brand Spoofing','detail'=>'Menggunakan logo brand tanpa izin di konten','risk'=>'Tinggi','time'=>'5 jam lalu','status'=>'Diblokir'],
    ['account'=>'view_buyer22','type'=>'View Manipulation','detail'=>'Mencurigai pembelian views dari luar platform','risk'=>'Sedang','time'=>'1 hari lalu','status'=>'Diperiksa'],
];
$riskClass=['Kritis'=>'bg-red-500/15 text-red-400 border-red-500/30','Tinggi'=>'bg-orange-500/15 text-orange-400 border-orange-500/30','Sedang'=>'bg-amber-500/15 text-amber-400 border-amber-500/30'];
$statusClass=['Aktif'=>'bg-red-500/10 text-red-400 border-red-500/20','Diperiksa'=>'bg-amber-500/10 text-amber-400 border-amber-500/20','Diblokir'=>'bg-neutral-700 text-slate-500 border-neutral-600'];
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Alert Aktif','val'=>'7','icon'=>'shield-alert','color'=>'red'],['label'=>'Akun Diblokir','val'=>'34','icon'=>'user-x','color'=>'amber'],['label'=>'Terdeteksi Bot','val'=>'12','icon'=>'bot','color'=>'orange'],['label'=>'Fraud Rate','val'=>'0.3%','icon'=>'percent','color'=>'emerald']] as $s)
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 items-center justify-center mb-3">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color'] }}-400"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse inline-block"></span>
                Alert Fraud Real-Time
            </h3>
        </div>
        <div class="divide-y divide-neutral-800/40">
            @foreach($alerts as $a)
            <div class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="shield-alert" class="w-4 h-4 text-red-400"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-0.5">
                        <p class="text-sm font-medium text-white">{{ $a['account'] }}</p>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full border {{ $riskClass[$a['risk']] }}">{{ $a['risk'] }}</span>
                    </div>
                    <p class="text-xs text-slate-500">{{ $a['type'] }} · {{ $a['detail'] }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusClass[$a['status']] }}">{{ $a['status'] }}</span>
                    <p class="text-xs text-slate-600 mt-1">{{ $a['time'] }}</p>
                </div>
                @if($a['status']==='Aktif')
                <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl hover:bg-red-500/20 transition-colors flex-shrink-0"><i data-lucide="ban" class="w-3 h-3"></i> Blokir</button>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
