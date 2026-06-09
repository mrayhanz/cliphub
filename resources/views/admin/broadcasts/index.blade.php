@extends('layouts.admin')
@section('title', 'Broadcast Pengumuman')
@section('page_title', 'Broadcast Pengumuman')
@section('page_subtitle', 'Kirim notifikasi massal ke pengguna platform')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl px-4 py-3 text-sm">
        <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="flex items-start gap-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl px-4 py-3 text-sm">
        <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
        <ul class="space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['label'=>'Total Broadcast',   'value'=>$stats['total'],   'icon'=>'megaphone',    'color'=>'emerald'],
            ['label'=>'Total Kreator',      'value'=>$stats['kreator'], 'icon'=>'clapperboard', 'color'=>'blue'],
            ['label'=>'Total Brand',        'value'=>$stats['brand'],   'icon'=>'briefcase',    'color'=>'purple'],
        ] as $s)
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-{{ $s['color'] }}-500/10 flex items-center justify-center flex-shrink-0">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color'] }}-400"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-white">{{ number_format($s['value']) }}</p>
                <p class="text-[11px] text-slate-500">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

        {{-- ===== FORM BUAT BROADCAST ===== --}}
        <div class="lg:col-span-2">
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-neutral-800/60 flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                        <i data-lucide="send" class="w-3.5 h-3.5 text-emerald-400"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white">Buat Broadcast</h3>
                        <p class="text-[10px] text-slate-500">Kirim pengumuman ke pengguna</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.broadcasts.send') }}"
                      x-data="{
                          type: 'info',
                          target: 'all',
                          scheduled: false,
                          charCount: 0,
                          typeConfig: {
                              info:      { label: 'Info',      color: 'blue',   icon: 'info' },
                              warning:   { label: 'Peringatan', color: 'amber',  icon: 'triangle-alert' },
                              important: { label: 'Penting',   color: 'red',    icon: 'alert-octagon' },
                              promo:     { label: 'Promosi',   color: 'purple', icon: 'sparkles' },
                          }
                      }"
                      class="p-5 space-y-4">
                    @csrf

                    {{-- Judul --}}
                    <div>
                        <label class="block text-[11px] font-medium text-slate-400 mb-1.5">
                            Judul Pengumuman <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            placeholder="Contoh: Maintenance 24 Mei 2026"
                            maxlength="150"
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors"
                            required>
                    </div>

                    {{-- Tipe --}}
                    <div>
                        <label class="block text-[11px] font-medium text-slate-400 mb-1.5">Tipe Pesan</label>
                        <div class="grid grid-cols-2 gap-1.5">
                            <template x-for="[key, cfg] in Object.entries(typeConfig)" :key="key">
                                <label
                                    :class="{
                                        'border-blue-500/40 bg-blue-500/10 text-blue-400':   type === key && key === 'info',
                                        'border-amber-500/40 bg-amber-500/10 text-amber-400': type === key && key === 'warning',
                                        'border-red-500/40 bg-red-500/10 text-red-400':       type === key && key === 'important',
                                        'border-purple-500/40 bg-purple-500/10 text-purple-400': type === key && key === 'promo',
                                        'border-neutral-700 bg-neutral-800/40 text-slate-500': type !== key
                                    }"
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-all text-xs font-medium"
                                >
                                    <input type="radio" name="type" :value="key" x-model="type" class="hidden">
                                    <i :data-lucide="cfg.icon" class="w-3 h-3 flex-shrink-0"></i>
                                    <span x-text="cfg.label"></span>
                                </label>
                            </template>
                        </div>
                        <input type="hidden" name="type" :value="type">
                    </div>

                    {{-- Target --}}
                    <div>
                        <label class="block text-[11px] font-medium text-slate-400 mb-1.5">Target Penerima</label>
                        <div class="flex gap-1.5">
                            @foreach(['all'=>'Semua User','kreator'=>'Kreator','brand'=>'Brand'] as $val=>$lbl)
                            <label
                                :class="target === '{{ $val }}' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : 'bg-neutral-800/40 text-slate-500 border-neutral-700'"
                                class="flex-1 flex items-center justify-center px-2 py-2 rounded-lg border cursor-pointer transition-all text-[11px] font-semibold">
                                <input type="radio" name="target" value="{{ $val }}" x-model="target" class="hidden">
                                {{ $lbl }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Isi Pesan --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-[11px] font-medium text-slate-400">Isi Pesan <span class="text-red-400">*</span></label>
                            <span class="text-[10px] text-slate-600" x-text="charCount + '/1000'"></span>
                        </div>
                        <textarea name="message" rows="4" maxlength="1000"
                            @input="charCount = $event.target.value.length"
                            placeholder="Tulis isi pengumuman di sini..."
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors resize-none"
                            required>{{ old('message') }}</textarea>
                    </div>

                    {{-- Jadwalkan --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-medium text-slate-400">Jadwalkan Pengiriman</label>
                            <button type="button" @click="scheduled = !scheduled"
                                :class="scheduled ? 'bg-emerald-500' : 'bg-neutral-700'"
                                class="relative w-9 h-5 rounded-full transition-colors duration-200 focus:outline-none">
                                <span :class="scheduled ? 'translate-x-4' : 'translate-x-0.5'"
                                    class="block w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"></span>
                            </button>
                        </div>
                        <div x-show="scheduled" x-transition class="mt-2">
                            <input type="datetime-local" name="scheduled_at"
                                value="{{ old('scheduled_at') }}"
                                min="{{ now()->format('Y-m-d\TH:i') }}"
                                class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors">
                            <p class="text-[10px] text-slate-600 mt-1">Broadcast akan dikirim otomatis pada waktu yang ditentukan.</p>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-emerald-500/20">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span x-text="scheduled ? 'Jadwalkan Broadcast' : 'Kirim Sekarang'"></span>
                    </button>
                </form>
            </div>
        </div>

        {{-- ===== RIWAYAT BROADCAST ===== --}}
        <div class="lg:col-span-3">
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-neutral-800/60 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-slate-700/50 flex items-center justify-center">
                            <i data-lucide="history" class="w-3.5 h-3.5 text-slate-400"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">Riwayat Broadcast</h3>
                            <p class="text-[10px] text-slate-500">{{ $broadcasts->total() }} total broadcast terkirim</p>
                        </div>
                    </div>
                </div>

                @if($broadcasts->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-center px-6">
                    <div class="w-12 h-12 rounded-2xl bg-neutral-800 flex items-center justify-center mb-3">
                        <i data-lucide="megaphone-off" class="w-5 h-5 text-slate-600"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Belum ada broadcast</p>
                    <p class="text-xs text-slate-700 mt-1">Broadcast yang kamu kirim akan muncul di sini.</p>
                </div>
                @else
                <div class="divide-y divide-neutral-800/60">
                    @foreach($broadcasts as $bc)
                    @php
                        $colorMap = ['info'=>'blue','warning'=>'amber','important'=>'red','promo'=>'purple'];
                        $iconMap  = ['info'=>'info','warning'=>'triangle-alert','important'=>'alert-octagon','promo'=>'sparkles'];
                        $c = $colorMap[$bc->type] ?? 'slate';
                        $ico = $iconMap[$bc->type] ?? 'bell';
                    @endphp
                    <div class="px-5 py-4 hover:bg-white/[0.015] transition-colors">
                        <div class="flex items-start gap-3">
                            {{-- Icon --}}
                            <div class="w-8 h-8 rounded-xl bg-{{ $c }}-500/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="{{ $ico }}" class="w-3.5 h-3.5 text-{{ $c }}-400"></i>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-white truncate">{{ $bc->title }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $bc->message }}</p>
                                    </div>
                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.broadcasts.destroy', $bc) }}"
                                          onsubmit="return confirm('Hapus broadcast ini dari riwayat?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-slate-700 hover:text-red-400 transition-colors p-1 rounded-lg hover:bg-red-500/10 flex-shrink-0">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="flex items-center flex-wrap gap-2 mt-2">
                                    {{-- Tipe badge --}}
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-{{ $c }}-500/15 text-{{ $c }}-400 border border-{{ $c }}-500/25">
                                        <i data-lucide="{{ $ico }}" class="w-2.5 h-2.5"></i>
                                        {{ $bc->typeLabel() }}
                                    </span>

                                    {{-- Target --}}
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium px-2 py-0.5 rounded-full bg-neutral-800 text-slate-400 border border-neutral-700">
                                        <i data-lucide="users" class="w-2.5 h-2.5"></i>
                                        {{ $bc->targetLabel() }}
                                    </span>

                                    {{-- Penerima --}}
                                    <span class="text-[10px] text-slate-600">
                                        {{ number_format($bc->recipient_count) }} penerima
                                    </span>

                                    {{-- Status --}}
                                    @if($bc->sent_at)
                                    <span class="text-[10px] text-slate-600">
                                        · Terkirim {{ $bc->sent_at->diffForHumans() }}
                                    </span>
                                    @elseif($bc->scheduled_at)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <i data-lucide="clock" class="w-2.5 h-2.5"></i>
                                        Jadwal: {{ $bc->scheduled_at->format('d M Y, H:i') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($broadcasts->hasPages())
                <div class="px-5 py-3 border-t border-neutral-800/60">
                    {{ $broadcasts->links() }}
                </div>
                @endif
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
