<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $campaigns = $user->campaigns()->latest()->get();
        return view('brand.campaigns.index', compact('campaigns'));
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

        return redirect()->route('brand.campaigns')->with('success', 'Campaign berhasil ' . ($status === 'active' ? 'diluncurkan!' : 'disimpan sebagai draft.'));
    }
}
