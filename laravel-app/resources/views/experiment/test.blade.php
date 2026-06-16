@extends('layouts.app')

@section('title', 'Testing Model')

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Testing Model</h1>
    <div class="grid lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl card-shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Setup Testing</h2>
            <form id="testForm">
                @csrf
                <div class="space-y-4 mb-6">
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Siswa (Actual Identity)</label>
                        <select name="student_id" id="student_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" data-name="{{ $student->name }}">{{ $student->nis }} - {{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="condition_id" class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                        <select name="condition_id" id="condition_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                            @foreach($conditions as $condition)
                                <option value="{{ $condition->id }}" 
                                    data-light="{{ $condition->light_condition }}" 
                                    data-angle="{{ $condition->face_angle }}" 
                                    data-distance="{{ $condition->distance_condition }}">
                                    Cahaya: {{ $condition->light_condition }} | Sudut: {{ $condition->face_angle }} | Jarak: {{ $condition->distance_condition }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="experiment_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Experiment</label>
                        <select name="experiment_type" id="experiment_type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="testing">Testing</option>
                            <option value="training">Training Verification</option>
                        </select>
                    </div>
                    <div>
                        <label for="threshold" class="block text-sm font-medium text-gray-700 mb-2">Threshold Confidence</label>
                        <select name="threshold" id="threshold" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="0">0%</option>
                            <option value="10">10%</option>
                            <option value="20">20%</option>
                            <option value="30">30%</option>
                            <option value="40">40%</option>
                            <option value="50" selected>50%</option>
                            <option value="60">60%</option>
                            <option value="70">70%</option>
                            <option value="80">80%</option>
                            <option value="90">90%</option>
                            <option value="100">100%</option>
                        </select>
                    </div>
                </div>
                <button type="submit" id="testSubmitBtn" class="btn-primary text-white w-full px-6 py-3 rounded-lg font-semibold">Mulai Testing</button>
            </form>
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Alur Testing:</h3>
                <ol class="list-decimal list-inside space-y-2 text-gray-600">
                    <li>Pilih siswa yang akan di-test (actual identity)</li>
                    <li>Pilih kondisi testing</li>
                    <li>Klik "Mulai Testing"</li>
                    <li>Hadapkan wajah ke kamera</li>
                    <li>Sistem akan mengenali dan mencatat hasilnya</li>
                    <li>Ulangi sampai 5x per siswa untuk mendapatkan data yang cukup</li>
                </ol>
            </div>
        </div>
        <div class="bg-white rounded-xl card-shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Hasil Testing</h2>
            <div class="bg-gray-900 rounded-lg aspect-video flex items-center justify-center mb-4 overflow-hidden relative">
                <video id="videoTest" class="rounded-lg max-w-full w-full h-full object-cover" autoplay playsinline></video>
                <canvas id="canvasTest" class="absolute inset-0 w-full h-full hidden"></canvas>
            </div>
            <div id="testResult" class="p-6 bg-gray-50 rounded-lg">
                <p class="text-gray-500 text-center">Hasil testing akan muncul disini</p>
            </div>
        </div>
    </div>
</div>

<script>
const PYTHON_API_URL = "{{ config('app.python_api_url', env('PYTHON_API_URL', 'http://localhost:5000')) }}";
const DO_TEST_URL = "{{ route('experiment.test.do') }}";
const videoTest = document.getElementById('videoTest');
const canvasTest = document.getElementById('canvasTest');
const testForm = document.getElementById('testForm');
const testSubmitBtn = document.getElementById('testSubmitBtn');
const testResult = document.getElementById('testResult');

// Aktifkan kamera
navigator.mediaDevices.getUserMedia({ 
    video: { facingMode: 'user' },
    audio: false
})
.then(stream => {
    videoTest.srcObject = stream;
    videoTest.play();
})
.catch(err => {
    console.error('Error accessing camera:', err);
    testResult.innerHTML = '<p class="text-red-600 text-center font-medium">Gagal mengakses kamera!</p>';
});

// Event submit form test
testForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const studentSelect = document.getElementById('student_id');
    const conditionSelect = document.getElementById('condition_id');
    const experimentType = document.getElementById('experiment_type').value;
    
    const studentId = studentSelect.value;
        const actualIdentity = studentSelect.options[studentSelect.selectedIndex].dataset.name;
        const lightCondition = conditionSelect.options[conditionSelect.selectedIndex].dataset.light;
        const faceAngle = conditionSelect.options[conditionSelect.selectedIndex].dataset.angle;
        const distanceCondition = conditionSelect.options[conditionSelect.selectedIndex].dataset.distance;
        const threshold = document.getElementById('threshold').value;
    
    testSubmitBtn.disabled = true;
    testSubmitBtn.innerHTML = `
        <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Memproses...
    `;
    testResult.innerHTML = `
        <div class="animate-pulse text-center">
            <p class="text-orange-600 font-semibold text-lg">Mengenali wajah...</p>
        </div>
    `;
    
    try {
        // Step 1: Capture image from video
        const ctx = canvasTest.getContext('2d');
        canvasTest.width = videoTest.videoWidth;
        canvasTest.height = videoTest.videoHeight;
        ctx.drawImage(videoTest, 0, 0, canvasTest.width, canvasTest.height);
        const imageData = canvasTest.toDataURL('image/jpeg', 0.8);
        
        // Step 2: Panggil Python API untuk recognize
        const recognizeResponse = await fetch(PYTHON_API_URL + '/api/recognize', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                image: imageData,
                light_condition: lightCondition,
                face_angle: faceAngle,
                distance_condition: distanceCondition
            })
        });
        
        const recognizeData = await recognizeResponse.json();
        
        let predictedIdentity = null;
        let confidence = null;
        let latency = null;
        let isCorrect = false;
        
        if (recognizeData.status === 'success' && recognizeData.results.length > 0) {
            const result = recognizeData.results[0];
            predictedIdentity = result.student_name;
            confidence = result.confidence;
            latency = result.latency;
            isCorrect = actualIdentity === predictedIdentity;
            
            testResult.innerHTML = `
                <div class="text-center w-full">
                    <div class="w-24 h-24 rounded-full ${isCorrect ? 'bg-green-100' : 'bg-red-100'} flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 ${isCorrect ? 'text-green-600' : 'text-red-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${isCorrect 
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                            }
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Hasil Testing</h3>
                    <p class="text-gray-600"><span class="font-semibold">Actual:</span> ${actualIdentity}</p>
                    <p class="text-gray-600"><span class="font-semibold">Predicted:</span> ${predictedIdentity}</p>
                    <p class="text-orange-600 font-semibold mt-2">Confidence: ${confidence}%</p>
                    <p class="text-gray-600">Latency: ${latency}ms</p>
                    <p class="${isCorrect ? 'text-green-600' : 'text-red-600'} font-semibold text-lg mt-2">
                        ${isCorrect ? '✓ BENAR' : '✗ SALAH'}
                    </p>
                </div>
            `;
        } else {
            testResult.innerHTML = `
                <div class="text-center w-full">
                    <div class="w-24 h-24 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Hasil Testing</h3>
                    <p class="text-gray-600"><span class="font-semibold">Actual:</span> ${actualIdentity}</p>
                    <p class="text-gray-600"><span class="font-semibold">Predicted:</span> Tidak dikenali</p>
                    <p class="text-red-600 font-semibold text-lg mt-2">✗ SALAH</p>
                </div>
            `;
        }
        
        // Langkah 2: Kirim data ke Laravel untuk disimpan ke database
        await fetch(DO_TEST_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                student_id: studentId,
                actual_identity: actualIdentity,
                predicted_identity: predictedIdentity,
                confidence: confidence,
                latency: latency,
                light_condition: lightCondition,
                face_angle: faceAngle,
                distance_condition: distanceCondition,
                is_correct: isCorrect,
                experiment_type: experimentType,
                threshold: threshold
            })
        });
        
    } catch (err) {
        console.error('Error during testing:', err);
        testResult.innerHTML = `
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
    } finally {
        testSubmitBtn.disabled = false;
        testSubmitBtn.innerHTML = 'Mulai Testing';
    }
});
</script>
@endsection
