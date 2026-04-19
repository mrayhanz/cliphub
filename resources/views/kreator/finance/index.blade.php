@extends('layouts.kreator')

@section('title', 'Clipper Wallet')

@section('content')
<div class="max-w-6xl mx-auto pb-12 pt-2">

    {{-- HEADER --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 shadow-[0_4px_12px_rgba(16,185,129,0.15)]">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="mb-6 bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 shadow-[0_4px_12px_rgba(244,63,94,0.15)]">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') ?? $errors->first() }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5 mb-8">
        <div>
            <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight mb-2">My Wallet</h1>
            <p class="text-sm text-slate-400">Atur penghasilan, tarik dana komisi, dan kelola rekening kamu.</p>
        </div>
        <button class="flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-sm font-bold text-white transition-all hover:scale-[1.02] active:scale-95 bg-gradient-to-br from-violet-600 to-fuchsia-500 shadow-[0_8px_24px_rgba(139,92,246,0.35)]" 
                onclick="document.getElementById('wd_modal').classList.remove('hidden')">
            <i data-lucide="arrow-right-left" class="w-4 h-4"></i>
            Tarik Dana Sekarang
        </button>
    </div>

    {{-- METRICS ROW (3 Columns) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        
        {{-- Card 1: Saldo Utama --}}
        <div class="bg-[#0a0a0a] rounded-[1.5rem] p-6 relative overflow-hidden flex flex-col min-h-[160px] transition-all duration-200 shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)] hover:-translate-y-0.5 hover:shadow-[inset_0_0_0_1px_rgba(139,92,246,0.2),_0_8px_30px_rgba(0,0,0,0.5)] group before:absolute before:inset-x-0 before:top-0 before:h-[1.5px] before:bg-gradient-to-r before:from-transparent before:via-violet-500/50 before:to-transparent before:opacity-0 hover:before:opacity-100 before:transition-opacity before:duration-300">
            <div class="flex items-start justify-between">
                <div class="w-[44px] h-[44px] rounded-[1.25rem] flex items-center justify-center mb-5 bg-violet-500/10 text-violet-400 shadow-[inset_0_0_0_1px_rgba(139,92,246,0.2)]">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(139,92,246,0.4)]">💰</span>
                </div>
                <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-500/10 shadow-[inset_0_0_0_1px_rgba(16,185,129,0.25)]">
                    Siap Tarik
                </span>
            </div>
            <div class="mt-auto">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Total Saldo Aktif</p>
                <h2 class="text-3xl font-black text-white leading-none tracking-tight">
                    <span class="text-xl text-slate-400 font-bold mr-0.5">Rp</span>{{ number_format(auth()->user()->balance, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        {{-- Card 2: Escrow / Pending --}}
        <div class="bg-[#0a0a0a] rounded-[1.5rem] p-6 relative overflow-hidden flex flex-col min-h-[160px] transition-all duration-200 shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)] hover:-translate-y-0.5 hover:shadow-[inset_0_0_0_1px_rgba(139,92,246,0.2),_0_8px_30px_rgba(0,0,0,0.5)] group before:absolute before:inset-x-0 before:top-0 before:h-[1.5px] before:bg-gradient-to-r before:from-transparent before:via-violet-500/50 before:to-transparent before:opacity-0 hover:before:opacity-100 before:transition-opacity before:duration-300">
            <div class="flex items-start justify-between">
                <div class="w-[44px] h-[44px] rounded-[1.25rem] flex items-center justify-center mb-5 bg-amber-500/10 text-amber-400 shadow-[inset_0_0_0_1px_rgba(245,158,11,0.2)]">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(245,158,11,0.4)]">⏳</span>
                </div>
            </div>
            <div class="mt-auto">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Dana Tertahan (Pending)</p>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-2xl font-black text-white leading-none tracking-tight">
                        <span class="text-lg text-slate-400 font-bold mr-0.5">Rp</span>{{ number_format($pending_withdrawal, 0, ',', '.') }}
                    </h2>
                    <i data-lucide="info" class="w-3.5 h-3.5 text-slate-600 cursor-help" title="Menunggu pencairan diproses oleh admin/brand"></i>
                </div>
            </div>
        </div>

        {{-- Card 3: Rekening --}}
        <div class="bg-[#0a0a0a] rounded-[1.5rem] p-6 relative overflow-hidden flex flex-col min-h-[160px] transition-all duration-200 shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)] hover:-translate-y-0.5 hover:shadow-[inset_0_0_0_1px_rgba(139,92,246,0.2),_0_8px_30px_rgba(0,0,0,0.5)] group before:absolute before:inset-x-0 before:top-0 before:h-[1.5px] before:bg-gradient-to-r before:from-transparent before:via-violet-500/50 before:to-transparent before:opacity-0 hover:before:opacity-100 before:transition-opacity before:duration-300">
            <div class="flex items-start justify-between">
                <div class="w-[44px] h-[44px] rounded-[1.25rem] flex items-center justify-center mb-5 bg-sky-500/10 text-sky-400 shadow-[inset_0_0_0_1px_rgba(56,189,248,0.2)]">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(59,130,246,0.4)]">🏦</span>
                </div>
                <button onclick="document.getElementById('edit_bank_modal').classList.remove('hidden')" class="text-[9px] font-bold text-slate-400 hover:text-white px-2.5 py-1 rounded-md transition-colors shadow-[inset_0_0_0_1px_rgba(255,255,255,0.1)] bg-white/[0.02]">
                    Ubah
                </button>
            </div>
            <div class="mt-auto">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Rekening Pencairan</p>
                <p class="text-lg font-black text-white leading-tight" id="disp_bank_acc">
                    @if(auth()->user()->bank_name && auth()->user()->bank_account)
                        {{ auth()->user()->bank_name }} — {{ auth()->user()->bank_account }}
                    @else
                        Belum Diatur
                    @endif
                </p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5 truncate" id="disp_bank_name">{{ strtoupper(auth()->user()->name) }}</p>
            </div>
        </div>

    </div>

    {{-- TRANSACTION HISTORY --}}
    <div class="bg-[#0a0a0a] rounded-[1.5rem] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)] overflow-hidden">
        
        <div class="p-6 shadow-[0_1px_0_rgba(255,255,255,0.04)] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 sm:gap-0">
            <h3 class="text-base font-black text-white">Riwayat Transaksi</h3>
            
            {{-- Filter Pills --}}
            <div class="flex items-center gap-1.5 p-1 rounded-xl bg-white/[0.03] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.05)] w-fit">
                <button class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white/10 text-white shadow-sm transition-colors">Semua</button>
                <button class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-400 hover:text-white transition-colors">Masuk</button>
                <button class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-400 hover:text-white transition-colors">Keluar</button>
            </div>
        </div>

        <div class="flex flex-col">
            @forelse($transactions as $t)
            <div class="flex items-center gap-4 min-w-0 justify-between px-6 py-5 transition-colors duration-200 hover:bg-white/[0.02] border-b border-white/[0.03] last:border-0">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $t['is_income'] ? 'bg-emerald-500/10 text-emerald-400' : 'bg-white/5 text-slate-200' }}">
                        <i data-lucide="{{ $t['is_income'] ? 'arrow-down-left' : 'arrow-up-right' }}" class="w-4.5 h-4.5"></i>
                    </div>
                    <div class="min-w-0 pr-4">
                        <p class="text-[13px] font-bold text-white mb-0.5 truncate">{{ $t['type'] }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ $t['desc'] }}</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-black {{ $t['is_income'] ? 'text-emerald-400' : 'text-slate-300' }}">{{ $t['amount'] }}</p>
                    <p class="text-[10px] font-semibold text-slate-500 mt-1">{{ $t['date'] }}</p>
                </div>
            </div>
            @empty
                <div class="px-6 py-12 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-4">
                        <i data-lucide="receipt" class="w-8 h-8 text-slate-600"></i>
                    </div>
                    <h3 class="text-white font-bold mb-1">Belum Ada Transaksi</h3>
                    <p class="text-xs text-slate-500">Histori penarikan atau pembayaranmu akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
        
        {{-- Load More --}}
        <div class="p-4 flex justify-center w-full shadow-[0_-1px_0_rgba(255,255,255,0.03)]">
            <button class="text-[11px] font-bold text-slate-400 hover:text-white transition-colors flex items-center gap-1.5 px-4 py-2 rounded-lg hover:bg-white/5">
                Tampilkan Lebih Banyak <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
            </button>
        </div>

    </div>
</div>

{{-- MODAL WITHDRAWAL --}}
<div id="wd_modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md hidden transition-all" style="opacity:1;">
    <div class="bg-[#0f0f0f] rounded-[1.5rem] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.08),_0_25px_50px_-12px_rgba(0,0,0,0.8)] w-full max-w-sm overflow-hidden" @click.stop>
        
        <div class="px-6 py-5 shadow-[0_1px_0_rgba(255,255,255,0.06)] flex justify-between items-center">
            <div>
                <h3 class="font-black text-white text-base">Tarik Pendapatan</h3>
                <p class="text-[11px] text-slate-400">Pencairan langsung ke rekeningmu</p>
            </div>
            <button onclick="document.getElementById('wd_modal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-colors focus:outline-none">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('kreator.finance.withdraw') }}" method="POST" class="p-5 space-y-6">
            @csrf
            {{-- Input Nominal --}}
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-2">Nominal Penarikan (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-slate-500">Rp</span>
                    <input type="number" name="amount" class="w-full bg-black border-none text-[1.5rem] font-extrabold shadow-[inset_0_0_0_1px_rgba(255,255,255,0.1)] text-white rounded-2xl pl-12 pr-5 py-4 outline-none transition-shadow duration-200 focus:shadow-[inset_0_0_0_1.5px_rgba(139,92,246,0.6),_0_0_0_4px_rgba(139,92,246,0.1)]" value="{{ auth()->user()->balance }}" max="{{ auth()->user()->balance }}" min="50000" placeholder="0">
                </div>
                <div class="flex items-center justify-between mt-2 px-1 text-[11px]">
                    <span class="text-slate-500 font-medium">Saldo Maksimal</span>
                    <span class="font-bold text-violet-400">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Bank Info --}}
            <div class="p-3.5 rounded-xl flex items-center justify-between gap-3 bg-white/[0.03] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)]">
                <div class="w-9 h-9 rounded-full bg-[#111] flex items-center justify-center flex-shrink-0 shadow-[inset_0_0_0_1px_rgba(255,255,255,0.05)]">
                    <i data-lucide="building" class="w-4 h-4 text-slate-300"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Tujuan Transfer</p>
                    <p class="text-xs font-black text-white truncate">
                        @if(auth()->user()->bank_name && auth()->user()->bank_account)
                            {{ auth()->user()->bank_name }} — {{ auth()->user()->bank_account }}
                        @else
                            <span class="text-rose-400">Belum Diatur! Ubah di kartu rekening.</span>
                        @endif
                    </p>
                </div>
                @if(auth()->user()->bank_name)
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                @else
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500"></i>
                @endif
            </div>

            {{-- Action Button --}}
            <button type="submit" class="w-full py-4 text-white text-sm font-black rounded-xl transition-all shadow-[0_8px_20px_rgba(139,92,246,0.3)] hover:shadow-[0_8px_25px_rgba(139,92,246,0.45)] hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2 bg-gradient-to-br from-violet-600 to-fuchsia-500">
                <i data-lucide="lock" class="w-4 h-4"></i> Konfirmasi Penarikan
            </button>
        </form>
    </div>
