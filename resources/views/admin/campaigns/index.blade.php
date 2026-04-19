@extends('layouts.admin')
@section('title', 'Semua Campaign')
@section('page_title', 'Manajemen Campaign')
@section('page_subtitle', 'Monitor seluruh campaign aktif di platform')

@section('content')
@php
$campaigns = [
    ['brand'=>'Wardah Beauty','title'=>'Summer Glow 2026','kreators'=>48,'target'=>'500K Views','progress'=>72,'budget'=>'Rp 24 Jt','status'=>'Aktif','deadline'=>'30 Apr 2026'],
    ['brand'=>'Tokopedia Sale','title'=>'Harbolnas 5.5','kreators'=>102,'target'=>'1M Views','progress'=>38,'budget'=>'Rp 75 Jt','status'=>'Aktif','deadline'=>'5 Mei 2026'],
    ['brand'=>'Indomie Goreng','title'=>'Kreasiku Kreasimu','kreators'=>23,'target'=>'200K Views','progress'=>91,'budget'=>'Rp 12 Jt','status'=>'Selesai','deadline'=>'20 Mar 2026'],
    ['brand'=>'Shopee 12.12','title'=>'Double Day Deals','kreators'=>77,'target'=>'750K Views','progress'=>55,'budget'=>'Rp 48 Jt','status'=>'Aktif','deadline'=>'12 Mei 2026'],
    ['brand'=>'Samsung ID','title'=>'Galaxy S25 Launch','kreators'=>34,'target'=>'300K Views','progress'=>22,'budget'=>'Rp 30 Jt','status'=>'Draft','deadline'=>'15 Apr 2026'],
    ['brand'=>'Kopi Kenangan','title'=>'Pagi Bareng Kenangan','kreators'=>19,'target'=>'150K Views','progress'=>65,'budget'=>'Rp 8 Jt','status'=>'Aktif','deadline'=>'10 Apr 2026'],
];
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Total Campaign','val'=>'86','icon'=>'megaphone','color'=>'brand'],['label'=>'Aktif','val'=>'71','icon'=>'play-circle','color'=>'emerald'],['label'=>'Draft','val'=>'9','icon'=>'file-pen-line','color'=>'slate'],['label'=>'Selesai','val'=>'6','icon'=>'check-circle','color'=>'blue']] as $s)
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 flex items-center justify-center">
                    <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color'] === 'brand' ? 'brand' : $s['color'].'-400' }}"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Semua Campaign</h3>
            <select class="bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-xs text-slate-300 outline-none">
                <option>Semua Status</option><option>Aktif</option><option>Draft</option><option>Selesai</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800/60">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Campaign</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Brand</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kreator</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Progress</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Budget</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Deadline</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @foreach($campaigns as $c)
                    @php
                    $statusClass = match($c['status']){
                        'Aktif'=>'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                        'Selesai'=>'bg-blue-500/10 text-blue-400 border-blue-500/20',
                        default=>'bg-neutral-700/50 text-slate-400 border-neutral-600'
                    };
                    $barColor = $c['progress']>=80?'bg-emerald-500':($c['progress']>=50?'bg-brand':'bg-amber-500');
                    @endphp
                    <tr class="hover:bg-white/[2%] transition-colors">
                        <td class="px-5 py-3.5"><p class="text-sm font-medium text-white">{{ $c['title'] }}</p><p class="text-xs text-slate-500">Target: {{ $c['target'] }}</p></td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">{{ $c['brand'] }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">{{ $c['kreators'] }} kreator</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2 w-28">
                                <div class="flex-1 h-1.5 rounded-full bg-neutral-700 overflow-hidden">
                                    <div class="h-full rounded-full {{ $barColor }}" style="width:{{ $c['progress'] }}%"></div>
                                </div>
                                <span class="text-xs text-slate-400 w-8 text-right">{{ $c['progress'] }}%</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">{{ $c['budget'] }}</td>
                        <td class="px-5 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusClass }}">{{ $c['status'] }}</span></td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $c['deadline'] }}</td>
                        <td class="px-5 py-3.5">
                            <button class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
