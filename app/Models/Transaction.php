<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const TYPE_DEPOSIT        = 'deposit';
    const TYPE_ESCROW_HOLD    = 'escrow_hold';
    const TYPE_ESCROW_RELEASE = 'escrow_release';
    const TYPE_ESCROW_REFUND  = 'escrow_refund';
    const TYPE_PAYOUT         = 'payout';
    const TYPE_WITHDRAWAL     = 'withdrawal';

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'reference_type',
        'reference_id',
        'balance_after',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
