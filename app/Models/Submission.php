<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'user_id',
        'campaign_id',
        'platform',
        'views_claimed',
        'video_url',
        'analytics_proof_path',
        'estimated_reward',
        'status',
        'rejection_reason',
        'rejected_by',
        'brand_approved_at',
        'admin_approved_at',
    ];

    protected $casts = [
        'brand_approved_at' => 'datetime',
        'admin_approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
