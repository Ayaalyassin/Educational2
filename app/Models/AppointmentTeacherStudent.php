<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentTeacherStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'type_lesson',
        'reason',
        'user_id',
        'teacher_id',
        'appointment_available_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function appointment_available()
    {
        return $this->belongsTo(AppointmentAvailable::class, 'appointment_available_id');
    }
}
