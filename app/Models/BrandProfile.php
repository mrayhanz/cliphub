<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'contact_name',
        'phone',
        'address',
        'website',
        'logo_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
