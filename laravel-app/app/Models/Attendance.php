<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $fillable = [
        'student_id', 'attendance_date', 'attendance_time', 'status',
        'confidence', 'latency', 'light_condition', 'face_angle',
        'distance_condition', 'session_id', 'location',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'confidence' => 'decimal:2',
        'latency' => 'decimal:3',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
