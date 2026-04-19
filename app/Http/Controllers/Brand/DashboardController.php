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
        
        // Sum of budget for campaigns that are pending review
        $escrow = $user->campaigns()->where('status', 'draft')->sum('budget'); // Replace draft with actual logic if needed later
        
        // Active campaigns
        $campaigns = $user->campaigns()->latest()->take(5)->get();
        
        // Let's assume some analytics for the top cards (mocked for now, until UGC feature is ready)
        $totalViews = 0;
        $totalUgc = 0;
        $pendingReview = 0;

        return view('brand.dashboard.index', compact('user', 'balance', 'escrow', 'campaigns', 'totalViews', 'totalUgc', 'pendingReview'));
    }
}
