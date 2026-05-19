<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Base query for campaigns owned by the brand
        $submissionsQuery = Submission::whereHas('campaign', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['campaign', 'user']);

        // Base stats (across all submissions for this brand)
        $statsQuery = Submission::whereHas('campaign', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });

        $pendingCount = (clone $statsQuery)->where('status', 'pending')->count();
        $approvedCount = (clone $statsQuery)->where('status', 'approved')->count();
        $rejectedCount = (clone $statsQuery)->where('status', 'rejected')->count();
        $totalEstimatedReward = (clone $statsQuery)->where('status', 'approved')->sum('estimated_reward');

        // Apply filters based on request tabs
        $statusFilter = $request->input('status');
        if ($statusFilter === 'pending') {
            $submissionsQuery->where('status', 'pending');
        } elseif ($statusFilter === 'completed') {
            $submissionsQuery->whereIn('status', ['approved', 'rejected']);
        }
        
        $search = $request->input('search');
        if (!empty($search)) {
            $submissionsQuery->where(function ($q) use ($search) {
                $q->whereHas('campaign', function ($qc) use ($search) {
                    $qc->where('title', 'like', "%{$search}%");
                })->orWhereHas('user', function ($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                });
            });
        }

        $currentSort = $request->query('sort', 'newest');
        switch ($currentSort) {
            case 'oldest':
                $submissionsQuery->orderBy('submissions.created_at', 'asc');
                break;
            case 'reward_high':
                $submissionsQuery->orderBy('estimated_reward', 'desc');
                break;
            case 'views_high':
                $submissionsQuery->orderBy('current_views', 'desc');
                break;
            case 'name_asc':
                $submissionsQuery->join('users', 'submissions.user_id', '=', 'users.id')
                                 ->orderBy('users.name', 'asc')
                                 ->select('submissions.*');
                break;
            case 'name_desc':
                $submissionsQuery->join('users', 'submissions.user_id', '=', 'users.id')
                                 ->orderBy('users.name', 'desc')
                                 ->select('submissions.*');
                break;
            case 'newest':
            default:
                $submissionsQuery->orderBy('submissions.created_at', 'desc');
                break;
        }

        $submissions = $submissionsQuery->paginate(10);
        
        $filters = [
            '' => ['label' => 'Semua', 'icon' => 'list'],
            'pending' => ['label' => 'Menunggu', 'icon' => 'clock'],
            'completed' => ['label' => 'Selesai', 'icon' => 'check-circle'],
        ];

        $sortOptions = [
            'newest' => 'Terbaru',
            'oldest' => 'Terlama',
            'name_asc' => 'Nama Kreator (A-Z)',
            'name_desc' => 'Nama Kreator (Z-A)',
            'reward_high' => 'Reward Tertinggi',
            'views_high' => 'Views Terbanyak',
        ];

        return view('brand.submissions.index', compact(
            'submissions',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalEstimatedReward',
            'statusFilter',
            'search',
            'filters',
            'sortOptions',
            'currentSort'
        ));
    }

    public function approve(Submission $submission)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Scoping / authorization check
        if ($submission->campaign->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Idempotency check
        if ($submission->status !== 'pending') {
            return redirect()->back()->with('error', 'Submission ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($submission) {
            $campaign = $submission->campaign;
            
            // Calculate reward: (views_claimed * price_per_1k) / 1000
            $reward = ($submission->views_claimed * $campaign->price_per_1k) / 1000;

            // Enforce update
            $submission->update([
                'status' => 'approved',
                'estimated_reward' => $reward,
            ]);

            // Deposit to creator
            $submission->user()->increment('balance', $reward);
        });

        return redirect()->back()->with('success', 'Submission berhasil disetujui, dana escrow ditransfer ke dompet kreator.');
    }

    public function reject(Request $request, Submission $submission)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Scoping / authorization check
        if ($submission->campaign->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Idempotency check
        if ($submission->status !== 'pending') {
            return redirect()->back()->with('error', 'Submission ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $submission->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return redirect()->back()->with('warning', 'Submission berhasil ditolak dengan alasan yang terlampir.');
    }

    public function show(Submission $submission)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Scoping / authorization check
        if ($submission->campaign->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $submission->load(['campaign', 'user']);
        return view('brand.submissions.show', compact('submission'));
    }
}
