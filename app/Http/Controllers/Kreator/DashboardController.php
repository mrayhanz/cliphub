<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;

class DashboardController extends Controller
{
    public function index()
    {
        // Status Pekerjaan (Dummy due to missing submissions table currently)
        $jobs = [
            [
                'campaign' => 'Belum Ada Pekerjaan',
                'views'    => '0',
                'status'   => 'Empty',
                'color'    => 'emerald',
                'icon'     => 'clock',
            ]
        ];

        // Campaign Rekomendasi (from DB)
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
            'total_pendapatan' => collect($jobs)->where('status', 'Approved')->sum('revenue') + $user->balance, // Simulasi, idealnya query dari submissions table
            'saldo_tersedia'   => $user->balance,
            'dalam_review'     => collect($jobs)->where('status', 'Pending Review')->sum('revenue'), // Simulasi
            'total_views'      => collect($jobs)->where('status', 'Approved')->sum('views'), // Simulasi
            'videos_approved'  => collect($jobs)->where('status', 'Approved')->count(),
            'success_rate'     => collect($jobs)->count() > 0 ? (collect($jobs)->where('status', 'Approved')->count() / count($jobs)) * 100 : 0,
            'revenue_growth'   => 0, // Default 0% selama belum ada tabel tracking pendapatan per hari/bulan
        ];

        return view('kreator.dashboard.index', compact('jobs', 'recs', 'stats'));
    }
}
