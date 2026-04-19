@extends('layouts.admin')
@section('title', 'Daftar Kreator')
@section('page_title', 'Daftar Kreator UGC')
@section('page_subtitle', 'Direktori portofolio dan performa kreator platform')

@section('content')
@php
$kreators = [
    ['name'=>'Rafi Ananda','handle'=>'@rafiananda_','level'=>'Pro','niche'=>'Tech, Gadget','rating'=>'4.9/5.0','jobs'=>45,'status'=>'Aktif'],
    ['name'=>'Hana Creator','handle'=>'@hanacreates','level'=>'Star','niche'=>'Beauty, Fashion','rating'=>'5.0/5.0','jobs'=>112,'status'=>'Aktif'],
    ['name'=>'Luna Aesthetic','handle'=>'@luna_vibes','level'=>'Pro','niche'=>'Lifestyle, Food','rating'=>'4.8/5.0','jobs'=>34,'status'=>'Aktif'],
    ['name'=>'Dimas Viral','handle'=>'@dimas.tv_','level'=>'Beginner','niche'=>'Gaming, Tech','rating'=>'4.2/5.0','jobs'=>5,'status'=>'Aktif'],
    ['name'=>'Rizky Clips','handle'=>'@rizclips','level'=>'Star','niche'=>'Automotive','rating'=>'4.9/5.0','jobs'=>88,'status'=>'Aktif'],
    ['name'=>'Nadia UGC','handle'=>'@nadiacontent','level'=>'Beginner','niche'=>'Home, Decor','rating'=>'4.5/5.0','jobs'=>12,'status'=>'Aktif'],
];
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Total Kreator','val'=>'947','icon'=>'users','color'=>'brand'],['label'=>'Kreator Star','val'=>'102','icon'=>'star','color'=>'amber'],['label'=>'Konten Dibuat','val'=>'4,892','icon'=>'film','color'=>'emerald'],['label'=>'Rata-rata Rating','val'=>'4.6/5.0','icon'=>'star-half','color'=>'violet']] as $s)
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
            <h3 class="text-sm font-semibold text-white">Daftar Kreator</h3>
            <div class="flex items-center gap-2">
                <select class="bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-1.5 text-xs text-slate-300 outline-none">
                    <option>Semua Level</option><option>Star</option><option>Pro</option><option>Beginner</option>
                </select>
                <div class="flex items-center gap-2 bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500"></i>
                    <input type="text" placeholder="Cari kreator..." class="bg-transparent text-xs text-slate-300 placeholder-slate-500 outline-none w-32">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="border-b border-neutral-800/60">
                    @foreach(['Kreator','Level','Niche Konten','Rating','Jobs Selesai','Status',''] as $h)
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                    @endforeach
                </tr></thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @foreach($kreators as $k)
                    @php $lc=match($k['level']){'Star'=>'text-amber-400 bg-amber-500/10 border-amber-500/20','Pro'=>'text-brand bg-brand/10 border-brand/20',default=>'text-slate-400 bg-neutral-800 border-neutral-700'}; @endphp
                    <tr class="hover:bg-white/[2%] transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand/20 flex items-center justify-center text-xs font-bold text-brand flex-shrink-0">
                                    {{ strtoupper(substr($k['name'],0,1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $k['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $k['handle'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5"><span class="text-xs font-semibold px-2 py-0.5 rounded border {{ $lc }}">{{ $k['level'] }}</span></td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">{{ $k['niche'] }}</td>
                        <td class="px-5 py-3.5"><div class="flex items-center gap-1.5 text-xs font-semibold text-white"><i data-lucide="star" class="w-3 h-3 text-amber-400 fill-amber-400"></i>{{ $k['rating'] }}</div></td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $k['jobs'] }} campaign</td>
                        <td class="px-5 py-3.5"><span class="flex items-center gap-1.5 text-xs font-medium {{ $k['status']==='Aktif' ? 'text-emerald-400' : 'text-slate-500' }}"><span class="w-1.5 h-1.5 rounded-full {{ $k['status']==='Aktif' ? 'bg-emerald-400' : 'bg-slate-600' }} inline-block"></span>{{ $k['status'] }}</span></td>
                        <td class="px-5 py-3.5"><button class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
