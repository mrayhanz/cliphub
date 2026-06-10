<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Submission;
use App\Models\User;
use App\Notifications\SubmissionCreated;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::with(['campaign'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // Statistics
        $stats = [
            'pending' => $submissions->where('status', 'pending_brand')->count(),
            'approved' => $submissions->whereIn('status', ['approved_by_brand', 'approved_by_admin'])->count(),
            'rejected' => $submissions->whereIn('status', ['rejected_by_brand', 'rejected_by_admin'])->count(),
            'total_reward' => $submissions->where('status', 'approved_by_admin')->sum('estimated_reward'),
        ];

        return view('kreator.submissions.index', compact('submissions', 'stats'));
    }

    public function create()
    {
        $userId = auth()->id();

        // Hanya tampilkan campaign yang sudah diikuti kreator
        $joinedCampaignIds = \App\Models\CampaignParticipant::where('user_id', $userId)
            ->pluck('campaign_id');

        $campaigns = Campaign::claimable()
            ->whereIn('id', $joinedCampaignIds)
            ->latest()
            ->get();

        return view('kreator.submissions.create', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'exists:campaigns,id'],
            'platform' => ['required', 'in:TikTok,Instagram,YouTube'],
            'views_claimed' => ['nullable', 'integer', 'min:1'],
            'video_url' => ['required', 'url', 'max:255'],
            'analytics_proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'campaign_id.required' => 'Kampanye harus dipilih',
            'campaign_id.exists' => 'Kampanye yang dipilih tidak valid',
            'platform.required' => 'Platform harus dipilih',
            'platform.in' => 'Platform harus TikTok, Instagram, atau YouTube',
            'views_claimed.integer' => 'Jumlah tayangan harus berupa angka',
            'views_claimed.min' => 'Jumlah tayangan minimal 1',
            'video_url.required' => 'Link video harus diisi',
            'video_url.url' => 'Link video harus berupa URL yang valid',
            'video_url.max' => 'Link video terlalu panjang (maksimal 255 karakter)',
            'analytics_proof.required' => 'Bukti analitik harus diunggah',
            'analytics_proof.image' => 'Bukti analitik harus berupa gambar',
            'analytics_proof.mimes' => 'Bukti analitik harus berformat JPG, JPEG, atau PNG',
            'analytics_proof.max' => 'Ukuran bukti analitik maksimal 2MB',
        ]);

        $campaign = Campaign::claimable()->findOrFail($validated['campaign_id']);
        $proofPath = $request->file('analytics_proof')->store('submission-proofs', 'public');

        // Set default views_claimed to 0 if not provided
        $viewsClaimed = $validated['views_claimed'] ?? 0;

        // Calculate estimated reward
        $estimatedReward = $viewsClaimed > 0 ? ($viewsClaimed / 1000) * $campaign->price_per_1k : 0;

        // Check if reward exceeds remaining campaign budget
        $remainingBudget = $campaign->budget - ($campaign->budget_spent ?? 0);

        if ($estimatedReward > $remainingBudget) {
            // Delete uploaded proof since submission will be rejected
            \Storage::disk('public')->delete($proofPath);

            return back()->withInput()->withErrors([
                'views_claimed' => 'Estimasi imbalan (Rp ' . number_format($estimatedReward, 0, ',', '.') .
                    ') melebihi sisa anggaran kampanye (Rp ' . number_format($remainingBudget, 0, ',', '.') . '). ' .
                    'Maksimal tayangan yang bisa diklaim: ' . number_format(($remainingBudget / $campaign->price_per_1k) * 1000, 0, ',', '.') . ' tayangan.'
            ]);
        }

        $submission = Submission::create([
            'user_id' => auth()->id(),
            'campaign_id' => $campaign->id,
            'platform' => $validated['platform'],
            'views_claimed' => $viewsClaimed,
            'video_url' => $validated['video_url'],
            'analytics_proof_path' => $proofPath,
            'estimated_reward' => $estimatedReward,
            'status' => 'pending_brand',
        ]);

        // Notifikasi ke Admin dan Brand pemilik campaign
        $submission->load(['user', 'campaign.user']);
        $admins = User::where('role', 'admin')->get();
        $brand  = $campaign->user;
        foreach ($admins as $admin) {
            $admin->notify(new SubmissionCreated($submission));
        }
        $brand->notify(new SubmissionCreated($submission));

        return redirect()
            ->route('kreator.submissions')
            ->with('success', 'Klaim tayangan berhasil dikirim! Menunggu ulasan dari pemilik merek.');
    }
}
