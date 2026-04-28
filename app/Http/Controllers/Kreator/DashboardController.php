<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;

class DashboardController extends Controller
{
    public function index()
    {
        $campaignsData = Campaign::with('user')
            ->where('status', 'active')
            ->inRandomOrder()
            ->take(3)
            ->get();

        $recs = [];
        foreach ($campaignsData as $c) {
            $brandName = $c->user ? $c->user->name : 'Unknown';
            
            // Generate visual style based on campaign type
            if ($c->type === 'clip') {
                $dotColor = '#10b981';
                $bgAlpha = 'rgba(16,185,129,0.05)';
                $iconBg = 'rgba(16,185,129,0.12)';
                $tag = 'Nge-clip';
            } else {
                $dotColor = '#ec4899';
                $bgAlpha = 'rgba(236,72,153,0.05)';
                $iconBg = 'rgba(236,72,153,0.12)';
                $tag = 'UGC';
            }

            $recs[] = [
                'brand'    => $brandName,
                'title'    => $c->title,
                'rate'     => 'Rp ' . number_format($c->price_per_1k, 0, ',', '.') . ' / 1K views',
                'tag'      => $tag,
                'dotColor' => $dotColor,
                'bgAlpha'  => $bgAlpha,
                'iconBg'   => $iconBg,
            ];
        }

        // Statistics User
        $user = auth()->user();
        $stats = [
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'saldo_tersedia'   => $user->balance,
        ];

        return view('kreator.dashboard.index', compact('recs', 'stats'));
    }
}
