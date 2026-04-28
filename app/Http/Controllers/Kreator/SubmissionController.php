<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index()
    {
        return view('kreator.submissions.index');
    }

    public function create()
    {
        $campaigns = Campaign::where('status', 'active')
            ->latest()
            ->get();

        return view('kreator.submissions.create', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'exists:campaigns,id'],
            'platform' => ['required', 'in:TikTok,Instagram,YouTube'],
            'views_claimed' => ['required', 'integer', 'min:1'],
            'video_url' => ['required', 'url', 'max:255'],
            'analytics_proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $campaign = Campaign::findOrFail($validated['campaign_id']);
        $proofPath = $request->file('analytics_proof')->store('submission-proofs', 'public');

        Submission::create([
            'user_id' => auth()->id(),
            'campaign_id' => $campaign->id,
            'platform' => $validated['platform'],
            'views_claimed' => $validated['views_claimed'],
            'video_url' => $validated['video_url'],
            'analytics_proof_path' => $proofPath,
            'estimated_reward' => ($validated['views_claimed'] / 1000) * $campaign->price_per_1k,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('kreator.submissions')
            ->with('success', 'Klaim views berhasil diajukan dan sedang menunggu review.');
    }
}
