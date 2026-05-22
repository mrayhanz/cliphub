<?php

namespace App\Http\Controllers\Brand;

use App\Models\Campaign;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        Campaign::syncExpiredCampaigns($user->id);
        
        $balance = $user->balance ?? 0;
        
        $escrow = $user->campaigns()
            ->get()
            ->sum(fn($campaign) => $campaign->escrow_held);
        
        $activeCampaigns = $user->campaigns()->effectivelyActive()->count();
        $draftCampaigns = $user->campaigns()->where('status', 'draft')->count();
        $totalCampaignBudget = $user->campaigns()->sum('budget');

        $campaigns = $user->campaigns()->latest()->take(5)->get();
        
        // Let's assume some analytics for the top cards (mocked for now, until UGC feature is ready)
        $totalViews = 0;
        $totalUgc = 0;
        $pendingReview = 0;

        return view('brand.dashboard.index', compact('user', 'balance', 'escrow', 'campaigns', 'activeCampaigns', 'draftCampaigns', 'totalCampaignBudget', 'totalViews', 'totalUgc', 'pendingReview'));
    }
}
