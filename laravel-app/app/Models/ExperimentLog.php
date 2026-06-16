<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperimentLog extends Model
{
    protected $fillable = [
        'student_id', 'actual_identity', 'predicted_identity',
        'confidence', 'latency', 'light_condition', 'face_angle',
        'distance_condition', 'is_correct', 'experiment_type',
        'threshold'
    ];

    protected $casts = [
        'confidence' => 'decimal:2',
        'latency' => 'decimal:3',
        'is_correct' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
