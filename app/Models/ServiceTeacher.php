<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'type',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointment_availables()
    {
        return $this->hasMany(AppointmentAvailable::class,'service_teacher_id','id');
    }
}
