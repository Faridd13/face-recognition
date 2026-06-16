@extends('layouts.app')

@section('title', 'Face Recognition Attendance')

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <div class="mb-6">
        <a href="{{ route('attendance.index') }}" class="text-orange-600 hover:text-orange-700 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Face Recognition Attendance</h1>
    <div class="grid lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl card-shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Kamera</h2>
            <div class="bg-gray-900 rounded-lg aspect-video flex items-center justify-center mb-4 overflow-hidden relative">
                <video id="video" class="rounded-lg max-w-full w-full h-full object-cover" autoplay playsinline></video>
                <canvas id="canvas" class="absolute inset-0 w-full h-full hidden"></canvas>
            </div>
            <button id="captureBtn" class="btn-primary text-white w-full px-6 py-3 rounded-lg font-semibold">Capture & Recognize</button>
        </div>
        <div class="bg-white rounded-xl card-shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Hasil</h2>
            <div id="result" class="p-6 bg-gray-50 rounded-lg mb-4 min-h-[200px] flex items-center justify-center">
                <p class="text-gray-500 text-center">Hasil akan muncul disini</p>
            </div>
            <form id="attendanceForm" action="{{ route('attendance.mark.face') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="student_id" id="studentIdInput">
                <input type="hidden" name="confidence" id="confidenceInput">
                <input type="hidden" name="latency" id="latencyInput">
                <input type="hidden" name="light_condition" id="lightConditionInput">
                <input type="hidden" name="face_angle" id="faceAngleInput">
                <input type="hidden" name="distance_condition" id="distanceConditionInput">
                <input type="hidden" name="session_id" value="{{ session()->getId() }}">
                <input type="hidden" name="location" id="locationInput">
                <button type="submit" class="btn-primary text-white w-full px-6 py-3 rounded-lg font-semibold">Konfirmasi Presensi</button>
            </form>
        </div>
    </div>
</div>

<script>
const PYTHON_API_URL = "{{ config('app.python_api_url', env('PYTHON_API_URL', 'http://localhost:5000')) }}";
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const resultDiv = document.getElementById('result');
const attendanceForm = document.getElementById('attendanceForm');
const captureBtn = document.getElementById('captureBtn');

let currentLocation = null;
let currentTime = null;

// Function to get current time
function getCurrentTime() {
    const now = new Date();
    return now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}

// Function to get current location using geolocation
function getCurrentLocation() {
    return new Promise((resolve) => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    resolve(`${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`);
                },
                (error) => {
                    console.error('Error getting location:', error);
                    resolve('Lokasi tidak tersedia');
                }
            );
        } else {
            resolve('Geolocation tidak didukung');
        }
    });
}

// Aktifkan kamera
navigator.mediaDevices.getUserMedia({ 
    video: { facingMode: 'user' },
    audio: false
})
.then(stream => {
    video.srcObject = stream;
    video.play();
})
.catch(err => {
    console.error('Error accessing camera:', err);
    resultDiv.innerHTML = '<p class="text-red-600 text-center font-medium">Gagal mengakses kamera! Pastikan Anda memberikan izin.</p>';
});

// Event klik capture
captureBtn.addEventListener('click', async () => {
    captureBtn.disabled = true;
    captureBtn.innerHTML = `
        <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Memproses...
    `;
    resultDiv.innerHTML = `
        <div class="animate-pulse text-center">
            <p class="text-orange-600 font-semibold text-lg">Mengenali wajah...</p>
        </div>
    `;

    // Get current time and location
    currentTime = getCurrentTime();
    currentLocation = await getCurrentLocation();
    document.getElementById('locationInput').value = currentLocation;
    
    try {
        // Capture image from video
        const ctx = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        
        const response = await fetch(PYTHON_API_URL + '/api/recognize', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ image: imageData })
        });
        
        const data = await response.json();
        
        if (data.status === 'success' && data.results.length > 0) {
            const result = data.results[0];
            
            resultDiv.innerHTML = `
                <div class="text-center w-full">
                    <div class="w-24 h-24 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">${result.student_name}</h3>
                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <div class="bg-orange-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Confidence</p>
                            <p class="text-xl font-bold text-orange-600">${result.confidence}%</p>
                        </div>
                        <div class="bg-gray-100 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Latency</p>
                            <p class="text-xl font-bold text-gray-900">${result.latency}ms</p>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Waktu</p>
                            <p class="text-xl font-bold text-blue-600">${currentTime}</p>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-600">Lokasi</p>
                            <p class="text-sm font-bold text-purple-600">${currentLocation}</p>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('studentIdInput').value = result.student_id;
            document.getElementById('confidenceInput').value = result.confidence;
            document.getElementById('latencyInput').value = result.latency;
            
            // Also send conditions to attendance
            const conditions = result.conditions || {};
            document.getElementById('lightConditionInput').value = conditions.light || '';
            document.getElementById('faceAngleInput').value = conditions.angle || '';
            document.getElementById('distanceConditionInput').value = conditions.distance || '';
            
            attendanceForm.classList.remove('hidden');
        } else {
            resultDiv.innerHTML = `
                <div class="text-center w-full">
                    <div class="w-24 h-24 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-red-600 font-semibold text-lg">Wajah tidak dikenali!</p>
                    <p class="text-gray-500 text-sm mt-2">Pastikan wajah terlihat jelas di kamera</p>
                </div>
            `;
            attendanceForm.classList.add('hidden');
        }
    } catch (err) {
        console.error('Error recognizing face:', err);
        
        resultDiv.innerHTML = `
            <div class="text-center w-full">
                <div class="w-24 h-24 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <p class="text-red-600 font-semibold text-lg">Gagal terhubung ke server!</p>
                <p class="text-gray-500 text-sm mt-2">Pastikan server Python berjalan di ${PYTHON_API_URL}</p>
            </div>
        `;
        attendanceForm.classList.add('hidden');
    } finally {
        captureBtn.disabled = false;
        captureBtn.innerHTML = 'Capture & Recognize';
    }
});
</script>
@endsection
