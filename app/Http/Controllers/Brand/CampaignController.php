<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('status', 'all');
        if (!in_array($filter, ['all', 'active', 'completed', 'draft'], true)) {
            $filter = 'all';
        }

        $search = trim((string) $request->query('q', ''));

        /** @var \App\Models\User $user */
        $user = auth()->user();
        Campaign::syncExpiredCampaigns($user->id);

        $baseQuery = $user->campaigns();

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->effectivelyActive()->count(),
            'completed' => (clone $baseQuery)->effectivelyCompleted()->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
        ];

        $campaigns = $baseQuery
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->when($filter === 'active', function ($query) {
                $query->effectivelyActive();
            })
            ->when($filter === 'completed', function ($query) {
                $query->effectivelyCompleted();
            })
            ->when($filter === 'draft', function ($query) {
                $query->where('status', 'draft');
            })
            ->latest()
            ->get();

        return view('brand.campaigns.index', compact('campaigns', 'filter', 'search', 'counts'));
    }

    public function show($id)
    {
        $campaign = Campaign::where('user_id', auth()->id())->findOrFail($id);
        return view('brand.campaigns.show', compact('campaign'));
    }

    public function create()
    {
        $balance = auth()->user()->balance ?? 0;

        return view('brand.campaigns.create', compact('balance'));
    }

    public function edit($id)
    {
        $campaign = Campaign::where('user_id', auth()->id())
            ->withCount('submissions')
            ->findOrFail($id);

        if ($campaign->effective_status === 'completed') {
            return redirect()
                ->route('brand.campaigns')
                ->with('error', 'Campaign yang sudah selesai tidak bisa diedit.');
        }

        return view('brand.campaigns.edit', compact('campaign'));
    }

    public function store(Request $request)
    {
        $status = $request->input('action') === 'active' ? 'active' : 'draft';
        $todayWib = Campaign::todayWib();

        $rules = [
            'title' => ($status === 'active' ? 'required' : 'nullable') . '|string|max:255',
            'type' => ($status === 'active' ? 'required' : 'nullable') . '|string|in:video,clip',
            'slots' => ($status === 'active' ? 'required' : 'nullable') . '|integer|min:1',
            'thumbnail' => ($status === 'active' ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg|max:5120',
            'desc' => ($status === 'active' ? 'required' : 'nullable') . '|string',
            'full_brief' => ($status === 'active' ? 'required' : 'nullable') . '|string',
            'donts' => ($status === 'active' ? 'required' : 'nullable') . '|string',
            'assets_url' => 'nullable|url',
            'deadline' => ($status === 'active' ? 'required' : 'nullable') . '|date|after_or_equal:' . $todayWib,
            'video_length' => ($status === 'active' ? 'required' : 'nullable') . '|string|max:50',
            'link' => ($status === 'active' ? 'required' : 'nullable') . '|url',
            'platform' => ($status === 'active' ? 'required' : 'nullable') . '|string',
            'budget' => ($status === 'active' ? 'required' : 'nullable') . '|numeric|min:0',
            'price_per_1k' => ($status === 'active' ? 'required' : 'nullable') . '|numeric|min:0',
        ];

        $validated = $request->validate($rules, [
            'deadline.after_or_equal' => 'Deadline tidak boleh lebih awal dari tanggal hari ini (WIB).',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('campaigns', 'public');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $budget = (int) ($validated['budget'] ?? 0);

        if ($status === 'active' && $budget > 0 && $user->balance < $budget) {
            if ($thumbnailPath) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            return back()
                ->withInput()
                ->withErrors(['budget' => 'Saldo deposit tidak mencukupi untuk menahan escrow campaign ini. Silakan top up terlebih dahulu.']);
        }

        DB::transaction(function () use ($user, $validated, $thumbnailPath, $status, $budget) {
            if ($status === 'active' && $budget > 0) {
                $user->decrement('balance', $budget);
            }

            $campaign = $user->campaigns()->create([
                'title' => $validated['title'] ?? 'Draft Campaign',
                'type' => $validated['type'] ?? 'video',
                'slots' => $validated['slots'] ?? 0,
                'thumbnail' => $thumbnailPath,
                'desc' => $validated['desc'] ?? null,
                'full_brief' => $validated['full_brief'] ?? null,
                'donts' => $validated['donts'] ?? null,
                'assets_url' => $validated['assets_url'] ?? null,
                'deadline' => $validated['deadline'] ?? null,
                'video_length' => $validated['video_length'] ?? null,
                'link' => $validated['link'] ?? null,
                'platform' => $validated['platform'] ?? 'all',
                'budget' => $budget,
                'escrow_amount' => $status === 'active' ? $budget : 0,
                'price_per_1k' => $validated['price_per_1k'] ?? 0,
                'status' => $status,
            ]);

            // Catat transaksi escrow jika campaign langsung aktif
            if ($status === 'active' && $budget > 0) {
                Transaction::create([
                    'user_id'        => $user->id,
                    'type'           => Transaction::TYPE_ESCROW_HOLD,
                    'amount'         => -$budget,
                    'description'    => 'Escrow ditahan untuk campaign: ' . $campaign->title,
                    'reference_type' => 'campaign',
                    'reference_id'   => $campaign->id,
                    'balance_after'  => $user->fresh()->balance,
                ]);
            }
        });

        return redirect()->route('brand.campaigns')->with('success', 'Campaign berhasil ' . ($status === 'active' ? 'diluncurkan!' : 'disimpan sebagai draft.'));
    }

    public function update(Request $request, $id)
    {
        $todayWib = Campaign::todayWib();

        $campaign = Campaign::where('user_id', auth()->id())
            ->withCount('submissions')
            ->findOrFail($id);

        if ($campaign->effective_status === 'completed') {
            return redirect()
                ->route('brand.campaigns')
                ->with('error', 'Campaign yang sudah selesai tidak bisa diedit.');
        }

        $hasSubmissions = $campaign->submissions_count > 0;

        $rules = [
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'desc' => 'required|string',
            'full_brief' => 'required|string',
            'donts' => 'required|string',
            'assets_url' => 'nullable|url',
            'deadline' => 'required|date|after_or_equal:' . $todayWib,
            'video_length' => 'required|string|max:50',
            'link' => 'required|url',
            'platform' => 'required|string',
        ];

        if (!$hasSubmissions && $campaign->status === 'draft') {
            $rules += [
                'type' => 'required|string|in:video,clip',
                'slots' => 'required|integer|min:1',
                'budget' => 'required|numeric|min:0',
                'price_per_1k' => 'required|numeric|min:0',
            ];
        }

        $validated = $request->validate($rules, [
            'deadline.after_or_equal' => 'Deadline tidak boleh lebih awal dari tanggal hari ini (WIB).',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($campaign->thumbnail) {
                Storage::disk('public')->delete($campaign->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('campaigns', 'public');
        }

        if (!$hasSubmissions && $campaign->status === 'draft') {
            $nextStatus = $request->input('action') === 'active' ? 'active' : 'draft';
            $nextBudget = (int) ($validated['budget'] ?? 0);

            if ($nextStatus === 'active' && $nextBudget > 0 && auth()->user()->balance < $nextBudget) {
                if (isset($validated['thumbnail'])) {
                    Storage::disk('public')->delete($validated['thumbnail']);
                }

                return back()
                    ->withInput()
                    ->withErrors(['budget' => 'Saldo deposit tidak mencukupi untuk menahan escrow campaign ini. Silakan top up terlebih dahulu.']);
            }

            $validated['status'] = $nextStatus;
            $validated['escrow_amount'] = $nextStatus === 'active' ? $nextBudget : 0;
        } else {
            unset($validated['type'], $validated['slots'], $validated['budget'], $validated['price_per_1k']);
        }

        DB::transaction(function () use ($campaign, $validated, $hasSubmissions) {
            $shouldHoldEscrow = !$hasSubmissions
                && $campaign->status === 'draft'
                && ($validated['status'] ?? null) === 'active'
                && (int) ($validated['escrow_amount'] ?? 0) > 0;

            if ($shouldHoldEscrow) {
                $escrowAmount = (int) $validated['escrow_amount'];
                $user = auth()->user();
                $user->decrement('balance', $escrowAmount);

                // Catat transaksi escrow saat draft diaktifkan
                Transaction::create([
                    'user_id'        => $user->id,
                    'type'           => Transaction::TYPE_ESCROW_HOLD,
                    'amount'         => -$escrowAmount,
                    'description'    => 'Escrow ditahan untuk campaign: ' . $campaign->title,
                    'reference_type' => 'campaign',
                    'reference_id'   => $campaign->id,
                    'balance_after'  => $user->fresh()->balance,
                ]);
            }

            $campaign->update($validated);
        });

        return redirect()
            ->route('brand.campaigns')
            ->with('success', 'Campaign berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $campaign = Campaign::where('user_id', auth()->id())
            ->withCount('submissions')
            ->findOrFail($id);

        if ($campaign->effective_status === 'completed') {
            return back()->with('error', 'Campaign yang sudah selesai tidak bisa dihapus.');
        }

        if ($campaign->submissions_count > 0) {
            return back()->with('error', 'Campaign yang sudah memiliki submission tidak bisa dihapus. Simpan sebagai arsip riwayat.');
        }

        if ($campaign->thumbnail) {
            Storage::disk('public')->delete($campaign->thumbnail);
        }

        DB::transaction(function () use ($campaign) {
            $refund = $campaign->escrow_held;
            $user   = $campaign->user;

            if ($refund > 0) {
                $user->increment('balance', $refund);
                $campaign->increment('escrow_refunded', $refund);

                // Catat transaksi refund escrow saat campaign dihapus
                Transaction::create([
                    'user_id'        => $user->id,
                    'type'           => Transaction::TYPE_ESCROW_REFUND,
                    'amount'         => $refund,
                    'description'    => 'Refund escrow dari penghapusan campaign: ' . $campaign->title,
                    'reference_type' => 'campaign',
                    'reference_id'   => $campaign->id,
                    'balance_after'  => $user->fresh()->balance,
                ]);
            }

            $campaign->delete();
        });

        return redirect()
            ->route('brand.campaigns')
            ->with('success', 'Campaign berhasil dihapus.');
    }
}