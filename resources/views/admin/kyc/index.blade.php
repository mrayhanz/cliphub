@extends('layouts.admin')
@section('title', 'Verifikasi KYC')
@section('page_title', 'Verifikasi KYC')
@section('page_subtitle', 'Review identitas kreator dan brand yang mendaftar')

@section('content')
@php
$kycs = [
    ['name'=>'Andika Pratama','role'=>'Kreator','id_type'=>'KTP','submitted'=>'2 jam lalu','status'=>'Menunggu'],
    ['name'=>'Sari Dewi Shop','role'=>'Brand','id_type'=>'NPWP','submitted'=>'4 jam lalu','status'=>'Menunggu'],
    ['name'=>'Bagas Content','role'=>'Kreator','id_type'=>'KTP','submitted'=>'6 jam lalu','status'=>'Menunggu'],
    ['name'=>'Elsa Viral ID','role'=>'Kreator','id_type'=>'KTP','submitted'=>'1 hari lalu','status'=>'Menunggu'],
    ['name'=>'PT Maju Jaya','role'=>'Brand','id_type'=>'SIUP','submitted'=>'1 hari lalu','status'=>'Menunggu'],
    ['name'=>'Kevin Creator','role'=>'Kreator','id_type'=>'KTP','submitted'=>'2 hari lalu','status'=>'Diverifikasi'],
    ['name'=>'Beauty Corp ID','role'=>'Brand','id_type'=>'NPWP','submitted'=>'3 hari lalu','status'=>'Ditolak'],
];
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-3 gap-4">
        @foreach([['label'=>'Menunggu Verifikasi','val'=>'5','icon'=>'clock','color'=>'amber'],['label'=>'Terverifikasi','val'=>'892','icon'=>'badge-check','color'=>'emerald'],['label'=>'Ditolak','val'=>'18','icon'=>'x-circle','color'=>'red']] as $s)
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
        <div class="p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Antrian Verifikasi KYC</h3>
        </div>
        <div class="divide-y divide-neutral-800/40">
            @foreach($kycs as $k)
            @php $sc=match($k['status']){'Diverifikasi'=>'bg-emerald-500/10 text-emerald-400 border-emerald-500/20','Ditolak'=>'bg-red-500/10 text-red-400 border-red-500/20',default=>'bg-amber-500/10 text-amber-400 border-amber-500/20'}; @endphp
            <div class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                <div class="w-10 h-10 rounded-full bg-brand/20 flex items-center justify-center text-sm font-bold text-brand flex-shrink-0">
                    {{ strtoupper(substr($k['name'],0,1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-white">{{ $k['name'] }}</p>
                    <p class="text-xs text-slate-500">{{ $k['role'] }} · Dokumen: {{ $k['id_type'] }} · {{ $k['submitted'] }}</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $sc }}">{{ $k['status'] }}</span>
                @if($k['status']==='Menunggu')
                <div class="flex gap-1.5">
                    <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-colors"><i data-lucide="check" class="w-3 h-3"></i> Verifikasi</button>
                    <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl hover:bg-red-500/20 transition-colors"><i data-lucide="x" class="w-3 h-3"></i> Tolak</button>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
