@extends('layouts.admin')
@section('title', 'Daftar Brand')
@section('page_title', 'Daftar Brand / Klien')
@section('page_subtitle', 'Direktori perusahaan dan brand yang menggunakan platform')

@section('content')
@php
$brands = [
    ['name'=>'Wardah Beauty','industry'=>'Cosmetics','spend'=>'Rp 120 Jt','campaigns'=>4,'status'=>'Aktif'],
    ['name'=>'Tokopedia Corp','industry'=>'E-Commerce','spend'=>'Rp 450 Jt','campaigns'=>12,'status'=>'Aktif'],
    ['name'=>'Shopee ID','industry'=>'E-Commerce','spend'=>'Rp 380 Jt','campaigns'=>8,'status'=>'Aktif'],
    ['name'=>'Indomie Official','industry'=>'F&B','spend'=>'Rp 45 Jt','campaigns'=>2,'status'=>'Aktif'],
    ['name'=>'Samsung Indonesia','industry'=>'Electronics','spend'=>'Rp 210 Jt','campaigns'=>5,'status'=>'Aktif'],
    ['name'=>'Kopi Kenangan','industry'=>'F&B','spend'=>'Rp 30 Jt','campaigns'=>1,'status'=>'Aktif'],
];
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Total Brand','val'=>'301','icon'=>'briefcase','color'=>'brand'],['label'=>'Brand Aktif Bulan Ini','val'=>'86','icon'=>'activity','color'=>'emerald'],['label'=>'Total Ad Spend','val'=>'Rp 4.2 M','icon'=>'trending-up','color'=>'amber'],['label'=>'Rata-rata Budget','val'=>'Rp 14 Jt','icon'=>'wallet','color'=>'violet']] as $s)
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
            <h3 class="text-sm font-semibold text-white">Daftar Brand</h3>
            <div class="flex items-center gap-2">
                <select class="bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-1.5 text-xs text-slate-300 outline-none">
                    <option>Semua Industri</option><option>E-Commerce</option><option>F&B</option><option>Tech</option>
                </select>
                <div class="flex items-center gap-2 bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500"></i>
                    <input type="text" placeholder="Cari brand..." class="bg-transparent text-xs text-slate-300 placeholder-slate-500 outline-none w-32">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="border-b border-neutral-800/60">
                    @foreach(['Brand','Industri','Total Ad Spend','Total Campaign','Status',''] as $h)
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                    @endforeach
                </tr></thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @foreach($brands as $b)
                    <tr class="hover:bg-white/[2%] transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center text-xs font-bold text-emerald-400 flex-shrink-0">
                                    {{ strtoupper(substr($b['name'],0,1)) }}
                                </div>
                                <p class="text-sm font-medium text-white">{{ $b['name'] }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">{{ $b['industry'] }}</td>
                        <td class="px-5 py-3.5"><span class="text-sm font-semibold text-white">{{ $b['spend'] }}</span></td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $b['campaigns'] }} campaign</td>
                        <td class="px-5 py-3.5"><span class="flex items-center gap-1.5 text-xs font-medium {{ $b['status']==='Aktif' ? 'text-emerald-400' : 'text-slate-500' }}"><span class="w-1.5 h-1.5 rounded-full {{ $b['status']==='Aktif' ? 'bg-emerald-400' : 'bg-slate-600' }} inline-block"></span>{{ $b['status'] }}</span></td>
                        <td class="px-5 py-3.5"><button class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
