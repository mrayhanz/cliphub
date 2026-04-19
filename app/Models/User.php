<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'balance',
        'bank_name',
        'bank_account',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKreator(): bool
    {
        return $this->role === 'kreator';
    }

    public function isBrand(): bool
    {
        return $this->role === 'brand';
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
}
