@extends('layouts.admin')
@section('title', 'Penarikan Dana')
@section('page_title', 'Penarikan Dana')
@section('page_subtitle', 'Approve atau tolak permintaan penarikan saldo kreator')

@section('content')
@php
$withdrawals = [
    ['kreator'=>'Rafi Ananda','bank'=>'BCA','account'=>'1234-5678-9012','amount'=>'Rp 2.400.000','requested'=>'2 jam lalu','status'=>'Menunggu'],
    ['kreator'=>'Hana Creator','bank'=>'Mandiri','account'=>'0987-6543-2109','amount'=>'Rp 5.100.000','requested'=>'5 jam lalu','status'=>'Menunggu'],
    ['kreator'=>'Luna Aesthetic','bank'=>'BNI','account'=>'8765-4321-0987','amount'=>'Rp 1.750.000','requested'=>'1 hari lalu','status'=>'Menunggu'],
    ['kreator'=>'Dimas Viral','bank'=>'BCA','account'=>'3456-7890-1234','amount'=>'Rp 980.000','requested'=>'2 hari lalu','status'=>'Diproses'],
    ['kreator'=>'Kevin Creator','bank'=>'BSI','account'=>'6789-0123-4567','amount'=>'Rp 3.200.000','requested'=>'3 hari lalu','status'=>'Berhasil'],
];
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-3 gap-4">
        @foreach([['label'=>'Menunggu Approval','val'=>'3','icon'=>'clock','color'=>'amber'],['label'=>'Total Dicairkan Bulan Ini','val'=>'Rp 48 Jt','icon'=>'banknote','color'=>'emerald'],['label'=>'Rata-rata Penarikan','val'=>'Rp 1.9 Jt','icon'=>'calculator','color'=>'brand']] as $s)
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 items-center justify-center mb-3">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color']==='brand'?'brand':$s['color'].'-400' }}"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-neutral-800/60"><h3 class="text-sm font-semibold text-white">Permintaan Penarikan</h3></div>
        <div class="divide-y divide-neutral-800/40">
            @foreach($withdrawals as $w)
            @php $sc=match($w['status']){'Berhasil'=>'bg-emerald-500/10 text-emerald-400 border-emerald-500/20','Diproses'=>'bg-blue-500/10 text-blue-400 border-blue-500/20',default=>'bg-amber-500/10 text-amber-400 border-amber-500/20'}; @endphp
            <div class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-sm font-bold text-emerald-400 flex-shrink-0">
                    {{ strtoupper(substr($w['kreator'],0,1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-white">{{ $w['kreator'] }}</p>
                    <p class="text-xs text-slate-500">{{ $w['bank'] }} · {{ $w['account'] }} · {{ $w['requested'] }}</p>
                </div>
                <p class="text-sm font-bold text-white">{{ $w['amount'] }}</p>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $sc }}">{{ $w['status'] }}</span>
                @if($w['status']==='Menunggu')
                <div class="flex gap-1.5">
                    <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-colors"><i data-lucide="check" class="w-3 h-3"></i> Cairkan</button>
                    <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl hover:bg-red-500/20 transition-colors"><i data-lucide="x" class="w-3 h-3"></i> Tolak</button>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
