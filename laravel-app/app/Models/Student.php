<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nis', 'name', 'class', 'parent_whatsapp',
    ];

    public function faceData()
    {
        return $this->hasMany(FaceData::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function experimentLogs()
    {
        return $this->hasMany(ExperimentLog::class);
    }
}
