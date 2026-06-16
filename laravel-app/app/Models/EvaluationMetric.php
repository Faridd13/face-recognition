<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationMetric extends Model
{
    protected $fillable = [
        'total_tests', 'correct_predictions', 'accuracy', 'precision',
        'recall', 'far', 'frr', 'avg_latency', 'experiment_log_id',
    ];

    protected $casts = [
        'accuracy' => 'decimal:2',
        'precision' => 'decimal:2',
        'recall' => 'decimal:2',
        'far' => 'decimal:2',
        'frr' => 'decimal:2',
        'avg_latency' => 'decimal:3',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function experimentLog()
    {
        return $this->belongsTo(ExperimentLog::class);
    }
}
