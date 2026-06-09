<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Campaign;
use App\Notifications\SubmissionStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    /**
     * Display a listing of all submissions for admin review
     */
    public function index(Request $request)
    {
        // Query submissions
        $query = Submission::with(['user', 'campaign.user']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $submissions = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'pending'           => Submission::where('status', 'approved_by_brand')->count(),
            'approved'          => Submission::where('status', 'approved_by_admin')->count(),
            'rejected_by_brand' => Submission::where('status', 'rejected_by_brand')->count(),
            'rejected_by_admin' => Submission::where('status', 'rejected_by_admin')->count(),
            'total'             => Submission::count(),
        ];

        return view('admin.submissions.index', compact('submissions', 'stats'));
    }

    /**
     * Show submission details with proof
     */
    public function show(Submission $submission)
    {
        $submission->load(['user', 'campaign.user']);
        return view('admin.submissions.show', compact('submission'));
    }

    /**
     * Approve a submission (Admin approval - final level)
     * This will release escrow funds or use the legacy balance flow.
     */
    public function approve(Submission $submission)
    {
        if ($submission->status !== 'approved_by_brand') {
            return back()->with('error', 'Submission ini belum disetujui brand atau sudah diproses sebelumnya.');
        }

        $brand   = $submission->campaign->user;
        $kreator = $submission->user;
        $reward  = (int) round((float) $submission->estimated_reward);
        $campaign = $submission->campaign;

        try {
            DB::transaction(function () use ($submission, $campaign, $brand, $kreator, $reward) {
                if ($campaign->uses_escrow) {
                    if ($campaign->escrow_held < $reward) {
                        throw new \RuntimeException('Dana escrow campaign tidak mencukupi untuk membayar reward ini.');
                    }
                    $campaign->increment('escrow_paid', $reward);
                } else {
                    if ($brand->balance < $reward) {
                        throw new \RuntimeException('Saldo brand tidak mencukupi untuk membayar reward ini.');
                    }
                    $brand->decrement('balance', $reward);
                }

                $kreator->increment('balance', $reward);

                $submission->update([
                    'status'           => 'approved_by_admin',
                    'admin_approved_at' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        // Notifikasi ke Kreator
        $submission->refresh()->load(['user', 'campaign']);
        $kreator->notify(new SubmissionStatusUpdated($submission, 'admin'));

        return back()->with('success', 'Submission berhasil disetujui! Dana escrow dilepas dan reward ditambahkan ke saldo kreator.');
    }

    /**
     * Reject a submission (Admin rejection)
     */
    public function reject(Request $request, Submission $submission)
    {
        if (!in_array($submission->status, ['pending_brand', 'approved_by_brand'])) {
            return back()->with('error', 'Submission ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $kreator = $submission->user;

        DB::transaction(function () use ($submission, $validated) {
            if ($submission->status === 'approved_by_brand') {
                $campaign = $submission->campaign;
                $reward   = (int) round((float) $submission->estimated_reward);
                $campaign->decrement('budget_spent', $reward);
            }

            $submission->update([
                'status'           => 'rejected_by_admin',
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_by'      => 'admin',
            ]);
        });

        // Notifikasi ke Kreator
        $submission->refresh()->load(['user', 'campaign']);
        $kreator->notify(new SubmissionStatusUpdated($submission, 'admin'));

        return back()->with('success', 'Submission berhasil ditolak dengan alasan yang diberikan.');
    }

    /**
     * Get analytics proof image
     */
    public function getProof(Submission $submission)
    {
        if (!$submission->analytics_proof_path) {
            abort(404, 'Bukti analytics tidak ditemukan');
        }

        $path = storage_path('app/public/' . $submission->analytics_proof_path);

        if (!file_exists($path)) {
            abort(404, 'File bukti tidak ditemukan');
        }

        return response()->file($path);
    }
}
