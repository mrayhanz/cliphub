@extends('layouts.admin')
@section('title', 'Analitik Platform')
@section('page_title', 'Analitik Platform')
@section('page_subtitle', 'Statistik dan performa keseluruhan ekosistem ClipHub')

@section('content')
@php
$months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
$vals = [45,62,38,80,60,95,72,88,55,90,75,100];
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Total Views Konten UGC','val'=>'48.2 Jt','delta'=>'+18% MoM','icon'=>'eye','color'=>'brand'],['label'=>'Campaign Selesai','val'=>'6','delta'=>'+2 bulan ini','icon'=>'check-circle','color'=>'emerald'],['label'=>'Total Pembayaran','val'=>'Rp 1.2 M','delta'=>'+31% MoM','icon'=>'trending-up','color'=>'amber'],['label'=>'Rata-rata CTR Konten','val'=>'4.7%','delta'=>'+0.4% MoM','icon'=>'mouse-pointer','color'=>'violet']] as $s)
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 flex items-center justify-center">
                    <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color']==='brand'?'brand':$s['color'].'-400' }}"></i>
                </div>
                <span class="text-[10px] text-emerald-400 font-medium">{{ $s['delta'] }}</span>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-white mb-1">Views Konten UGC per Bulan</h3>
            <p class="text-xs text-slate-500 mb-5">Dalam jutaan views (2026)</p>
            <div class="flex items-end justify-between gap-1.5 h-36">
                @foreach($vals as $i => $v)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full rounded-t-lg bg-brand/40 hover:bg-brand/70 transition-colors" style="height:{{ $v }}%"></div>
                    <span class="text-[9px] text-slate-600">{{ $months[$i] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-white mb-1">Top Kreator Bulan Ini</h3>
            <p class="text-xs text-slate-500 mb-4">Berdasarkan total views konten UGC</p>
            <div class="space-y-3">
                @foreach([['name'=>'Hana Creator','views'=>'3.1M','growth'=>'+22%'],['name'=>'Rafi Ananda','views'=>'1.2M','growth'=>'+14%'],['name'=>'Luna Aesthetic','views'=>'890K','growth'=>'+9%'],['name'=>'Rizky Clips','views'=>'640K','growth'=>'+5%'],['name'=>'Nadia UGC','views'=>'410K','growth'=>'+3%']] as $i => $k)
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-600 w-4">{{ $i+1 }}</span>
                    <div class="w-7 h-7 rounded-full bg-brand/20 flex items-center justify-center text-xs font-bold text-brand flex-shrink-0">
                        {{ strtoupper(substr($k['name'],0,1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-white">{{ $k['name'] }}</span>
                            <span class="text-xs text-slate-400">{{ $k['views'] }}</span>
                        </div>
                        <div class="h-1 rounded-full bg-neutral-700 overflow-hidden">
                            <div class="h-full bg-brand rounded-full" style="width:{{ 100 - ($i * 18) }}%"></div>
                        </div>
                    </div>
                    <span class="text-[10px] text-emerald-400 font-medium w-10 text-right">{{ $k['growth'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
