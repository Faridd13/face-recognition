<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{
    private $pythonApiUrl;

    public function __construct()
    {
        $this->pythonApiUrl = env('PYTHON_API_URL', 'http://localhost:5000');
    }

    public function index()
    {
        $attendances = Attendance::with('student')->latest()->get();
        return view('attendance.index', compact('attendances'));
    }

    public function create()
    {
        $students = Student::all();
        return view('attendance.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'status' => 'required',
            'attendance_date' => 'required|date',
        ]);

        Attendance::create([
            'student_id' => $request->student_id,
            'attendance_date' => $request->attendance_date,
            'attendance_time' => now()->format('H:i:s'),
            'status' => $request->status,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Presensi berhasil ditambahkan');
    }

    public function edit(Attendance $attendance)
    {
        $students = Student::all();
        return view('attendance.edit', compact('attendance', 'students'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $attendance->update($request->only(['status']));
        return redirect()->route('attendance.index')->with('success', 'Presensi berhasil diupdate');
    }

    public function faceRecognition()
    {
        return view('attendance.face-recognition');
    }

    public function markViaFace(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'confidence' => 'nullable|numeric',
            'latency' => 'nullable|numeric',
            'session_id' => 'required',
        ]);
        
        // Check for existing attendance first (only 1 per day)
        $existingAttendance = Attendance::where('student_id', $request->student_id)
            ->where('attendance_date', now()->toDateString())
            ->first();
        
        if ($existingAttendance) {
            return back()->with('error', 'Anda sudah melakukan presensi hari ini! Silakan coba lagi besok.');
        }

        // Check time: only allow attendance between 6 AM and 12 PM (WIB)
        $currentTime = now()->format('H:i');
        $status = 'alpha';
        if ($currentTime >= '06:00' && $currentTime <= '12:00') {
            $status = 'hadir';
        }
        
        // Create attendance record in Laravel
        $attendance = Attendance::create([
            'student_id' => $request->student_id,
            'attendance_date' => now()->toDateString(),
            'attendance_time' => now()->toTimeString(),
            'status' => $status,
            'confidence' => $request->confidence,
            'latency' => $request->latency,
            'light_condition' => $request->light_condition,
            'face_angle' => $request->face_angle,
            'distance_condition' => $request->distance_condition,
            'session_id' => $request->session_id,
            'location' => $request->location,
        ]);
        
        // Prepare data for Python API (just in case we need it later)
        $pythonData = [
            'student_id' => $request->student_id,
            'confidence' => $request->confidence,
            'latency' => $request->latency,
            'session_id' => $request->session_id,
            'conditions' => [
                'light' => $request->light_condition,
                'angle' => $request->face_angle,
                'distance' => $request->distance_condition,
            ],
        ];
        
        $response = Http::post($this->pythonApiUrl . '/api/attendance', $pythonData);
        
        if ($response->successful()) {
            return back()->with('success', 'Presensi berhasil dicatat!');
        }
        
        return back()->with('error', 'Gagal mencatat presensi');
    }
}
