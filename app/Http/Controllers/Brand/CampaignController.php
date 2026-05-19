<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $query = $user->campaigns();
        
        $status = $request->query('status');
        $search = $request->query('search');
        
        if ($status && in_array($status, ['active', 'completed', 'draft', 'cancelled'])) {
            $query->where('status', $status);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('desc', 'like', '%' . $search . '%');
            });
        }
        
        $currentSort = $request->query('sort', 'newest');
        switch ($currentSort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'budget_high':
                $query->orderBy('budget', 'desc');
                break;
            case 'budget_low':
                $query->orderBy('budget', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $campaigns = $query->paginate(6);
        
        $filters = [
            '' => ['label' => 'Semua', 'icon' => 'list'],
            'active' => ['label' => 'Aktif', 'icon' => 'zap'],
            'completed' => ['label' => 'Selesai', 'icon' => 'check-circle'],
            'draft' => ['label' => 'Draft', 'icon' => 'file-edit'],
            'cancelled' => ['label' => 'Dibatalkan', 'icon' => 'x-circle'],
        ];

        $sortOptions = [
            'newest' => 'Terbaru',
            'oldest' => 'Terlama',
            'name_asc' => 'Nama (A-Z)',
            'name_desc' => 'Nama (Z-A)',
            'budget_high' => 'Budget Tertinggi',
            'budget_low' => 'Budget Terendah',
        ];

        return view('brand.campaigns.index', compact('campaigns', 'status', 'search', 'filters', 'sortOptions', 'currentSort'));
    }

    public function create()
    {
        return view('brand.campaigns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:video,clip',
            'slots' => 'required|integer|min:1',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'desc' => 'required|string',
            'full_brief' => 'required|string',
            'donts' => 'required|string',
            'assets_url' => 'nullable|url',
            'deadline' => 'required|date',
            'video_length' => 'required|string|max:50',
            'link' => 'required|url',
            'platform' => 'required|string',
            'budget' => 'required|numeric|min:0',
            'price_per_1k' => 'required|numeric|min:0',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('campaigns', 'public');
        }

        // Determine status based on action button
        $status = $request->input('action') === 'active' ? 'active' : 'draft';

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Escrow Validation: check if user has enough balance for active campaigns
        if ($status === 'active' && $user->balance < $request->budget) {
            return back()->withInput()->withErrors(['budget' => 'Saldo akun Anda tidak mencukupi untuk meluncurkan campaign ini. Silakan top-up terlebih dahulu.']);
        }

        $campaign = $user->campaigns()->create([
            'title' => $request->title,
            'type' => $request->type,
            'slots' => $request->slots,
            'thumbnail' => $thumbnailPath,
            'desc' => $request->desc,
            'full_brief' => $request->full_brief,
            'donts' => $request->donts,
            'assets_url' => $request->assets_url,
            'deadline' => $request->deadline,
            'video_length' => $request->video_length,
            'link' => $request->link,
            'platform' => $request->platform,
            'budget' => $request->budget,
            'price_per_1k' => $request->price_per_1k,
            'status' => $status,
        ]);

        // Secure Escrow: Deduct budget from user's balance
        if ($status === 'active') {
            $user->decrement('balance', $request->budget);
        }

        return redirect()->route('brand.campaigns')->with('success', 'Campaign berhasil ' . ($status === 'active' ? 'diluncurkan!' : 'disimpan sebagai draft.'));
    }

    public function activate(Campaign $campaign)
    {
        abort_unless($campaign->user_id === auth()->id(), 403);
        abort_unless($campaign->status === 'draft', 400);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->balance < $campaign->budget) {
            return back()->with('error', 'Saldo akun Anda tidak mencukupi untuk meluncurkan campaign ini. Silakan top-up terlebih dahulu.');
        }

        $user->decrement('balance', $campaign->budget);
        $campaign->update(['status' => 'active']);

        return redirect()->route('brand.campaigns')->with('success', 'Campaign berhasil diluncurkan!');
    }

    public function cancel(Campaign $campaign)
    {
        abort_unless($campaign->user_id === auth()->id(), 403);
        $abort_in_array = ['cancelled', 'completed'];
        abort_if(in_array($campaign->status, $abort_in_array), 400);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // If it was active, refund escrow budget
        if ($campaign->status === 'active') {
            $user->increment('balance', $campaign->budget);
        }

        $campaign->update(['status' => 'cancelled']);

        return redirect()->route('brand.campaigns')->with('success', 'Campaign berhasil dibatalkan!');
    }

    public function complete(Campaign $campaign)
    {
        abort_unless($campaign->user_id === auth()->id(), 403);
        abort_unless($campaign->status === 'active', 400);

        $campaign->update(['status' => 'completed']);

        return redirect()->route('brand.campaigns')->with('success', 'Campaign telah selesai!');
    }

    public function show(Campaign $campaign)
    {
        abort_unless($campaign->user_id === auth()->id(), 403);
        $campaign->load(['submissions.user']);
        return view('brand.campaigns.show', compact('campaign'));
    }
}
