@extends('layouts.app')

@section('title', 'Capture Data Wajah')

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Capture Data Wajah</h1>
    <div class="grid lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl card-shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Pilih Siswa & Kondisi</h2>
            <form id="captureForm">
                @csrf
                <div class="space-y-4 mb-6">
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Siswa</label>
                        <select name="student_id" id="student_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->nis }} - {{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="condition_id" class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                        <select name="condition_id" id="condition_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                            @foreach($conditions as $condition)
                                <option value="{{ $condition->id }}">
                                    Cahaya: {{ $condition->light_condition }} | Sudut: {{ $condition->face_angle }} | Jarak: {{ $condition->distance_condition }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="num_images" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Gambar</label>
                        <input type="number" name="num_images" id="num_images" value="5" min="1" max="20" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>
                <button type="button" id="captureSubmitBtn" class="btn-primary text-white w-full px-6 py-3 rounded-lg font-semibold">Mulai Capture</button>
            </form>
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Alur Capture:</h3>
                <ol class="list-decimal list-inside space-y-2 text-gray-600">
                    <li>Pilih siswa dan kondisi yang diinginkan</li>
                    <li>Klik "Mulai Capture"</li>
                    <li>Hadapkan wajah ke kamera di dalam kotak hijau</li>
                    <li>Sistem akan mengambil gambar secara otomatis</li>
                    <li>Ulangi untuk kondisi yang lain sampai 6 kondisi selesai</li>
                </ol>
            </div>
        </div>
        <div class="bg-white rounded-xl card-shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Preview Kamera</h2>
            <div class="bg-gray-900 rounded-lg aspect-video flex items-center justify-center mb-4 overflow-hidden relative">
                <video id="video" class="rounded-lg max-w-full w-full h-full object-cover" autoplay playsinline></video>
                <div id="captureOverlay" class="absolute inset-0 pointer-events-none border-8 border-green-500 border-opacity-70 rounded-lg"></div>
                <canvas id="canvas" class="absolute inset-0 w-full h-full"></canvas>
            </div>
            <div id="captureStatus" class="text-center mb-4">
                <p class="text-gray-500">Kamera siap</p>
            </div>
            <div id="capturedImagesContainer" class="mt-6 hidden">
                <h3 class="text-lg font-semibold mb-3">Hasil Capture:</h3>
                <div id="capturedImages" class="grid grid-cols-5 gap-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
const PYTHON_API_URL = "{{ config('app.python_api_url', env('PYTHON_API_URL', 'http://localhost:5000')) }}";
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureForm = document.getElementById('captureForm');
const captureSubmitBtn = document.getElementById('captureSubmitBtn');
const captureStatus = document.getElementById('captureStatus');
const capturedImagesContainer = document.getElementById('capturedImagesContainer');
const capturedImagesDiv = document.getElementById('capturedImages');
let capturedImages = [];
let isCapturing = false;

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
    captureStatus.innerHTML = '<p class="text-red-600 font-medium">Gagal mengakses kamera!</p>';
});

// Draw canvas
video.addEventListener('loadedmetadata', () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
});

// Event submit form capture
captureSubmitBtn.addEventListener('click', async () => {
    if (isCapturing) return;
    
    const formData = new FormData(captureForm);
    const numImages = parseInt(formData.get('num_images'));
    const studentId = formData.get('student_id');
    const conditionId = formData.get('condition_id');
    
    if (!studentId || !conditionId) {
        alert('Silakan pilih siswa dan kondisi!');
        return;
    }
    
    isCapturing = true;
    capturedImages = [];
    capturedImagesContainer.classList.add('hidden');
    capturedImagesDiv.innerHTML = '';
    
    captureSubmitBtn.disabled = true;
    captureSubmitBtn.innerHTML = `
        <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Memproses...
    `;
    captureStatus.innerHTML = '<p class="text-orange-600 font-medium animate-pulse">Menyimpan gambar 0/' + numImages + '...</p>';
    
    // Capture images one by one
    let capturedCount = 0;
    const captureInterval = setInterval(() => {
        if (capturedCount >= numImages) {
            clearInterval(captureInterval);
            sendImagesToServer(studentId, conditionId);
            return;
        }
        
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        capturedImages.push(imageData);
        
        capturedCount++;
        captureStatus.innerHTML = '<p class="text-orange-600 font-medium animate-pulse">Menyimpan gambar ' + capturedCount + '/' + numImages + '...</p>';
    }, 500);
});

async function sendImagesToServer(studentId, conditionId) {
    try {
        const formData = new FormData(captureForm);
        const data = {
            student_id: studentId,
            condition_id: conditionId,
            images: capturedImages
        };
        
        const response = await fetch(PYTHON_API_URL + '/api/capture', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            // Show captured images
            capturedImagesDiv.innerHTML = '';
            capturedImages.forEach((imgData, index) => {
                const img = document.createElement('img');
                img.src = imgData;
                img.className = 'rounded-lg w-full h-32 object-cover';
                img.alt = 'Captured image ' + (index + 1);
                capturedImagesDiv.appendChild(img);
            });
            capturedImagesContainer.classList.remove('hidden');
            
            captureStatus.innerHTML = '<p class="text-green-600 font-medium">Gambar wajah berhasil di-capture!</p>';
        } else {
            captureStatus.innerHTML = '<p class="text-red-600 font-medium">Gagal capture gambar!</p>';
            alert('Gagal capture gambar: ' + (result.error || 'Unknown error'));
        }
    } catch (err) {
        console.error('Error capturing data:', err);
        captureStatus.innerHTML = '<p class="text-red-600 font-medium">Gagal terhubung ke server!</p>';
        alert('Gagal terhubung ke server! Pastikan server Python berjalan di ' + PYTHON_API_URL);
    } finally {
        isCapturing = false;
        captureSubmitBtn.disabled = false;
        captureSubmitBtn.innerHTML = 'Mulai Capture';
    }
}
</script>
@endsection