@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan Platform')
@section('page_subtitle', 'Konfigurasi umum sistem ClipHub')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Sidebar Settings Menu -->
    <div class="space-y-1 bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-3 h-fit">
        @foreach([['icon'=>'sliders-horizontal','label'=>'Umum'],['icon'=>'percent','label'=>'Komisi & Fee'],['icon'=>'mail','label'=>'Email & SMTP'],['icon'=>'shield','label'=>'Keamanan'],['icon'=>'palette','label'=>'Tampilan'],['icon'=>'plug','label'=>'Integrasi API']] as $i => $m)
        <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors {{ $i===0?'bg-brand/10 text-brand border border-brand/20':'text-slate-400 hover:text-white hover:bg-white/5' }}">
            <i data-lucide="{{ $m['icon'] }}" class="w-4 h-4"></i> {{ $m['label'] }}
        </button>
        @endforeach
    </div>

    <!-- Settings Form -->
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-white mb-5">Pengaturan Umum</h3>
            <div class="space-y-4">
                @foreach([['label'=>'Nama Platform','val'=>'ClipHub','type'=>'text'],['label'=>'Email Support','val'=>'support@cliphub.com','type'=>'email'],['label'=>'URL Platform','val'=>'https://cliphub.com','type'=>'url'],['label'=>'Tagline','val'=>'Platform UGC & Clipper No. 1 di Indonesia','type'=>'text']] as $f)
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">{{ $f['label'] }}</label>
                    <input type="{{ $f['type'] }}" value="{{ $f['val'] }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-white mb-5">Komisi & Fee Platform</h3>
            <div class="grid grid-cols-2 gap-4">
                @foreach([['label'=>'Fee Platform dari Brand (%)','val'=>'10'],['label'=>'Fee Platform dari Kreator (%)','val'=>'5'],['label'=>'Minimum Payout Kreator (Rp)','val'=>'100000'],['label'=>'Maksimum Budget Campaign (Rp)','val'=>'500000000']] as $f)
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">{{ $f['label'] }}</label>
                    <input type="number" value="{{ $f['val'] }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <button class="flex items-center gap-2 px-6 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover transition-colors">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
            </button>
        </div>
    </div>
</div>
@endsection
