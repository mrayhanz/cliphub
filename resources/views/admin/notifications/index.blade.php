@extends('layouts.admin')
@section('title', 'Notifikasi Sistem')
@section('page_title', 'Notifikasi Sistem')
@section('page_subtitle', 'Kirim pengumuman dan notifikasi massal ke pengguna')

@section('content')
<div class="space-y-5" x-data="{ tab: 'send' }">
    <!-- Tabs -->
    <div class="flex gap-1 bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-1 w-fit">
        <button @click="tab='send'" :class="tab==='send'?'bg-brand/10 text-brand border border-brand/20':'text-slate-500 hover:text-white'" class="px-4 py-2 rounded-xl text-xs font-medium transition-all">Kirim Notifikasi</button>
        <button @click="tab='history'" :class="tab==='history'?'bg-brand/10 text-brand border border-brand/20':'text-slate-500 hover:text-white'" class="px-4 py-2 rounded-xl text-xs font-medium transition-all">Riwayat Terkirim</button>
    </div>

    <!-- Send Form -->
    <div x-show="tab==='send'" class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6 space-y-4">
        <h3 class="text-sm font-semibold text-white mb-4">Buat Notifikasi Baru</h3>
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-2">Target Penerima</label>
            <select class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                <option>Semua Pengguna</option><option>Hanya Kreator</option><option>Hanya Brand</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-2">Judul Notifikasi</label>
            <input type="text" placeholder="contoh: Pembaruan Platform Terbaru" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 placeholder-slate-600 outline-none focus:border-brand transition-colors">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-2">Isi Pesan</label>
            <textarea rows="4" placeholder="Tulis isi notifikasi di sini..." class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 placeholder-slate-600 outline-none focus:border-brand transition-colors resize-none"></textarea>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" class="flex items-center gap-2 px-5 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover transition-colors">
                <i data-lucide="send" class="w-4 h-4"></i> Kirim Sekarang
            </button>
            <button type="button" class="px-5 py-2.5 bg-neutral-800 border border-neutral-700 text-slate-300 text-sm font-medium rounded-xl hover:border-neutral-600 transition-colors">
                Jadwalkan
            </button>
        </div>
    </div>

    <!-- History -->
    <div x-show="tab==='history'" class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-neutral-800/60"><h3 class="text-sm font-semibold text-white">Riwayat Notifikasi</h3></div>
        <div class="divide-y divide-neutral-800/40">
            @foreach([['title'=>'Maintenance Dijadwalkan 30 Mar','target'=>'Semua Pengguna','sent'=>'25 Mar 2026','reach'=>'1,248'],['title'=>'Fitur Analitik Kreator Baru!','target'=>'Hanya Kreator','sent'=>'20 Mar 2026','reach'=>'947'],['title'=>'Flash Campaign Harbolnas!','target'=>'Hanya Brand','sent'=>'15 Mar 2026','reach'=>'301']] as $n)
            <div class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                <div class="w-8 h-8 rounded-xl bg-brand/10 border border-brand/20 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="bell" class="w-4 h-4 text-brand"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-white">{{ $n['title'] }}</p>
                    <p class="text-xs text-slate-500">{{ $n['target'] }} · {{ $n['sent'] }}</p>
                </div>
                <span class="text-xs text-slate-500">{{ $n['reach'] }} penerima</span>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Terkirim</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
