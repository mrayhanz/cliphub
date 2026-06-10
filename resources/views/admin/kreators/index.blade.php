@extends('layouts.admin')
@section('title', 'Daftar Kreator')
@section('page_title', 'Daftar Kreator UGC')
@section('page_subtitle', 'Direktori portofolio dan performa kreator platform')

@section('content')
@php
$kreators = \App\Models\User::where('role', 'kreator')->latest()->get();
$totalKreators = \App\Models\User::where('role', 'kreator')->count();
$totalSubmissions = \App\Models\Submission::whereHas('user', function($q) { $q->where('role', 'kreator'); })->where('status', 'approved')->count();
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-brand/10 border border-brand/20 items-center justify-center">
                <i data-lucide="users" class="w-4 h-4 text-brand"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($totalKreators) }}</p>
            <p class="text-xs text-slate-500">Total Kreator</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-amber/10 border border-amber/20 items-center justify-center">
                <i data-lucide="star" class="w-4 h-4 text-amber-400"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($totalKreators) }}</p>
            <p class="text-xs text-slate-500">Kreator Aktif</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-emerald/10 border border-emerald/20 items-center justify-center">
                <i data-lucide="film" class="w-4 h-4 text-emerald-400"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($totalSubmissions) }}</p>
            <p class="text-xs text-slate-500">Konten Dibuat</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-violet/10 border border-violet/20 items-center justify-center">
                <i data-lucide="star-half" class="w-4 h-4 text-violet-400"></i>
            </div>
            <p class="text-xl font-bold text-white">-</p>
            <p class="text-xs text-slate-500">Rata-rata Rating</p>
        </div>
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
                    @forelse($kreators as $k)
                    <tr class="hover:bg-white/[2%] transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($k->avatar)
                                <img src="{{ $k->avatar }}" alt="{{ $k->name }}" class="w-8 h-8 rounded-full flex-shrink-0">
                                @else
                                <div class="w-8 h-8 rounded-full bg-brand/20 flex items-center justify-center text-xs font-bold text-brand flex-shrink-0">
                                    {{ strtoupper(substr($k->name,0,1)) }}
                                </div>
                                @endif
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-white">{{ $k->name }}</p>
                                        @if($k->google_id)
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 font-semibold">Google</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500">{{ $k->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5"><span class="text-xs font-semibold px-2 py-0.5 rounded border text-brand bg-brand/10 border-brand/20">Kreator</span></td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">-</td>
                        <td class="px-5 py-3.5"><div class="flex items-center gap-1.5 text-xs font-semibold text-white"><i data-lucide="star" class="w-3 h-3 text-amber-400 fill-amber-400"></i>-</div></td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ \App\Models\Submission::where('user_id', $k->id)->where('status', 'approved')->count() }} konten</td>
                        <td class="px-5 py-3.5"><span class="flex items-center gap-1.5 text-xs font-medium text-emerald-400"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>Aktif</span></td>
                        <td class="px-5 py-3.5"><button class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-500 text-sm">
                            Belum ada kreator terdaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
