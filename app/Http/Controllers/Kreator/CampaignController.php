<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignParticipant;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    private function mapCampaignToViewArray($campaign, int $userId = 0)
    {
        $statusText = 'TERSEDIA';
        $statusCls = 's-green';
        $crColor = 'text-emerald-400';
        $creator = 'Tersedia';
        $isFull = false;

        if ($campaign->slots === 0 || $campaign->deadline < \Carbon\Carbon::now()) {
            $statusText = 'DANA HABIS';
            $statusCls = 's-red';
            $crColor = 'text-red-400';
            $creator = 'Habis';
            $isFull = true;
        } elseif ($campaign->slots > 0 && $campaign->slots <= 3) {
            $statusText = 'SISA ' . $campaign->slots . ' SLOT';
            $statusCls = 's-amber';
            $crColor = 'text-amber-400';
            $creator = 'Hampir Penuh';
        }

        $brandName = $campaign->user ? $campaign->user->name : 'Unknown';

        return [
            'id'         => $campaign->id,
            'brand'      => $brandName,
            'initial'    => strtoupper(substr($brandName, 0, 1)),
            'dotColor'   => $campaign->type === 'clip' ? '#10b981' : '#f97316',
            'category'   => $campaign->type === 'clip' ? 'Content Clip' : 'User Generated Content',
            'type'       => $campaign->type,
            'title'      => $campaign->title,
            'desc'       => $campaign->desc,
            'full_brief' => $campaign->full_brief . "\n\nDo's & Don'ts:\n" . $campaign->donts,
            'rate'       => 'Rp ' . number_format($campaign->price_per_1k, 0, ',', '.'),
            'deadline'   => \Carbon\Carbon::parse($campaign->deadline)->format('d M Y'),
            'creator'    => $creator,
            'crColor'    => $crColor,
            'statusText' => $statusText,
            'statusCls'  => $statusCls,
            'image'      => $campaign->thumbnail_url,
            'cover'      => $campaign->thumbnail_url,
            'full'       => $isFull,
            'is_joined'  => $userId ? $campaign->isJoinedBy($userId) : false,
        ];
    }

    public function index()
    {
        $campaignsData = Campaign::with('user')
            ->claimable()
            ->latest()
            ->get();

        $userId = auth()->id();
        $campaigns = [];
        foreach ($campaignsData as $c) {
            $campaigns[] = $this->mapCampaignToViewArray($c, $userId);
        }

        return view('kreator.campaigns.index', compact('campaigns'));
    }

    public function show($id)
    {
        $c = Campaign::with('user')->claimable()->findOrFail($id);

        $campaign = $this->mapCampaignToViewArray($c, auth()->id());

        return view('kreator.campaigns.show', compact('campaign'));
    }

    /**
     * Proses Kreator bergabung ke campaign (menyimpan ke database)
     */
    public function join(Request $request, $id)
    {
        $campaign = Campaign::claimable()->findOrFail($id);
        $userId = auth()->id();

        // Cek apakah sudah bergabung sebelumnya
        $alreadyJoined = CampaignParticipant::where('campaign_id', $campaign->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyJoined) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah bergabung di campaign ini sebelumnya.',
            ], 409);
        }

        CampaignParticipant::create([
            'campaign_id' => $campaign->id,
            'user_id'     => $userId,
            'joined_at'   => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil bergabung! Kamu sekarang bisa mulai mengerjakan campaign ini.',
        ]);
    }
}
