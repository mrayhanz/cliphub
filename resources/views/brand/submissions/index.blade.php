@extends('layouts.brand')

@section('title', 'Review Submission')

@section('content')
<div class="w-full pb-12 pt-2 space-y-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-medium text-emerald-400">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-medium text-red-400">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight mb-2">Review Submission</h1>
            <p class="text-xs text-slate-400 max-w-xl leading-relaxed">
                Validasi link konten, bukti analytics, dan views yang diklaim sebelum reward kreator disetujui.
            </p>
        </div>
        <a href="{{ route('brand.campaigns') }}" class="btn-ghost w-fit px-4">
            <i data-lucide="megaphone" class="w-4 h-4"></i> Lihat Campaign
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-amber/10 border border-amber/20 items-center justify-center mb-3">
                <i data-lucide="clock" class="w-4 h-4 text-amber-400"></i>
            </div>
            <p class="text-xl font-black text-white">{{ number_format($stats['pending']) }}</p>
            <p class="text-xs text-slate-500">Menunggu Review</p>
        </div>
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-emerald/10 border border-emerald/20 items-center justify-center mb-3">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
            </div>
            <p class="text-xl font-black text-white">{{ number_format($stats['approved']) }}</p>
            <p class="text-xs text-slate-500">Disetujui</p>
        </div>
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-red/10 border border-red/20 items-center justify-center mb-3">
                <i data-lucide="x-circle" class="w-4 h-4 text-red-400"></i>
            </div>
            <p class="text-xl font-black text-white">{{ number_format($stats['rejected']) }}</p>
            <p class="text-xs text-slate-500">Ditolak</p>
        </div>
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-brand/10 border border-brand/20 items-center justify-center mb-3">
                <i data-lucide="wallet" class="w-4 h-4 text-brand"></i>
            </div>
            <p class="text-xl font-black text-white">Rp {{ number_format($stats['total_reward'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500">Estimasi Reward</p>
        </div>
    </div>

    <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.5rem] overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-5 border-b border-white/5">
            <div>
                <h3 class="text-sm lg:text-base font-black text-white">Daftar Submission</h3>
                <p class="text-xs text-slate-500 mt-1">Approve untuk meneruskan proses reward, reject dengan alasan jika bukti tidak valid.</p>
            </div>

            <form method="GET" action="{{ route('brand.submissions.index') }}" class="flex gap-1.5 p-1 bg-white/[0.02] border border-white/5 rounded-xl overflow-x-auto">
                <button type="submit" name="status" value="all" class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold {{ request('status', 'all') === 'all' ? 'bg-brand/10 text-brand border border-brand/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }} whitespace-nowrap transition-all">
                    Semua
                </button>
                <button type="submit" name="status" value="pending_brand" class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold {{ request('status') === 'pending_brand' ? 'bg-brand/10 text-brand border border-brand/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }} whitespace-nowrap transition-all">
                    Menunggu
                </button>
                <button type="submit" name="status" value="approved_by_brand" class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold {{ request('status') === 'approved_by_brand' ? 'bg-brand/10 text-brand border border-brand/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }} whitespace-nowrap transition-all">
                    Menunggu Admin
                </button>
                <button type="submit" name="status" value="approved_by_admin" class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold {{ request('status') === 'approved_by_admin' ? 'bg-brand/10 text-brand border border-brand/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }} whitespace-nowrap transition-all">
                    Disetujui
                </button>
                <button type="submit" name="status" value="rejected_by_brand" class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold {{ request('status') === 'rejected_by_brand' ? 'bg-brand/10 text-brand border border-brand/20' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }} whitespace-nowrap transition-all">
                    Ditolak
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kreator</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Campaign</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Platform</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Views</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Reward</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dikirim</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.05]">
                    @forelse($submissions as $s)
                    @php
                    $statusClass = match($s->status){
                        'pending_brand' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                        'approved_by_brand' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                        'approved_by_admin' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                        'rejected_by_brand' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                        'rejected_by_admin' => 'bg-red-500/10 text-red-400 border-red-500/20',
                        default => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                    };
                    $statusLabel = match($s->status){
                        'pending_brand' => 'Menunggu Review',
                        'approved_by_brand' => 'Menunggu Admin',
                        'approved_by_admin' => 'Disetujui',
                        'rejected_by_brand' => 'Ditolak',
                        'rejected_by_admin' => 'Ditolak Admin',
                        default => 'Unknown',
                    };
                    
                    // Check if approving this submission would exceed campaign budget
                    $campaign = $s->campaign;
                    $remainingBudget = $campaign->budget - ($campaign->budget_spent ?? 0);
                    $wouldExceedBudget = $s->status === 'pending_brand' && $s->estimated_reward > $remainingBudget;
                    @endphp
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($s->user->avatar)
                                <img src="{{ $s->user->avatar }}" alt="{{ $s->user->name }}" class="w-8 h-8 rounded-full flex-shrink-0">
                                @else
                                <div class="w-8 h-8 rounded-full bg-brand/20 text-brand flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($s->user->name, 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-black text-white">{{ $s->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $s->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-xs font-semibold text-slate-300">{{ $s->campaign->title }}</p>
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-400">{{ ucfirst($s->platform) }}</td>
                        <td class="px-5 py-4 text-xs font-bold text-white">{{ number_format($s->views_claimed, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-xs font-black text-emerald-400">
                            Rp {{ number_format($s->estimated_reward, 0, ',', '.') }}
                            @if($wouldExceedBudget)
                            <span class="block text-[10px] text-red-400 mt-1">⚠️ Melebihi budget</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-500">{{ $s->created_at->diffForHumans() }}</td>
                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($s->status === 'pending_brand')
                            <div class="flex items-center gap-1.5">
                                <!-- View Proof Button -->
                                <button onclick="showProofModal({{ $s->id }}, '{{ $s->video_url }}', '{{ $s->analytics_proof_path ? route('brand.submissions.proof', $s) : '' }}')" 
                                    class="h-8 px-3 flex items-center justify-center gap-1.5 rounded-xl bg-white/[0.04] border border-white/[0.08] hover:bg-white/[0.08] text-slate-300 transition-colors" 
                                    title="Lihat bukti analytics">
                                    <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold">Bukti</span>
                                </button>
                                
                                <!-- Reject Button -->
                                <button onclick="showRejectModal({{ $s->id }}, '{{ $s->user->name }}')" 
                                    class="h-8 px-3 flex items-center justify-center gap-1.5 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-colors">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold">Reject</span>
                                </button>
                                
                                <!-- Approve Button -->
                                <form action="{{ route('brand.submissions.approve', $s) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui submission ini? Submission akan diteruskan ke admin untuk persetujuan final.')">
                                    @csrf
                                    <button type="submit" class="h-8 px-3 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white transition-colors flex items-center justify-center gap-1.5">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                        <span class="text-[10px] font-bold">Approve</span>
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="flex items-center gap-1.5">
                                <!-- View Details -->
                                <button onclick="showProofModal({{ $s->id }}, '{{ $s->video_url }}', '{{ $s->analytics_proof_path ? route('brand.submissions.proof', $s) : '' }}')" 
                                    class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors" 
                                    title="Lihat Detail">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </button>
                                
                                @if(in_array($s->status, ['rejected_by_brand', 'rejected_by_admin']) && $s->rejection_reason)
                                <button onclick="showRejectionReason('{{ addslashes($s->rejection_reason) }}', '{{ $s->rejected_by }}')" 
                                    class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-colors" 
                                    title="Lihat Alasan Penolakan">
                                    <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                                </button>
                                @endif
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-400">Belum ada submission</p>
                                    <p class="text-xs text-slate-600 mt-1">Submission akan muncul ketika kreator mengirimkan konten</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($submissions->hasPages())
        <div class="flex items-center justify-between px-5 py-3.5 border-t border-white/5">
            <p class="text-xs text-slate-500">
                Menampilkan {{ $submissions->firstItem() ?? 0 }} - {{ $submissions->lastItem() ?? 0 }} dari {{ number_format($submissions->total()) }} submission
            </p>
            <div class="flex items-center gap-1">
                {{ $submissions->links('pagination::tailwind') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Proof Modal -->
<div id="proofModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-neutral-900 border border-neutral-800 rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800 flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Bukti Analytics & Video</h3>
            <button onclick="closeProofModal()" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-5 overflow-y-auto space-y-4">
            <!-- Video URL -->
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-2">Link Video</label>
                <a id="videoLink" href="#" target="_blank" class="text-sm text-brand hover:text-brand-light break-all"></a>
            </div>
            
            <!-- Analytics Proof Image -->
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-2">Bukti Analytics</label>
                <div id="proofImageContainer" class="bg-neutral-800 rounded-xl overflow-hidden max-w-full">
                    <img id="proofImage" src="" alt="Analytics Proof" class="max-w-full h-auto">
                </div>
                <p id="noProofMessage" class="text-sm text-slate-500 italic hidden">Tidak ada bukti analytics yang diupload</p>
            </div>
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
        <form id="rejectForm" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <p class="text-sm text-slate-300 mb-4">Anda akan menolak submission dari <span id="kreatorName" class="font-bold text-white"></span></p>
                
                <label class="block text-xs font-medium text-slate-400 mb-2">Alasan Penolakan <span class="text-red-400">*</span></label>
                <textarea name="rejection_reason" required rows="4" 
                    class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all resize-none"
                    placeholder="Jelaskan alasan penolakan (contoh: Views tidak sesuai bukti, konten tidak sesuai brief, dll)"></textarea>
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

<!-- Rejection Reason Modal -->
<div id="rejectionReasonModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-neutral-900 border border-neutral-800 rounded-2xl max-w-lg w-full">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800">
            <h3 class="text-lg font-bold text-white">Alasan Penolakan</h3>
            <button onclick="closeRejectionReasonModal()" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-5">
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4">
                <p id="rejectionReasonText" class="text-sm text-slate-300"></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showProofModal(submissionId, videoUrl, proofUrl) {
    const modal = document.getElementById('proofModal');
    const videoLink = document.getElementById('videoLink');
    const proofImage = document.getElementById('proofImage');
    const proofImageContainer = document.getElementById('proofImageContainer');
    const noProofMessage = document.getElementById('noProofMessage');
    
    // Set video link
    videoLink.href = videoUrl;
    videoLink.textContent = videoUrl;
    
    // Handle proof image
    if (proofUrl) {
        // Show loading state
        proofImageContainer.innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand"></div>
            </div>
        `;
        proofImageContainer.classList.remove('hidden');
        noProofMessage.classList.add('hidden');
        
        // Create new image element
        const img = new Image();
        img.className = 'max-w-full h-auto';
        img.alt = 'Analytics Proof';
        
        // On load success
        img.onload = function() {
            proofImageContainer.innerHTML = '';
            proofImageContainer.appendChild(img);
        };
        
        // On load error
        img.onerror = function() {
            proofImageContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <i data-lucide="image-off" class="w-12 h-12 text-red-400 mb-3"></i>
                    <p class="text-sm text-red-400 font-semibold">Gagal memuat gambar</p>
                    <p class="text-xs text-slate-500 mt-1">File mungkin tidak ditemukan atau rusak</p>
                </div>
            `;
            // Re-initialize lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        };
        
        // Set image source
        img.src = proofUrl;
    } else {
        proofImageContainer.classList.add('hidden');
        noProofMessage.classList.remove('hidden');
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeProofModal() {
    const modal = document.getElementById('proofModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function showRejectModal(submissionId, kreatorName) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const nameSpan = document.getElementById('kreatorName');
    
    form.action = `/brand/submissions/${submissionId}/reject`;
    nameSpan.textContent = kreatorName;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function showRejectionReason(reason, rejectedBy) {
    const modal = document.getElementById('rejectionReasonModal');
    const text = document.getElementById('rejectionReasonText');
    const title = document.querySelector('#rejectionReasonModal h3');
    
    text.textContent = reason;
    title.textContent = rejectedBy === 'admin' ? 'Alasan Penolakan (Admin)' : 'Alasan Penolakan';
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectionReasonModal() {
    const modal = document.getElementById('rejectionReasonModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProofModal();
        closeRejectModal();
        closeRejectionReasonModal();
    }
});

// Close modals on backdrop click
document.getElementById('proofModal').addEventListener('click', function(e) {
    if (e.target === this) closeProofModal();
});
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
document.getElementById('rejectionReasonModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectionReasonModal();
});
</script>
@endpush
@endsection
