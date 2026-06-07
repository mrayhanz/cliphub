<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    protected $fillable = [
        'admin_id',
        'title',
        'message',
        'type',
        'target',
        'recipient_count',
        'scheduled_at',
        'sent_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at'      => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /** Apakah broadcast sudah terkirim */
    public function isSent(): bool
    {
        return !is_null($this->sent_at);
    }

    /** Label & warna berdasarkan tipe */
    public static function typeConfig(): array
    {
        return [
            'info'      => ['label' => 'Info',      'color' => 'blue',   'icon' => 'info'],
            'warning'   => ['label' => 'Peringatan', 'color' => 'amber',  'icon' => 'triangle-alert'],
            'important' => ['label' => 'Penting',   'color' => 'red',    'icon' => 'alert-octagon'],
            'promo'     => ['label' => 'Promosi',   'color' => 'purple', 'icon' => 'sparkles'],
        ];
    }

    public function typeLabel(): string
    {
        return self::typeConfig()[$this->type]['label'] ?? $this->type;
    }

    public function typeColor(): string
    {
        return self::typeConfig()[$this->type]['color'] ?? 'slate';
    }

    public function typeIcon(): string
    {
        return self::typeConfig()[$this->type]['icon'] ?? 'bell';
    }

    /** Label target */
    public function targetLabel(): string
    {
        return match($this->target) {
            'kreator' => 'Kreator',
            'brand'   => 'Brand',
            default   => 'Semua User',
        };
    }
}
