<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'description',
        'file',
        'status',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function users(){
        return $this->belongsToMany(
            User::class,
            'teaching_method_users',
            'teaching_method_id',
            'user_id'
        );
    }
}
