<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaceData extends Model
{
    protected $table = 'face_data';
    protected $fillable = [
        'student_id', 'condition_id', 'image_path', 'is_training',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
}
