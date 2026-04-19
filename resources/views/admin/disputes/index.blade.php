@extends('layouts.admin')
@section('title', 'Dispute & Komplain')
@section('page_title', 'Dispute & Komplain')
@section('page_subtitle', 'Penanganan perselisihan antara kreator dan brand')

@section('content')
@php
$disputes = [
    ['id'=>'DSP-001','kreator'=>'Rafi Ananda','brand'=>'Wardah Beauty','issue'=>'Pembayaran tidak diterima setelah konten disetujui','date'=>'26 Mar 2026','priority'=>'Tinggi','status'=>'Terbuka'],
    ['id'=>'DSP-002','kreator'=>'Dimas Viral','brand'=>'Samsung ID','issue'=>'Brief campaign berubah mendadak tanpa pemberitahuan','date'=>'25 Mar 2026','priority'=>'Sedang','status'=>'Terbuka'],
    ['id'=>'DSP-003','kreator'=>'Luna Aesthetic','brand'=>'Tokopedia','issue'=>'Konten ditolak tanpa alasan yang jelas','date'=>'23 Mar 2026','priority'=>'Rendah','status'=>'Diselidiki'],
    ['id'=>'DSP-004','kreator'=>'Hana Creator','brand'=>'Shopee ID','issue'=>'Deadline diperpanjang sepihak oleh brand','date'=>'20 Mar 2026','priority'=>'Sedang','status'=>'Selesai'],
];
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-3 gap-4">
        @foreach([['label'=>'Terbuka','val'=>'2','icon'=>'alert-triangle','color'=>'red'],['label'=>'Diselidiki','val'=>'1','icon'=>'search','color'=>'amber'],['label'=>'Selesai','val'=>'14','icon'=>'check-circle','color'=>'emerald']] as $s)
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
        <div class="p-5 border-b border-neutral-800/60"><h3 class="text-sm font-semibold text-white">Daftar Dispute</h3></div>
        <div class="divide-y divide-neutral-800/40">
            @foreach($disputes as $d)
            @php
            $sc=match($d['status']){'Terbuka'=>'bg-red-500/10 text-red-400 border-red-500/20','Diselidiki'=>'bg-amber-500/10 text-amber-400 border-amber-500/20',default=>'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'};
            $pc=match($d['priority']){'Tinggi'=>'text-red-400','Sedang'=>'text-amber-400',default=>'text-slate-400'};
            @endphp
            <div class="flex items-start gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                <div class="flex-shrink-0 pt-0.5">
                    <p class="text-xs font-mono text-slate-500">{{ $d['id'] }}</p>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-white mb-0.5">{{ $d['issue'] }}</p>
                    <p class="text-xs text-slate-500">{{ $d['kreator'] }} vs {{ $d['brand'] }} · {{ $d['date'] }}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs font-medium {{ $pc }}">● {{ $d['priority'] }}</span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $sc }}">{{ $d['status'] }}</span>
                    @if($d['status']!=='Selesai')
                    <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-brand/10 text-brand border border-brand/20 rounded-xl hover:bg-brand/20 transition-colors"><i data-lucide="scale" class="w-3 h-3"></i> Tangani</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
