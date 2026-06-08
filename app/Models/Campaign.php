<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\CampaignParticipant;

class Campaign extends Model
{
    protected $guarded = [];

    public static function todayWib(): string
    {
        return Carbon::today('Asia/Jakarta')->toDateString();
    }

    public function scopeClaimable($query)
    {
        return $query
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('deadline')
                    ->orWhereDate('deadline', '>=', static::todayWib());
            })
            ->where(function ($query) {
                $query->where('budget', '<=', 0)
                    ->orWhereColumn('budget_spent', '<', 'budget');
            });
    }

    public function scopeEffectivelyActive(Builder $query): Builder
    {
        return $query
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('deadline')
                    ->orWhereDate('deadline', '>=', static::todayWib());
            })
            ->where(function ($query) {
                $query->where('budget', '<=', 0)
                    ->orWhereColumn('budget_spent', '<', 'budget');
            });
    }

    public function scopeEffectivelyCompleted(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->where('status', 'completed')
                ->orWhere(function ($query) {
                    $query->where('status', 'active')
                        ->whereNotNull('deadline')
                        ->whereDate('deadline', '<', static::todayWib());
                })
                ->orWhere(function ($query) {
                    $query->where('budget', '>', 0)
                        ->whereColumn('budget_spent', '>=', 'budget');
                });
        });
    }

    public static function syncExpiredCampaigns(?int $userId = null): void
    {
        static::query()
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->where('status', 'active')
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', static::todayWib())
            ->with('user')
            ->chunkById(100, function ($campaigns) {
                foreach ($campaigns as $campaign) {
                    DB::transaction(function () use ($campaign) {
                        $unusedEscrow = min(
                            $campaign->escrow_held,
                            max(0, (int) ($campaign->budget ?? 0) - (int) ($campaign->budget_spent ?? 0))
                        );

                        if ($unusedEscrow > 0) {
                            $campaign->user->increment('balance', $unusedEscrow);
                            $campaign->increment('escrow_refunded', $unusedEscrow);
                        }

                        $campaign->update(['status' => 'completed']);
                    });
                }
            });
    }

    public function getEffectiveStatusAttribute(): string
    {
        $status = strtolower((string) ($this->status ?? 'draft'));
        $budget = (float) ($this->budget ?? 0);
        $spent = (float) ($this->budget_spent ?? 0);
        $deadlinePassed = $this->deadline && Carbon::parse($this->deadline, 'Asia/Jakarta')->lt(Carbon::today('Asia/Jakarta'));

        if ($status === 'cancelled') {
            return 'cancelled';
        }

        if ($status === 'completed' || $deadlinePassed || ($budget > 0 && $spent >= $budget)) {
            return 'completed';
        }

        if ($status === 'active') {
            return 'active';
        }

        return 'draft';
    }

    public function getEffectiveStatusLabelAttribute(): string
    {
        return match ($this->effective_status) {
            'active'    => 'Aktif',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default     => 'Draft',
        };
    }

    public function getEscrowHeldAttribute(): int
    {
        return max(0, (int) ($this->escrow_amount ?? 0) - (int) ($this->escrow_paid ?? 0) - (int) ($this->escrow_refunded ?? 0));
    }

    public function getUsesEscrowAttribute(): bool
    {
        return (int) ($this->escrow_amount ?? 0) > 0;
    }

    public function getThumbnailUrlAttribute(): string
    {
        if (!$this->thumbnail) {
            return asset('images/brand/campaign-placeholder.png');
        }

        if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            return $this->thumbnail;
        }

        if (str_starts_with($this->thumbnail, 'images/')) {
            return asset($this->thumbnail);
        }

        if (Storage::disk('public')->exists($this->thumbnail)) {
            return route('media.public', ['path' => $this->thumbnail]);
        }

        return asset('storage/' . ltrim($this->thumbnail, '/'));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function participants()
    {
        return $this->hasMany(CampaignParticipant::class);
    }

    public function isJoinedBy(int $userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }
}