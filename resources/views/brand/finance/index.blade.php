@extends('layouts.brand')

@section('title', 'Keuangan & Deposit')
@section('page_title', 'Keuangan & Deposit')

@push('styles')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Midtrans Snap.js -->
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="w-full pb-8 space-y-6" x-data="topUpManager()">

    <!-- Header Section -->
    <div class="hero-card p-5 lg:p-7 animate-fade-in-up">
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex-1 min-w-0">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold mb-3" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.25); color: #34d399;">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    Keuangan & Deposit
                </div>
                <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight leading-snug">
                    Dompet & Deposit Brand
                </h1>
                <p class="text-emerald-200/70 text-sm lg:text-base mt-2 leading-relaxed max-w-xl">
                    Pantau saldo deposit Anda, alokasikan dana ditahan (escrow) untuk campaign aktif, dan kelola riwayat pengisian dana secara real-time.
                </p>
            </div>
        </div>
    </div>

    {{-- MAIN GRID: Left (Balance + Escrow + Campaign List) | Right (Transaction History) --}}
    <div class="grid grid-cols-1 lg:grid-cols-[340px_1fr] gap-6 lg:items-stretch animate-fade-in-up delay-100">

        {{-- ========================
             LEFT COLUMN
             ======================== --}}
        <div class="flex flex-col gap-5">

            {{-- SALDO DEPOSIT CARD --}}
            <div class="stat-card p-6 border-emerald-500/20 relative overflow-hidden bg-gradient-to-br from-white/[0.01] to-emerald-950/[0.04]">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-green-500/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-8">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[9px] font-extrabold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase tracking-widest">
                            Saldo Deposit
                        </span>
                        <div class="icon-box-green">
                            <i data-lucide="wallet" class="w-5 h-5 text-emerald-400"></i>
                        </div>
                    </div>

                    <h2 class="text-3xl lg:text-4xl font-black text-white tracking-tight mb-2">
                        <span class="text-xl text-slate-400 font-bold mr-0.5">Rp</span>{{ number_format((float)$balance, 0, ',', '.') }}
                    </h2>
                    <p class="text-[10px] lg:text-xs text-slate-400/90 font-medium leading-relaxed mb-8">
                        Tersedia untuk pembayaran kreator kampanye Anda.
                    </p>

                    <button @click="isModalOpen = true" class="btn-primary w-full cursor-pointer border-none outline-none">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Top-up Saldo
                    </button>
                </div>
            </div>

            {{-- DANA ESCROW CARD --}}
            <div class="stat-card p-6 border-amber-500/20 relative overflow-hidden bg-gradient-to-br from-white/[0.01] to-amber-950/[0.04]">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="flex gap-4 items-start relative z-10">
                    <div class="icon-box-amber">
                        <i data-lucide="clock" class="w-5 h-5 text-amber-400"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-amber-500/80 uppercase tracking-widest mb-1.5">Dana Ditahan (Escrow)</h4>
                        <p class="text-xl lg:text-2xl font-black text-white mb-2">Rp {{ number_format((float)$escrow, 0, ',', '.') }}</p>
                        <p class="text-[10px] lg:text-[11px] text-slate-400 leading-relaxed font-medium">Dana dialokasikan sementara untuk UGC yang masih menunggu direview.</p>
                    </div>
                </div>
            </div>

            {{-- ESCROW CAMPAIGN ALLOCATION LIST --}}
            <div class="glass-card p-5 relative overflow-hidden flex flex-col">
                <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(16,185,129,0.04) 0%, transparent 70%);"></div>
                
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-1.5">
                    <i data-lucide="layers" class="w-3.5 h-3.5 text-emerald-400"></i> Alokasi Escrow Kampanye
                </h4>

                <div class="flex flex-col gap-3 max-h-[400px] overflow-y-auto pr-1">
                    @forelse($activeCampaigns as $c)
                    <div class="bg-white/[0.01] border border-white/[0.04] rounded-xl p-3 flex flex-col gap-2 hover:border-emerald-500/10 transition-all">
                        <div class="flex items-start justify-between gap-2">
                            <h5 class="text-xs font-bold text-white line-clamp-1" title="{{ $c->title }}">
                                {{ $c->title }}
                            </h5>
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[8px] font-black bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase tracking-widest">
                                Active
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] text-slate-400">
                            <span class="font-medium">Slot: {{ $c->slots }} Kreator</span>
                            <span class="font-bold text-emerald-400">Rp {{ number_format($c->budget, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="py-6 text-center">
                        <p class="text-[11px] text-slate-500 font-semibold">Tidak ada alokasi dana escrow kampanye aktif.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ========================
             RIGHT COLUMN: HISTORY
             ======================== --}}
        <div class="relative min-h-[600px] h-full w-full">
            <div class="lg:absolute lg:inset-0 glass-card p-5 lg:p-6 flex flex-col min-h-0 overflow-hidden h-full w-full">

                {{-- Tab Header --}}
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-white/5">
                    <h3 class="text-sm lg:text-base font-black text-white uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="history" class="w-4.5 h-4.5 text-emerald-400"></i> Riwayat Transaksi
                    </h3>
                </div>

                @include('brand.partials.filter-search', [
                    'action' => route('brand.finance'),
                    'filters' => $filters,
                    'currentStatus' => $status ?? '',
                    'search' => $search ?? '',
                    'searchPlaceholder' => 'Cari Order ID atau Nominal...',
                    'sortOptions' => $sortOptions ?? [],
                    'currentSort' => $currentSort ?? 'newest',
                    'compact' => true
                ])

                {{-- Transaction List --}}
                <div class="flex flex-col gap-3 flex-1 overflow-y-auto pr-1">
                    @if($deposits->count() > 0)
                        @foreach($deposits as $t)
                        <div class="bg-white/[0.01] hover:bg-white/[0.03] border border-white/[0.04] rounded-xl p-4 transition-all duration-200 flex items-center justify-between
                            @if($t->status === 'success')
                                hover:border-emerald-500/10
                            @elseif($t->status === 'pending')
                                hover:border-amber-500/10
                            @elseif($t->status === 'expired')
                                hover:border-white/10
                            @else
                                hover:border-rose-500/10
                            @endif">
                            <div class="flex items-center gap-3.5 min-w-0 pr-4">
                                @if($t->status === 'success')
                                    <div class="icon-box-green text-lg flex items-center justify-center"><i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i></div>
                                @elseif($t->status === 'pending')
                                    <div class="icon-box-amber text-lg flex items-center justify-center"><i data-lucide="clock" class="w-4 h-4 text-amber-400"></i></div>
                                @elseif($t->status === 'expired')
                                    <div class="icon-box-slate text-lg flex items-center justify-center"><i data-lucide="alert-circle" class="w-4 h-4 text-slate-400"></i></div>
                                @else
                                    <div class="icon-box-red text-lg flex items-center justify-center"><i data-lucide="x-circle" class="w-4 h-4 text-rose-400"></i></div>
                                @endif

                                <div class="min-w-0">
                                    <p class="text-xs lg:text-sm font-bold text-white truncate">
                                        Top-Up Deposit
                                    </p>
                                    <p class="text-[10px] lg:text-xs font-bold mt-0.5 truncate
                                        @if($t->status === 'success')
                                            text-emerald-400
                                        @elseif($t->status === 'pending')
                                            text-amber-400
                                        @elseif($t->status === 'expired')
                                            text-slate-400
                                        @else
                                            text-rose-400
                                        @endif">
                                        {{ strtoupper($t->status) }} • {{ $t->order_id }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 flex flex-col items-end justify-center">
                                @if($t->status === 'pending' && $t->snap_token)
                                    <button @click="payPending('{{ $t->snap_token }}', '{{ $t->order_id }}')" class="btn-primary text-[10px] py-1.5 px-3 rounded-lg flex items-center gap-1 cursor-pointer mb-1 border-none outline-none">
                                        <i data-lucide="wallet" class="w-3 h-3"></i> Bayar Sekarang
                                    </button>
                                @else
                                    <p class="text-xs lg:text-sm font-black 
                                        @if($t->status === 'success')
                                            text-emerald-400
                                        @elseif($t->status === 'pending')
                                            text-amber-400
                                        @elseif($t->status === 'expired')
                                            text-slate-500 line-through
                                        @else
                                            text-rose-500 line-through
                                        @endif">
                                        + Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </p>
                                @endif
                                <p class="text-[9px] lg:text-[10px] text-slate-500 font-semibold mt-1">{{ $t->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="py-12 text-center">
                            <p class="text-xs lg:text-sm text-slate-500 font-semibold">Belum ada riwayat transaksi top-up deposit.</p>
                        </div>
                    @endif
                </div>

                @if($deposits->hasPages())
                    <div class="mt-5 pt-4 border-t border-white/5 bg-black/10 -mx-5 -mb-5 p-5">
                        {{ $deposits->appends(request()->query())->links('brand.partials.pagination') }}
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- MODAL TOP-UP --}}
    <template x-teleport="body">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[999] flex items-center justify-center animate-fade-in" style="display: none;" x-show="isModalOpen" x-transition.opacity>
            <div class="bg-[#111111] border border-white/10 rounded-2xl w-full max-w-[400px] p-8 relative shadow-[0_20px_50px_rgba(0,0,0,0.7)] mx-4" @click.away="isModalOpen = false">
                <button @click="isModalOpen = false" class="absolute top-5 right-5 text-slate-500 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
                
                <h3 class="text-xl font-black text-white mb-1 mt-2">Top-up Deposit</h3>
                <p class="text-[11px] text-slate-400 mb-6 font-medium">Pilih atau masukkan nominal uang yang ingin diisi ke saldo Anda.</p>
                
                <div class="grid grid-cols-2 gap-2 mb-6">
                    <button type="button" @click="amount = 50000" class="bg-white/5 hover:bg-emerald-500/10 border border-white/10 hover:border-emerald-500/30 rounded-xl p-3 text-slate-400 hover:text-white font-bold text-xs sm:text-sm transition-all" :class="amount == 50000 ? 'bg-emerald-500/20 border-emerald-500/50 text-white border-emerald-500/30' : ''">Rp 50.000</button>
                    <button type="button" @click="amount = 100000" class="bg-white/5 hover:bg-emerald-500/10 border border-white/10 hover:border-emerald-500/30 rounded-xl p-3 text-slate-400 hover:text-white font-bold text-xs sm:text-sm transition-all" :class="amount == 100000 ? 'bg-emerald-500/20 border-emerald-500/50 text-white border-emerald-500/30' : ''">Rp 100.000</button>
                    <button type="button" @click="amount = 500000" class="bg-white/5 hover:bg-emerald-500/10 border border-white/10 hover:border-emerald-500/30 rounded-xl p-3 text-slate-400 hover:text-white font-bold text-xs sm:text-sm transition-all" :class="amount == 500000 ? 'bg-emerald-500/20 border-emerald-500/50 text-white border-emerald-500/30' : ''">Rp 500.000</button>
                    <button type="button" @click="amount = 1000000" class="bg-white/5 hover:bg-emerald-500/10 border border-white/10 hover:border-emerald-500/30 rounded-xl p-3 text-slate-400 hover:text-white font-bold text-xs sm:text-sm transition-all" :class="amount == 1000000 ? 'bg-emerald-500/20 border-emerald-500/50 text-white border-emerald-500/30' : ''">Rp 1.000.000</button>
                </div>

                <div class="mb-8">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Atau Nominal Lainnya</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-sm">Rp</span>
                        <input type="number" x-model="amount" class="w-full bg-black/40 border border-white/10 rounded-xl py-3.5 pl-12 pr-4 text-white text-sm font-bold focus:outline-none focus:border-emerald-500/50 transition-colors" placeholder="Minimum 10000" min="10000">
                    </div>
                </div>

                <button @click="processPayment()" :disabled="isLoading || amount < 10000" class="btn-primary w-full py-3.5 text-sm cursor-pointer border-none outline-none" :class="(isLoading || amount < 10000) ? 'opacity-50 cursor-not-allowed hover:translate-y-0 hover:shadow-none' : ''">
                    <span x-text="isLoading ? 'Memproses...' : 'Lanjutkan Pembayaran'"></span>
                </button>
            </div>
        </div>
    </template>

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

            // Request snap token from backend
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
                    this.payPending(data.snap_token, data.order_id);
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal memproses transaksi.', background: '#111', color: '#fff' });
                }
            })
            .catch(error => {
                this.isLoading = false;
                Swal.fire({ icon: 'error', title: 'Kesalahan Sistem', text: 'Periksa koneksi internet Anda.', background: '#111', color: '#fff' });
            });
        },

        payPending(snapToken, orderId) {
            window.snap.pay(snapToken, {
                onSuccess: function(result){
                    // Hit backend manually because local server can't receive Midtrans webhook
                    fetch("{{ route('brand.finance.topup.callback') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: orderId,
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
        }
    }));
});
</script>
@endpush
