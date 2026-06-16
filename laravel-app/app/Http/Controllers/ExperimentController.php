<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Condition;
use App\Models\ExperimentLog;
use App\Models\EvaluationMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExperimentController extends Controller
{
    private $pythonApiUrl;

    public function __construct()
    {
        $this->pythonApiUrl = env('PYTHON_API_URL', 'http://localhost:5000');
    }

    public function capture()
    {
        $students = Student::all();
        $conditions = Condition::all();
        return view('experiment.capture', compact('students', 'conditions'));
    }

    public function doCapture(Request $request)
    {
        $response = Http::post($this->pythonApiUrl . '/api/capture', $request->all());
        
        if ($response->successful()) {
            return back()->with('success', 'Gambar wajah berhasil di-capture!');
        }
        
        return back()->with('error', 'Gagal capture gambar');
    }

    public function train()
    {
        return view('experiment.train');
    }

    public function doTrain()
    {
        $response = Http::post($this->pythonApiUrl . '/api/train');
        
        if ($response->successful()) {
            return back()->with('success', 'Model berhasil dilatih!');
        }
        
        return back()->with('error', 'Gagal melatih model');
    }

    public function test()
    {
        $students = Student::all();
        $conditions = Condition::all();
        return view('experiment.test', compact('students', 'conditions'));
    }

    public function doTest(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'actual_identity' => 'required|string',
            'predicted_identity' => 'nullable|string',
            'confidence' => 'nullable|numeric',
            'latency' => 'nullable|numeric',
            'light_condition' => 'nullable|string',
            'face_angle' => 'nullable|string',
            'distance_condition' => 'nullable|string',
            'is_correct' => 'required|boolean',
            'experiment_type' => 'required|string|in:training,testing',
        ]);

        // Kirim ke Python API untuk disimpan juga
        $response = Http::post($this->pythonApiUrl . '/api/experiment/log', $request->all());

        // Simpan ke database Laravel juga
        ExperimentLog::create($request->all());

        return response()->json(['status' => 'success']);
    }

    public function logs()
    {
        $logs = ExperimentLog::with('student')->latest()->paginate(50);
        return view('experiment.logs', compact('logs'));
    }

    public function metrics()
    {
        $allMetrics = EvaluationMetric::with('experimentLog')->latest()->get();
        
        // Hitung rata-rata dari semua EvaluationMetric untuk "Hasil Evaluasi Keseluruhan Terbaru"
        $metrics = null;
        if ($allMetrics->count() > 0) {
            $metrics = (object)[
                'total_tests' => $allMetrics->sum('total_tests'),
                'correct_predictions' => $allMetrics->sum('correct_predictions'),
                'accuracy' => round($allMetrics->avg('accuracy'), 2),
                'precision' => round($allMetrics->avg('precision'), 2),
                'recall' => round($allMetrics->avg('recall'), 2),
                'far' => round($allMetrics->avg('far'), 2),
                'frr' => round($allMetrics->avg('frr'), 2),
                'avg_latency' => round($allMetrics->avg('avg_latency'), 3),
            ];
        }
        
        $conditions = \App\Models\Condition::all(); // Ambil semua kondisi
        
        // Hitung perhitungan per kondisi dan filter EvaluationMetric per kondisi
        $conditionMetrics = [];
        $conditionEvaluationHistory = [];
        foreach ($conditions as $condition) {
            $logs = \App\Models\ExperimentLog::where('experiment_type', 'testing')
                ->where('light_condition', $condition->light_condition)
                ->where('face_angle', $condition->face_angle)
                ->where('distance_condition', $condition->distance_condition)
                ->get();
                
            $totalTests = $logs->count();
            $correctPredictions = $logs->where('is_correct', 1)->count();
            $falseAccept = $logs->where('is_correct', 0)->whereNotNull('predicted_identity')->count();
            $falseReject = $logs->where('is_correct', 0)->whereNull('predicted_identity')->count();
            $truePositive = $correctPredictions;
            $falsePositive = $falseAccept;
            $falseNegative = $falseReject;
            
            $accuracy = $totalTests > 0 ? ($correctPredictions / $totalTests * 100) : 0;
            $precision = ($truePositive + $falsePositive) > 0 ? ($truePositive / ($truePositive + $falsePositive) * 100) : 0;
            $recall = ($truePositive + $falseNegative) > 0 ? ($truePositive / ($truePositive + $falseNegative) * 100) : 0;
            $far = $totalTests > 0 ? ($falseAccept / $totalTests * 100) : 0;
            $frr = $totalTests > 0 ? ($falseReject / $totalTests * 100) : 0;
            $avgLatency = $logs->avg('latency') ?? 0;
            
            $conditionMetrics[$condition->id] = [
                'total_tests' => $totalTests,
                'correct_predictions' => $correctPredictions,
                'accuracy' => round($accuracy, 2),
                'precision' => round($precision, 2),
                'recall' => round($recall, 2),
                'far' => round($far, 2),
                'frr' => round($frr, 2),
                'avg_latency' => round($avgLatency, 3),
            ];
            
            // Filter EvaluationMetric yang sesuai dengan kondisi ini
            $conditionEvaluationHistory[$condition->id] = EvaluationMetric::whereHas('experimentLog', function ($query) use ($condition) {
                $query->where('light_condition', $condition->light_condition)
                      ->where('face_angle', $condition->face_angle)
                      ->where('distance_condition', $condition->distance_condition);
            })->with('experimentLog')->latest()->get();
        }
        
        return view('experiment.metrics', compact('metrics', 'allMetrics', 'conditions', 'conditionMetrics', 'conditionEvaluationHistory'));
    }
    
    public function threshold()
    {
        $allMetrics = EvaluationMetric::with('experimentLog')->latest()->get();
        // Ambil semua nilai threshold unik dari ExperimentLog untuk dropdown
        $uniqueThresholds = ExperimentLog::where('experiment_type', 'training')
            ->whereNotNull('threshold')
            ->distinct()
            ->orderBy('threshold')
            ->pluck('threshold')
            ->toArray();
        
        // Jika tidak ada threshold di logs, gunakan 0-100 10% increments
        if (empty($uniqueThresholds)) {
            $uniqueThresholds = range(0, 100, 10);
        }
        
        // Ambil semua training logs
        $trainingLogs = ExperimentLog::where('experiment_type', 'training')->get();
        
        // Hitung metrik untuk setiap threshold (seperti di calculateThresholdEvaluation)
        $allThresholdMetrics = [];
        foreach ($uniqueThresholds as $threshold) {
            $allThresholdMetrics[] = $this->calculateMetricsForThreshold($trainingLogs, $threshold);
        }
        
        // Prepare individual logs for JavaScript
        $individualLogsJson = $trainingLogs->map(function ($log) {
            return [
                'id' => $log->id,
                'actual_identity' => $log->actual_identity,
                'predicted_identity' => $log->predicted_identity,
                'confidence' => $log->confidence,
                'threshold' => $log->threshold,
                'light_condition' => $log->light_condition,
                'face_angle' => $log->face_angle,
                'distance_condition' => $log->distance_condition,
                'is_correct' => $log->is_correct,
                'latency' => $log->latency,
            ];
        })->values();
        
        return view('experiment.threshold', compact('allMetrics', 'uniqueThresholds', 'trainingLogs', 'allThresholdMetrics', 'individualLogsJson'));
    }

    public function calculateMetrics()
    {
        $response = Http::get($this->pythonApiUrl . '/api/metrics');
        
        if ($response->successful()) {
            return back()->with('success', 'Metrik evaluasi berhasil dihitung!');
        }
        
        return back()->with('error', 'Gagal menghitung metrik');
    }

    // Menghitung metrik per kondisi (untuk AJAX) - rata-rata dari riwayat evaluasi sesuai kondisi
    public function calculateMetricsByCondition(Request $request)
    {
        $lightCondition = $request->query('light_condition');
        $faceAngle = $request->query('face_angle');
        $distanceCondition = $request->query('distance_condition');
        
        // Filter EvaluationMetric yang sesuai dengan kondisi
        $conditionMetrics = EvaluationMetric::whereHas('experimentLog', function ($query) use ($lightCondition, $faceAngle, $distanceCondition) {
            $query->where('light_condition', $lightCondition)
                  ->where('face_angle', $faceAngle)
                  ->where('distance_condition', $distanceCondition);
        })->get();
        
        if ($conditionMetrics->count() > 0) {
            // Hitung rata-rata dan total
            $averageMetrics = [
                'status' => 'success',
                'metrics' => [
                    'total_tests' => $conditionMetrics->sum('total_tests'),
                    'correct_predictions' => $conditionMetrics->sum('correct_predictions'),
                    'accuracy' => round($conditionMetrics->avg('accuracy'), 2),
                    'precision' => round($conditionMetrics->avg('precision'), 2),
                    'recall' => round($conditionMetrics->avg('recall'), 2),
                    'far' => round($conditionMetrics->avg('far'), 2),
                    'frr' => round($conditionMetrics->avg('frr'), 2),
                    'avg_latency' => round($conditionMetrics->avg('avg_latency'), 3),
                ]
            ];
            return response()->json($averageMetrics);
        }
        
        // Jika tidak ada data, coba hitung dari ExperimentLog seperti sebelumnya
        $logs = ExperimentLog::where('experiment_type', 'testing')
            ->where('light_condition', $lightCondition)
            ->where('face_angle', $faceAngle)
            ->where('distance_condition', $distanceCondition)
            ->get();
        
        $totalTests = $logs->count();
        $correctPredictions = $logs->where('is_correct', 1)->count();
        $falseAccept = $logs->where('is_correct', 0)->whereNotNull('predicted_identity')->count();
        $falseReject = $logs->where('is_correct', 0)->whereNull('predicted_identity')->count();
        $truePositive = $correctPredictions;
        $falsePositive = $falseAccept;
        $falseNegative = $falseReject;
        
        $accuracy = $totalTests > 0 ? ($correctPredictions / $totalTests * 100) : 0;
        $precision = ($truePositive + $falsePositive) > 0 ? ($truePositive / ($truePositive + $falsePositive) * 100) : 0;
        $recall = ($truePositive + $falseNegative) > 0 ? ($truePositive / ($truePositive + $falseNegative) * 100) : 0;
        $far = $totalTests > 0 ? ($falseAccept / $totalTests * 100) : 0;
        $frr = $totalTests > 0 ? ($falseReject / $totalTests * 100) : 0;
        $avgLatency = $logs->avg('latency') ?? 0;
        
        return response()->json([
            'status' => 'success',
            'metrics' => [
                'total_tests' => $totalTests,
                'correct_predictions' => $correctPredictions,
                'accuracy' => round($accuracy, 2),
                'precision' => round($precision, 2),
                'recall' => round($recall, 2),
                'far' => round($far, 2),
                'frr' => round($frr, 2),
                'avg_latency' => round($avgLatency, 3),
            ]
        ]);
    }

    // Hitung evaluasi per threshold dan cari optimal threshold
    public function calculateThresholdEvaluation(Request $request)
    {
        $request->validate([
            'threshold_range' => 'nullable|string',
        ]);

        $trainingLogs = ExperimentLog::where('experiment_type', 'training')->get();
        
        // Ambil semua nilai threshold unik dari training logs (dan filter null jika ada)
        $uniqueThresholds = $trainingLogs->whereNotNull('threshold')->pluck('threshold')->unique()->sort()->values()->toArray();
        
        // Jika tidak ada threshold di logs, gunakan 0-100 10% increments
        if (empty($uniqueThresholds)) {
            $uniqueThresholds = range(0, 100, 10);
        }
        
        // Tentukan threshold yang akan dihitung
        $thresholdsToCalculate = [];
        $range = $request->get('threshold_range', 'all');
        
        if ($range === 'all') {
            $thresholdsToCalculate = $uniqueThresholds;
        } else {
            $thresholdsToCalculate[] = (int)$range;
        }
        
        // Hitung evaluasi untuk setiap threshold, dan also return the individual training logs
        $allThresholdMetrics = [];
        $optimalThreshold = null;
        $bestScore = -1;

        foreach ($thresholdsToCalculate as $threshold) {
            $metrics = $this->calculateMetricsForThreshold($trainingLogs, $threshold);
            $allThresholdMetrics[] = $metrics;

            // Hitung score untuk menentukan optimal threshold (misal: accuracy + precision + recall - (far + frr), bisa diubah)
            $score = $metrics['accuracy'] + $metrics['precision'] + $metrics['recall'] - $metrics['far'] - $metrics['frr'];
            if ($score > $bestScore) {
                $bestScore = $score;
                $optimalThreshold = $metrics;
            }
        }

        // Prepare the individual training logs with their details
        $individualLogs = $trainingLogs->map(function ($log) {
            return [
                'id' => $log->id,
                'actual_identity' => $log->actual_identity,
                'predicted_identity' => $log->predicted_identity,
                'confidence' => $log->confidence,
                'threshold' => $log->threshold,
                'light_condition' => $log->light_condition,
                'face_angle' => $log->face_angle,
                'distance_condition' => $log->distance_condition,
                'is_correct' => $log->is_correct,
                'latency' => $log->latency,
            ];
        })->values()->toArray();

        return response()->json([
            'status' => 'success',
            'all_threshold_metrics' => $allThresholdMetrics,
            'optimal_threshold' => $optimalThreshold,
            'individual_logs' => $individualLogs,
        ]);
    }

    // Helper function untuk menghitung metrik berdasarkan threshold
    private function calculateMetricsForThreshold($testLogs, $threshold)
    {
        $totalTests = 0;
        $correctPredictions = 0;
        $falseAccept = 0;
        $falseReject = 0;
        $truePositive = 0;

        foreach ($testLogs as $log) {
            $totalTests++;
            // Tentukan apakah terdeteksi berdasarkan threshold
            $detected = $log->confidence >= $threshold;
            
            if ($detected && $log->is_correct) {
                $correctPredictions++;
                $truePositive++;
            } elseif ($detected && !$log->is_correct) {
                $falseAccept++;
            } elseif (!$detected && $log->is_correct) {
                $falseReject++;
            }
        }

        $accuracy = $totalTests > 0 ? ($correctPredictions / $totalTests * 100) : 0;
        $falsePositive = $falseAccept;
        $falseNegative = $falseReject;
        $precision = ($truePositive + $falsePositive) > 0 ? ($truePositive / ($truePositive + $falsePositive) * 100) : 0;
        $recall = ($truePositive + $falseNegative) > 0 ? ($truePositive / ($truePositive + $falseNegative) * 100) : 0;
        $far = $totalTests > 0 ? ($falseAccept / $totalTests * 100) : 0;
        $frr = $totalTests > 0 ? ($falseReject / $totalTests * 100) : 0;

        return [
            'threshold' => $threshold,
            'total_tests' => $totalTests,
            'correct_predictions' => $correctPredictions,
            'accuracy' => round($accuracy, 2),
            'precision' => round($precision, 2),
            'recall' => round($recall, 2),
            'far' => round($far, 2),
            'frr' => round($frr, 2),
        ];
    }
}
