<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestComplete extends Model
{
    use HasFactory;

    protected $fillable = [
        'cv',
        'identity_number',
        'self_identity',
        'phone',
        'status',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
