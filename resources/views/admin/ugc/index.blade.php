@extends('layouts.admin')
@section('title', 'Moderasi Konten UGC')
@section('page_title', 'Moderasi Konten UGC')
@section('page_subtitle', 'Review dan approve/reject video konten kreator')

@section('content')
@php
$contents = [
    ['kreator'=>'Rafi Ananda','campaign'=>'Summer Glow 2026','brand'=>'Wardah','thumb'=>'🎬','duration'=>'0:58','submitted'=>'2 jam lalu','status'=>'Menunggu'],
    ['kreator'=>'Luna Aesthetic','campaign'=>'Summer Glow 2026','brand'=>'Wardah','thumb'=>'🎥','duration'=>'1:12','submitted'=>'5 jam lalu','status'=>'Menunggu'],
    ['kreator'=>'Hana Creator','campaign'=>'Harbolnas 5.5','brand'=>'Tokopedia','thumb'=>'📹','duration'=>'0:45','submitted'=>'8 jam lalu','status'=>'Menunggu'],
    ['kreator'=>'Dimas Viral','campaign'=>'Pagi Bareng Kenangan','brand'=>'Kopi Kenangan','thumb'=>'🎦','duration'=>'1:02','submitted'=>'1 hari lalu','status'=>'Disetujui'],
    ['kreator'=>'Rizky Clips','campaign'=>'Harbolnas 5.5','brand'=>'Tokopedia','thumb'=>'🎬','duration'=>'0:33','submitted'=>'1 hari lalu','status'=>'Ditolak'],
    ['kreator'=>'Nadia UGC','campaign'=>'Double Day Deals','brand'=>'Shopee','thumb'=>'🎥','duration'=>'0:55','submitted'=>'2 hari lalu','status'=>'Disetujui'],
];
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Menunggu Review','val'=>'12','icon'=>'clock','color'=>'amber'],['label'=>'Disetujui','val'=>'284','icon'=>'check-circle','color'=>'emerald'],['label'=>'Ditolak','val'=>'31','icon'=>'x-circle','color'=>'red'],['label'=>'Total Konten','val'=>'327','icon'=>'film','color'=>'brand']] as $s)
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
        <div class="flex items-center justify-between p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Antrian Moderasi</h3>
            <div class="flex gap-2">
                @foreach(['Semua','Menunggu','Disetujui','Ditolak'] as $f)
                <button class="px-3 py-1.5 text-xs rounded-lg {{ $loop->index===1?'bg-brand/10 text-brand border border-brand/20':'text-slate-500 hover:text-white hover:bg-white/5' }} transition-colors">{{ $f }}</button>
                @endforeach
            </div>
        </div>
        <div class="divide-y divide-neutral-800/40">
            @foreach($contents as $c)
            @php
            $sc = match($c['status']){
                'Menunggu'=>'bg-amber-500/10 text-amber-400 border-amber-500/20',
                'Disetujui'=>'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                default=>'bg-red-500/10 text-red-400 border-red-500/20'
            };
            @endphp
            <div class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                <div class="w-20 h-12 rounded-xl bg-neutral-800 border border-neutral-700 flex items-center justify-center text-2xl flex-shrink-0">
                    {{ $c['thumb'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white">{{ $c['kreator'] }}</p>
                    <p class="text-xs text-slate-500">{{ $c['campaign'] }} · {{ $c['brand'] }} · {{ $c['duration'] }}</p>
                </div>
                <span class="text-xs text-slate-500">{{ $c['submitted'] }}</span>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $sc }}">{{ $c['status'] }}</span>
                @if($c['status']==='Menunggu')
                <div class="flex items-center gap-1.5">
                    <button class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-colors">
                        <i data-lucide="check" class="w-3 h-3"></i> Setujui
                    </button>
                    <button class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl hover:bg-red-500/20 transition-colors">
                        <i data-lucide="x" class="w-3 h-3"></i> Tolak
                    </button>
                </div>
                @else
                <button class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
