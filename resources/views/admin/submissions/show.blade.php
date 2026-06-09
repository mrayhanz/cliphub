@extends('layouts.admin')

@section('title', 'Detail Submission')
@section('page_title', 'Detail Submission')
@section('page_subtitle', 'Informasi lengkap submission dari kreator')

@section('content')
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.submissions.index') }}" class="btn-secondary px-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-5">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-5">
            <!-- Submission Info -->
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Informasi Submission</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Campaign</label>
                        <p class="text-sm font-semibold text-white mt-1">{{ $submission->campaign->title }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Brand: {{ $submission->campaign->user->name }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Platform</label>
                            <p class="text-sm font-semibold text-white mt-1">{{ $submission->platform }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Views Claimed</label>
                            <p class="text-sm font-semibold text-white mt-1">{{ number_format($submission->views_claimed, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Link Video</label>
                        <a href="{{ $submission->video_url }}" target="_blank" class="text-sm text-brand hover:text-brand-light mt-1 break-all block">
                            {{ $submission->video_url }}
                        </a>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Estimasi Reward</label>
                        <p class="text-2xl font-bold text-emerald-400 mt-1">Rp {{ number_format($submission->estimated_reward, 0, ',', '.') }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Bukti Analytics</label>
                        <div class="mt-2 bg-neutral-800 rounded-xl overflow-hidden">
                            @if($submission->analytics_proof_path)
                            <img src="{{ route('admin.submissions.proof', $submission) }}" alt="Analytics Proof" class="w-full h-auto">
                            @else
                            <p class="text-sm text-slate-500 p-4">Tidak ada bukti analytics</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-5">
            <!-- Kreator Info -->
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white mb-4">Kreator</h3>
                <div class="flex items-center gap-3 mb-4">
                    @if($submission->user->avatar)
                    <img src="{{ $submission->user->avatar }}" alt="{{ $submission->user->name }}" class="w-12 h-12 rounded-xl">
                    @else
                    <div class="w-12 h-12 rounded-xl bg-brand/20 text-brand flex items-center justify-center text-lg font-bold">
                        {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-bold text-white">{{ $submission->user->name }}</p>
                        <p class="text-xs text-slate-500">{{ $submission->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white mb-4">Status</h3>
                
                @php
                $statusConfig = match($submission->status){
                    'pending_brand' => ['label' => 'Pending Brand', 'color' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'],
                    'approved_by_brand' => ['label' => 'Menunggu Review Admin', 'color' => 'bg-amber-500/10 text-amber-400 border-amber-500/20'],
                    'approved_by_admin' => ['label' => 'Disetujui Admin', 'color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'],
                    'rejected_by_brand' => ['label' => 'Ditolak Brand', 'color' => 'bg-slate-500/10 text-slate-400 border-slate-500/20'],
                    'rejected_by_admin' => ['label' => 'Ditolak Admin', 'color' => 'bg-red-500/10 text-red-400 border-red-500/20'],
                    default => ['label' => 'Unknown', 'color' => 'bg-slate-500/10 text-slate-400 border-slate-500/20']
                };
                @endphp

                <div class="flex items-center justify-center">
                    <span class="text-sm font-semibold px-4 py-2 rounded-xl border {{ $statusConfig['color'] }}">
                        {{ $statusConfig['label'] }}
                    </span>
                </div>

                @if($submission->rejection_reason)
                <div class="mt-4 p-3 bg-red-500/10 border border-red-500/20 rounded-xl">
                    <p class="text-xs font-medium text-red-400 mb-1">Alasan Penolakan:</p>
                    <p class="text-xs text-slate-300">{{ $submission->rejection_reason }}</p>
                </div>
                @endif

                <div class="mt-4 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Dikirim:</span>
                        <span class="text-white">{{ $submission->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($submission->brand_approved_at)
                    <div class="flex justify-between">
                        <span class="text-slate-500">Approved Brand:</span>
                        <span class="text-white">{{ $submission->brand_approved_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($submission->admin_approved_at)
                    <div class="flex justify-between">
                        <span class="text-slate-500">Approved Admin:</span>
                        <span class="text-white">{{ $submission->admin_approved_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if($submission->status === 'approved_by_brand')
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white mb-4">Aksi</h3>
                
                <form action="{{ route('admin.submissions.approve', $submission) }}" method="POST" class="mb-3" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui submission ini? Saldo brand akan dikurangi dan reward akan ditambahkan ke saldo kreator.')">
                    @csrf
                    <button type="submit" class="w-full btn-primary bg-emerald-500 hover:bg-emerald-600 border-emerald-500">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        Approve Submission
                    </button>
                </form>

                <button onclick="showRejectModal()" class="w-full btn-secondary bg-red-500/10 text-red-400 border-red-500/20 hover:bg-red-500/20">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Reject Submission
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-neutral-900 border border-neutral-800 rounded-2xl max-w-lg w-full">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800">
            <h3 class="text-lg font-bold text-white">Tolak Submission</h3>
            <button onclick="closeRejectModal()" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.submissions.reject', $submission) }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-2">Alasan Penolakan <span class="text-red-400">*</span></label>
                <textarea name="rejection_reason" required rows="4" 
                    class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all resize-none"
                    placeholder="Jelaskan alasan penolakan"></textarea>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="submit" class="flex-1 btn-primary bg-red-500 hover:bg-red-600 border-red-500">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Tolak Submission
                </button>
                <button type="button" onclick="closeRejectModal()" class="flex-1 btn-secondary">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
    }
});

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush
@endsection