</div>

{{-- MODAL UBAH REKENING --}}
<div id="edit_bank_modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md hidden transition-all">
    <div class="bg-[#0f0f0f] rounded-[1.5rem] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.08),_0_25px_50px_-12px_rgba(0,0,0,0.8)] w-full max-w-sm overflow-hidden">
        <div class="px-6 py-5 shadow-[0_1px_0_rgba(255,255,255,0.06)] flex justify-between items-center">
            <div>
                <h3 class="font-black text-white text-base">Ubah Rekening / E-Wallet</h3>
                <p class="text-[11px] text-slate-400">Pastikan rekening atas nama Anda</p>
            </div>
            <button type="button" onclick="document.getElementById('edit_bank_modal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-colors focus:outline-none">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        
        <form action="{{ route('kreator.finance.bank.update') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1.5">Pilih Bank / E-Wallet</label>
                    <select name="bank_name" id="inp_bank_name" required class="w-full bg-black border-none text-sm font-semibold shadow-[inset_0_0_0_1px_rgba(255,255,255,0.1)] text-white rounded-xl px-4 py-3 outline-none focus:shadow-[inset_0_0_0_1.5px_rgba(139,92,246,0.6)] appearance-none cursor-pointer">
                        <option value="BCA" {{ auth()->user()->bank_name == 'BCA' ? 'selected' : '' }}>Bank BCA</option>
                        <option value="BRI" {{ auth()->user()->bank_name == 'BRI' ? 'selected' : '' }}>Bank BRI</option>
                        <option value="BNI" {{ auth()->user()->bank_name == 'BNI' ? 'selected' : '' }}>Bank BNI</option>
                        <option value="Mandiri" {{ auth()->user()->bank_name == 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                        <option value="DANA" {{ auth()->user()->bank_name == 'DANA' ? 'selected' : '' }}>DANA (E-Wallet)</option>
                        <option value="GoPay" {{ auth()->user()->bank_name == 'GoPay' ? 'selected' : '' }}>GoPay (E-Wallet)</option>
                        <option value="OVO" {{ auth()->user()->bank_name == 'OVO' ? 'selected' : '' }}>OVO (E-Wallet)</option>
                        <option value="ShopeePay" {{ auth()->user()->bank_name == 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1.5">Nomor Rekening / HP</label>
                    <input type="number" name="bank_account" id="inp_bank_acc" value="{{ auth()->user()->bank_account }}" placeholder="Misal: 081234..." required class="w-full bg-black border-none text-sm font-semibold shadow-[inset_0_0_0_1px_rgba(255,255,255,0.1)] text-white rounded-xl px-4 py-3 outline-none focus:shadow-[inset_0_0_0_1.5px_rgba(139,92,246,0.6)]">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1.5">Nama Pemilik Akun</label>
                    <input type="text" id="inp_acc_name" value="{{ strtoupper(auth()->user()->name) }}" readonly class="w-full bg-black/50 border-none text-sm font-semibold shadow-[inset_0_0_0_1px_rgba(255,255,255,0.05)] text-slate-500 rounded-xl px-4 py-3 outline-none cursor-not-allowed">
                    <p class="text-[9px] text-slate-500 mt-2">Nama pemilik akun didapat dari pencocokan KTP identitas dan tidak bisa diubah.</p>
                </div>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-violet-600 to-fuchsia-600 hover:from-violet-500 hover:to-fuchsia-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-[0_4px_12px_rgba(139,92,246,0.3)] transition-all">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

</div>
@endsection
