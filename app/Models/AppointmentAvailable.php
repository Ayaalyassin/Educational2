<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentAvailable extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'status',
        'user_id',
        'service_teacher_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service_teacher()
    {
        return $this->belongsTo(User::class, 'service_teacher_id');
    }


    public function appointment_teacher_students()
    {
        return $this->hasMany(AppointmentTeacherStudent::class,'appointment_available_id','id');
    }
}
