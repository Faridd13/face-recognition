@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="gradient-bg min-h-screen">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-12 sm:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 items-center">
            <div class="text-center lg:text-left">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6">
                    Sistem Presensi
                    <span class="text-orange-600">Face Recognition</span>
                </h1>
                <p class="text-base sm:text-xl text-gray-600 mb-6 sm:mb-8">
                    Sistem presensi otomatis berbasis pengenalan wajah dengan multi-condition testing untuk penelitian.
                </p>
                <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary text-white px-6 sm:px-8 py-2 sm:py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition text-sm sm:text-base">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary text-white px-6 sm:px-8 py-2 sm:py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition text-sm sm:text-base">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
            <div class="relative">
                <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow">
                    <div class="flex items-center space-x-3 sm:space-x-4 mb-4 sm:mb-6">
                        <div class="w-12 sm:w-16 h-12 sm:h-16 rounded-full bg-orange-100 flex items-center justify-center">
                            <svg class="w-6 sm:w-8 h-6 sm:h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs sm:text-sm">Terdeteksi</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-900">M. Farid Alwaritsi</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 sm:gap-4 text-center">
                        <div class="bg-orange-50 rounded-lg p-3 sm:p-4">
                            <p class="text-xl sm:text-2xl font-bold text-orange-600">98%</p>
                            <p class="text-xs sm:text-sm text-gray-600">Confidence</p>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-3 sm:p-4">
                            <p class="text-xl sm:text-2xl font-bold text-orange-600">120ms</p>
                            <p class="text-xs sm:text-sm text-gray-600">Latency</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-3 sm:p-4">
                            <p class="text-xl sm:text-2xl font-bold text-green-600">✓</p>
                            <p class="text-xs sm:text-sm text-gray-600">Hadir</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-12 sm:mt-20 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
            <div class="bg-white rounded-xl p-5 sm:p-6 card-shadow">
                <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-lg bg-orange-100 flex items-center justify-center mb-3 sm:mb-4">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Multi-Condition Testing</h3>
                <p class="text-sm sm:text-base text-gray-600">Pengujian dengan berbagai kondisi pencahayaan, sudut wajah, dan jarak.</p>
            </div>
            <div class="bg-white rounded-xl p-5 sm:p-6 card-shadow">
                <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-lg bg-orange-100 flex items-center justify-center mb-3 sm:mb-4">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Evaluasi Metrik</h3>
                <p class="text-sm sm:text-base text-gray-600">Hitung accuracy, precision, recall, FAR, FRR, dan latency secara otomatis.</p>
            </div>
            <div class="bg-white rounded-xl p-5 sm:p-6 card-shadow">
                <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-lg bg-orange-100 flex items-center justify-center mb-3 sm:mb-4">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Anti Duplikat</h3>
                <p class="text-sm sm:text-base text-gray-600">Sistem membatasi satu presensi per sesi untuk menghindari duplikasi.</p>
            </div>
        </div>
    </div>
</div>
@endsection
