<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clip extends Model
{
    protected $fillable = [
        'user_id', 'title', 'hook', 'source_url', 'video_id',
        'ratio', 'resolution', 'has_captions', 'transcript', 'transcript_segments',
        'start_time', 'end_time', 'duration', 'score', 'status',
        'file_path', 'file_size',
    ];

    protected $casts = [
        'has_captions' => 'boolean',
        'start_time' => 'integer',
        'end_time' => 'integer',
        'score' => 'integer',
        'file_size' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path && is_file(storage_path('app/public/' . $this->file_path))) {
            return route('media.public', ['path' => $this->file_path]);
        }

        return null;
    }

    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) return '-';
        $kb = $this->file_size / 1024;
        if ($kb < 1024) return round($kb, 1) . ' KB';
        return round($kb / 1024, 1) . ' MB';
    }

    public function isDone(): bool
    {
        return $this->status === 'done';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isProcessing(): bool
    {
        return in_array($this->status, ['queued', 'processing']);
    }
}
