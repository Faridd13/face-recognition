<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\ExperimentLog;
use App\Models\EvaluationMetric;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $todayAttendance = Attendance::whereDate('attendance_date', Carbon::today())->count();
        $totalTests = ExperimentLog::where('experiment_type', 'testing')->count();
        $latestMetrics = EvaluationMetric::latest()->first();
        
        $recentAttendance = Attendance::with('student')->latest()->take(10)->get();
        
        return view('dashboard', compact(
            'totalStudents',
            'todayAttendance',
            'totalTests',
            'latestMetrics',
            'recentAttendance'
        ));
    }
}
