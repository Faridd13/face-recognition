<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    public function faceData()
    {
        return $this->hasMany(FaceData::class);
    }
}
