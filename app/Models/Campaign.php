<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Campaign extends Model
{
    protected $guarded = [];

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
}
