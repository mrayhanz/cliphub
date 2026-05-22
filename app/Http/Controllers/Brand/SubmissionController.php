<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Campaign;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * Display a listing of submissions for brand's campaigns
     */
    public function index(Request $request)
    {
        $brandId = auth()->id();

        // Get all campaigns owned by this brand
        $campaignIds = Campaign::where('user_id', $brandId)->pluck('id');

        // Query submissions
        $query = Submission::with(['user', 'campaign'])
            ->whereIn('campaign_id', $campaignIds);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $submissions = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'pending' => Submission::whereIn('campaign_id', $campaignIds)->where('status', 'pending_brand')->count(),
            'approved' => Submission::whereIn('campaign_id', $campaignIds)->whereIn('status', ['approved_by_brand', 'approved_by_admin'])->count(),
            'rejected' => Submission::whereIn('campaign_id', $campaignIds)->whereIn('status', ['rejected_by_brand', 'rejected_by_admin'])->count(),
            'total_reward' => Submission::whereIn('campaign_id', $campaignIds)->where('status', 'pending_brand')->sum('estimated_reward'),
        ];

        return view('brand.submissions.index', compact('submissions', 'stats'));
    }

    /**
     * Show submission details with proof
     */
    public function show(Submission $submission)
    {
        // Verify this submission belongs to brand's campaign
        if ($submission->campaign->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $submission->load(['user', 'campaign']);

        return view('brand.submissions.show', compact('submission'));
    }

    /**
     * Approve a submission (Brand approval - first level)
     */
    public function approve(Submission $submission)
    {
        // Verify this submission belongs to brand's campaign
        if ($submission->campaign->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($submission->status !== 'pending_brand') {
            return back()->with('error', 'Submission ini sudah diproses sebelumnya.');
        }

        $campaign = $submission->campaign;
        $reward = (int) round((float) $submission->estimated_reward);

        // Check if campaign budget is sufficient
        $remainingBudget = $campaign->budget - ($campaign->budget_spent ?? 0);

        if ($reward > $remainingBudget) {
            return back()->with(
                'error',
                'Budget campaign tidak mencukupi! ' .
                    'Reward submission: Rp ' . number_format($reward, 0, ',', '.') . ', ' .
                    'Sisa budget: Rp ' . number_format($remainingBudget, 0, ',', '.') . '. ' .
                    'Silakan top up budget campaign atau tolak submission ini.'
            );
        }

        // Increment campaign budget_spent
        $campaign->increment('budget_spent', $reward);

        $submission->update([
            'status' => 'approved_by_brand',
            'rejection_reason' => null,
            'rejected_by' => null,
            'brand_approved_at' => now(),
        ]);

        return back()->with('success', 'Submission berhasil disetujui! Menunggu persetujuan admin untuk pencairan reward.');
    }

    /**
     * Reject a submission (Brand rejection)
     */
    public function reject(Request $request, Submission $submission)
    {
        // Verify this submission belongs to brand's campaign
        if ($submission->campaign->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($submission->status !== 'pending_brand') {
            return back()->with('error', 'Submission ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $submission->update([
            'status' => 'rejected_by_brand',
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_by' => 'brand',
        ]);

        return back()->with('success', 'Submission berhasil ditolak dengan alasan yang diberikan.');
    }

    /**
     * Get analytics proof image
     */
    public function getProof(Submission $submission)
    {
        // Verify this submission belongs to brand's campaign
        if ($submission->campaign->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

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
