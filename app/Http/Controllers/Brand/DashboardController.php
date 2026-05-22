<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $balance = $user->balance ?? 0;
        
        // Active campaigns
        $campaigns = $user->campaigns()->latest()->take(5)->get();
        
        // Total Views: Only count views from Approved submissions
        $totalViews = $user->campaigns()->with(['submissions' => function($q) {
            $q->where('status', 'approved');
        }])->get()->flatMap->submissions->sum('views_claimed');

        // Total UGC/Clips: Count of approved submissions
        $totalUgc = $user->campaigns()->withCount(['submissions' => function($q) {
            $q->where('status', 'approved');
        }])->get()->sum('submissions_count');

        // Pending Review
        $pendingReview = $user->campaigns()->withCount(['submissions' => function($q) {
            $q->where('status', 'pending');
        }])->get()->sum('submissions_count');

        // Canonical Escrow Calculation: Sum of Active Campaign Budgets MINUS Paid Rewards on Approved Submissions
        $activeCampaigns = $user->campaigns()->where('status', 'active')->get();
        $totalActiveBudget = $activeCampaigns->sum('budget');
        
        $totalPaidRewards = $activeCampaigns->flatMap(function ($campaign) {
            return $campaign->submissions()->where('status', 'approved')->get();
        })->sum('estimated_reward');

        $escrow = max(0, $totalActiveBudget - $totalPaidRewards);

        // Chart Data Generation: 7-day view trend
        $chartData = [];
        $chartLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = \Carbon\Carbon::now()->subDays($i)->format('d M');
            
            $dailyViews = $user->campaigns()->with(['submissions' => function($q) use ($date) {
                $q->where('status', 'approved')->whereDate('created_at', $date);
            }])->get()->flatMap->submissions->sum('views_claimed');
            
            $chartData[] = $dailyViews;
        }

        $avgViews = $totalUgc > 0 ? round($totalViews / $totalUgc) : 0;

        return view('brand.dashboard.index', compact(
            'user', 'balance', 'escrow', 'campaigns', 
            'totalViews', 'totalUgc', 'pendingReview',
            'chartLabels', 'chartData', 'totalPaidRewards', 'avgViews'
        ));
    }
}
