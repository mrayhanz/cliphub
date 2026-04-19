@extends('layouts.brand')

@section('title', 'Keuangan & Deposit')

@push('styles')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Midtrans Snap.js -->
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="w-full pb-12 pt-2" x-data="topUpManager()">

    {{-- MAIN GRID: Left (Balance + Escrow) | Right (Transaction History) --}}
    <div class="grid grid-cols-1 lg:grid-cols-[340px_1fr] gap-6 items-start">

        {{-- ========================
             LEFT COLUMN
             ======================== --}}
        <div class="flex flex-col gap-5">

            {{-- SALDO DEPOSIT CARD --}}
            <div class="bg-[#111111] border border-violet-500/30 rounded-3xl relative overflow-hidden shadow-[inset_0_0_20px_rgba(139,92,246,0.05),0_10px_40px_rgba(91,33,182,0.15)] p-7">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-violet-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-fuchsia-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <span class="text-xs font-bold text-violet-300 uppercase tracking-widest bg-violet-500/10 border border-violet-500/20 px-2.5 py-1 rounded shadow-sm">Saldo Deposit</span>
                        <div class="w-11 h-11 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                            <span class="text-2xl drop-shadow-[0_2px_4px_rgba(139,92,246,0.5)]">💰</span>
                        </div>
                    </div>

                    <h2 class="text-3xl lg:text-4xl font-black text-white tracking-tight mb-2">
                        <span class="text-xl text-slate-400 font-bold mr-0.5">Rp</span>{{ number_format((float)$balance, 0, ',', '.') }}
                    </h2>
                    <p class="text-[10px] lg:text-xs text-slate-400/90 font-medium leading-relaxed mb-8">
                        Tersedia untuk pembayaran kreator
                    </p>

                    <button @click="isModalOpen = true" class="w-full bg-gradient-to-br from-violet-600 to-fuchsia-600 text-white px-6 py-3.5 rounded-2xl text-sm font-extrabold flex items-center justify-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(124,58,237,0.25)] hover:shadow-[0_8px_30px_rgba(124,58,237,0.4)] hover:-translate-y-0.5 cursor-pointer border-none outline-none">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Top-up
                    </button>
                </div>
            </div>

            {{-- DANA ESCROW CARD --}}
            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-5 lg:p-6 overflow-hidden relative">
                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-xl drop-shadow-[0_2px_4px_rgba(245,158,11,0.4)]">⏳</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-amber-500/80 uppercase tracking-widest mb-1.5">Dana Ditahan (Escrow)</h4>
                        <p class="text-xl lg:text-2xl font-black text-white mb-2 pb-1">Rp {{ number_format((float)$escrow, 0, ',', '.') }}</p>
                        <p class="text-[10px] lg:text-[11px] text-slate-400 leading-relaxed font-medium">Dana dialokasikan sementara untuk UGC yang masih menunggu direview.</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========================
             RIGHT COLUMN: HISTORY
             ======================== --}}
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-5 lg:p-6 flex flex-col min-h-0 overflow-hidden h-full">

            {{-- Tab Header --}}
            <div class="flex items-center justify-between pb-4 mb-5 border-b border-white/5">
                <h3 class="text-sm lg:text-base font-black text-white">Riwayat Transaksi</h3>
                <div class="flex gap-2">
                    <button class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all bg-violet-500/10 text-violet-400 border border-violet-500/25">Semua</button>
                </div>
            </div>

            {{-- Transaction List --}}
            <div class="flex flex-col gap-3 flex-1 overflow-y-auto">
                @if($deposits->count() > 0)
                    @foreach($deposits as $t)
                    <div class="bg-white/[0.02] hover:bg-white/[0.04] border border-white/[0.05] rounded-2xl p-4 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-3.5 min-w-0 pr-4">
                            <div class="w-10 h-10 rounded-xl bg-white/[0.04] border border-white/[0.08] flex items-center justify-center flex-shrink-0">
                                <span class="text-lg">
                                    {{ $t->status === 'success' ? '✅' : ($t->status === 'pending' ? '⏳' : '❌') }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs lg:text-sm font-bold text-white truncate">
                                    Top-Up Deposit
                                </p>
                                <p class="text-[10px] lg:text-xs {{ $t->status === 'success' ? 'text-emerald-400' : ($t->status === 'pending' ? 'text-amber-400' : 'text-rose-400') }} font-bold mt-0.5 truncate">
                                    {{ strtoupper($t->status) }} • {{ $t->order_id }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs lg:text-sm font-black text-emerald-400">+ Rp {{ number_format($t->amount, 0, ',', '.') }}</p>
                            <p class="text-[9px] lg:text-[10px] text-slate-500 font-semibold mt-1">{{ $t->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="py-10 text-center">
                        <p class="text-sm text-slate-500 font-medium">Belum ada riwayat transaksi top-up deposit.</p>
                    </div>
                @endif
            </div>

        </div>

    </div>

    {{-- MODAL TOP-UP --}}
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center" style="display: none;" x-show="isModalOpen" x-transition.opacity>
        <div class="bg-[#0a0a0a] border border-zinc-800 rounded-3xl w-full max-w-[400px] p-8 relative shadow-[0_20px_50px_rgba(0,0,0,0.5)] mx-4" @click.away="isModalOpen = false">
            <button @click="isModalOpen = false" class="absolute top-5 right-5 text-slate-500 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            
            <h3 class="text-xl font-black text-white mb-1 mt-2">Top-up Deposit</h3>
            <p class="text-[11px] text-slate-400 mb-6 font-medium">Pilih atau masukkan nominal uang yang ingin diisi ke saldo Anda.</p>
            
            <div class="grid grid-cols-2 gap-2 mb-6">
                <button type="button" @click="amount = 50000" class="bg-white/[0.03] hover:bg-violet-500/10 border border-white/10 hover:border-violet-500/30 rounded-xl p-3 text-slate-400 hover:text-white font-bold text-xs sm:text-sm transition-all" :class="amount == 50000 ? 'bg-violet-500/20 border-violet-500/50 text-white' : ''">Rp 50.000</button>
                <button type="button" @click="amount = 100000" class="bg-white/[0.03] hover:bg-violet-500/10 border border-white/10 hover:border-violet-500/30 rounded-xl p-3 text-slate-400 hover:text-white font-bold text-xs sm:text-sm transition-all" :class="amount == 100000 ? 'bg-violet-500/20 border-violet-500/50 text-white' : ''">Rp 100.000</button>
                <button type="button" @click="amount = 500000" class="bg-white/[0.03] hover:bg-violet-500/10 border border-white/10 hover:border-violet-500/30 rounded-xl p-3 text-slate-400 hover:text-white font-bold text-xs sm:text-sm transition-all" :class="amount == 500000 ? 'bg-violet-500/20 border-violet-500/50 text-white' : ''">Rp 500.000</button>
                <button type="button" @click="amount = 1000000" class="bg-white/[0.03] hover:bg-violet-500/10 border border-white/10 hover:border-violet-500/30 rounded-xl p-3 text-slate-400 hover:text-white font-bold text-xs sm:text-sm transition-all" :class="amount == 1000000 ? 'bg-violet-500/20 border-violet-500/50 text-white' : ''">Rp 1.000.000</button>
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Atau Nominal Lainnya</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">Rp</span>
                    <input type="number" x-model="amount" class="w-full bg-black border border-zinc-800 rounded-xl py-3 pl-12 pr-4 text-white text-sm font-bold focus:outline-none focus:border-violet-500/50 transition-colors" placeholder="Minimum 10000" min="10000">
                </div>
            </div>

            <button @click="processPayment()" :disabled="isLoading || amount < 10000" class="w-full bg-gradient-to-br from-violet-600 to-fuchsia-600 text-white px-6 py-3.5 rounded-2xl text-sm font-extrabold flex items-center justify-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(124,58,237,0.25)] hover:shadow-[0_8px_30px_rgba(124,58,237,0.4)] hover:-translate-y-0.5 cursor-pointer border-none outline-none" :class="(isLoading || amount < 10000) ? 'opacity-50 cursor-not-allowed hover:translate-y-0 hover:shadow-none' : ''">
                <span x-text="isLoading ? 'Memproses...' : 'Lanjutkan Pembayaran'"></span>
            </button>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('topUpManager', () => ({
        isModalOpen: false,
        amount: 50000,
        isLoading: false,

        processPayment() {
            if(this.amount < 10000) {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Minimal topup adalah Rp 10.000', background: '#111', color: '#fff' });
                return;
            }

            this.isLoading = true;

            // Minta Snap Token ke Backend Kita
            fetch("{{ route('brand.finance.topup') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ amount: this.amount })
            })
            .then(response => response.json())
            .then(data => {
                this.isLoading = false;
                
                if(data.status === 'success') {
                    this.isModalOpen = false;
                    
                    // Panggil UI Midtrans Snap Pop-up
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result){
                            // Hit backend manually because local server can't receive Midtrans webhook
                            fetch("{{ route('brand.finance.topup.callback') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    order_id: result.order_id,
                                    payment_type: result.payment_type
                                })
                            }).then(() => {
                                Swal.fire({
                                    icon: 'success', title: 'Pembayaran Berhasil!',
                                    text: 'Saldo deposit anda telah diperbarui.', background: '#111', color: '#fff'
                                }).then(() => { window.location.reload(); });
                            });
                        },
                        onPending: function(result){
                            Swal.fire({
                                icon: 'info', title: 'Menunggu Pembayaran',
                                text: 'Silakan selesaikan pembayaran sesuai instruksi.', background: '#111', color: '#fff'
                            }).then(() => { window.location.reload(); });
                        },
                        onError: function(result){
                            Swal.fire({ icon: 'error', title: 'Pembayaran Gagal', text: 'Terjadi kesalahan pada saat proses pembayaran.', background: '#111', color: '#fff' });
                        },
                        onClose: function(){
                            Swal.fire({ icon: 'warning', title: 'Transaksi Dibatalkan', text: 'Anda menutup popup sebelum menyelesaikan pembayaran.', background: '#111', color: '#fff' });
                        }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal memproses transaksi.', background: '#111', color: '#fff' });
                }
            })
            .catch(error => {
                this.isLoading = false;
                Swal.fire({ icon: 'error', title: 'Kesalahan Sistem', text: 'Periksa koneksi internet Anda.', background: '#111', color: '#fff' });
            });
        }
    }));
});
</script>
@endpush
