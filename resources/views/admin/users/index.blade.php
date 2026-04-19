@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')
@section('page_subtitle', 'Kelola semua akun pengguna platform')

@section('content')
@php
$users = [
    ['name'=>'Rafi Ananda','email'=>'rafi@gmail.com','role'=>'kreator','status'=>'Aktif','joined'=>'20 Mar 2026','views'=>'1.2M'],
    ['name'=>'Wardah Beauty','email'=>'wardah@brand.com','role'=>'brand','status'=>'Aktif','joined'=>'18 Mar 2026','views'=>'-'],
    ['name'=>'Luna Aesthetic','email'=>'luna@gmail.com','role'=>'kreator','status'=>'Aktif','joined'=>'15 Mar 2026','views'=>'890K'],
    ['name'=>'Tokopedia Corp','email'=>'mkt@tokopedia.com','role'=>'brand','status'=>'Aktif','joined'=>'12 Mar 2026','views'=>'-'],
    ['name'=>'Dimas Viral','email'=>'dimas@gmail.com','role'=>'kreator','status'=>'Nonaktif','joined'=>'10 Mar 2026','views'=>'234K'],
    ['name'=>'Shopee Indonesia','email'=>'ads@shopee.com','role'=>'brand','status'=>'Aktif','joined'=>'8 Mar 2026','views'=>'-'],
    ['name'=>'Hana Creator','email'=>'hana@gmail.com','role'=>'kreator','status'=>'Aktif','joined'=>'5 Mar 2026','views'=>'3.1M'],
    ['name'=>'Indomie Official','email'=>'marketing@indomie.com','role'=>'brand','status'=>'Aktif','joined'=>'1 Mar 2026','views'=>'-'],
];
@endphp
<div class="space-y-5">
    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Total Pengguna','val'=>'1,248','icon'=>'users','color'=>'brand'],['label'=>'Kreator','val'=>'947','icon'=>'clapperboard','color'=>'violet'],['label'=>'Brand','val'=>'301','icon'=>'briefcase','color'=>'emerald'],['label'=>'Nonaktif','val'=>'24','icon'=>'user-x','color'=>'red']] as $s)
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

    <!-- Table -->
    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Semua Pengguna</h3>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-2 bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500"></i>
                    <input type="text" placeholder="Cari pengguna..." class="bg-transparent text-xs text-slate-300 placeholder-slate-500 outline-none w-40">
                </div>
                <select class="bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-xs text-slate-300 outline-none">
                    <option>Semua Role</option>
                    <option>Kreator</option>
                    <option>Brand</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800/60">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bergabung</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Views</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @foreach($users as $u)
                    <tr class="hover:bg-white/[2%] transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $u['role']==='kreator' ? 'bg-brand/20 text-brand' : 'bg-emerald-500/20 text-emerald-400' }} flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($u['name'],0,1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $u['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $u['email'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $u['role']==='kreator' ? 'bg-brand/10 text-brand border border-brand/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                                {{ ucfirst($u['role']) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="flex items-center gap-1.5 text-xs font-medium {{ $u['status']==='Aktif' ? 'text-emerald-400' : 'text-slate-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $u['status']==='Aktif' ? 'bg-emerald-400' : 'bg-slate-600' }} inline-block"></span>
                                {{ $u['status'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $u['joined'] }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $u['views'] }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5 justify-end">
                                <button class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
                                <button class="p-1.5 rounded-lg text-slate-500 hover:text-amber-400 hover:bg-amber-500/10 transition-colors"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button>
                                <button class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between px-5 py-3.5 border-t border-neutral-800/60">
            <p class="text-xs text-slate-500">Menampilkan 1–8 dari 1,248 pengguna</p>
            <div class="flex items-center gap-1">
                <button class="px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-white hover:bg-white/5 transition-colors">← Prev</button>
                <button class="px-3 py-1.5 rounded-lg text-xs bg-brand/10 text-brand border border-brand/20">1</button>
                <button class="px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-white hover:bg-white/5 transition-colors">2</button>
                <button class="px-3 py-1.5 rounded-lg text-xs text-slate-500 hover:text-white hover:bg-white/5 transition-colors">Next →</button>
            </div>
        </div>
    </div>
</div>
@endsection
