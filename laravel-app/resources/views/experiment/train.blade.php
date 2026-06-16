@extends('layouts.app')

@section('title', 'Training Model')

@section('content')
<div class="max-w-2xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Training Model</h1>
    <div class="bg-white rounded-xl card-shadow p-8 text-center">
        <div class="w-24 h-24 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-semibold mb-4">Latih Model LBPH</h2>
        <p class="text-gray-600 mb-8">Model akan dilatih menggunakan semua data wajah yang telah di-capture. Pastikan semua siswa sudah mengumpulkan data di semua kondisi.</p>
        <form id="trainForm">
            @csrf
            <button type="submit" id="trainSubmitBtn" class="btn-primary text-white px-12 py-4 rounded-lg font-semibold text-lg">Mulai Training</button>
        </form>
        <div id="trainStatus" class="mt-6 text-center"></div>
    </div>
</div>

<script>
const PYTHON_API_URL = "{{ config('app.python_api_url', env('PYTHON_API_URL', 'http://localhost:5000')) }}";
const TEST_URL = "{{ route('experiment.test') }}";
const trainForm = document.getElementById('trainForm');
const trainSubmitBtn = document.getElementById('trainSubmitBtn');
const trainStatus = document.getElementById('trainStatus');

trainForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    trainSubmitBtn.disabled = true;
    trainSubmitBtn.innerHTML = `
        <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Training...
    `;
    trainStatus.innerHTML = '<p class="text-orange-600 font-medium animate-pulse">Model sedang dilatih, harap tunggu...</p>';
    
    try {
        const response = await fetch(PYTHON_API_URL + '/api/train', {
            method: 'POST'
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            trainStatus.innerHTML = '<p class="text-green-600 font-medium text-lg">Model berhasil dilatih!</p>';
            alert('Model berhasil dilatih!');
            window.location.href = TEST_URL;
        } else {
            trainStatus.innerHTML = '<p class="text-red-600 font-medium">Gagal melatih model!</p>';
            alert('Gagal melatih model: ' + (result.error || 'Unknown error'));
        }
    } catch (err) {
        console.error('Error training model:', err);
        trainStatus.innerHTML = '<p class="text-red-600 font-medium">Gagal terhubung ke server!</p>';
        alert('Gagal terhubung ke server! Pastikan server Python berjalan di ' + PYTHON_API_URL);
    } finally {
        trainSubmitBtn.disabled = false;
        trainSubmitBtn.innerHTML = 'Mulai Training';
    }
});
</script>
@endsection
