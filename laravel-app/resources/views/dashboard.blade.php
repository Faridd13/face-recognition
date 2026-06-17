@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 py-6 sm:py-8 animate-fadeIn">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2 animate-slideInLeft">Dashboard</h1>
        <p class="text-sm sm:text-base text-gray-600 animate-slideInLeft" style="animation-delay: 0.1s;">Selamat datang! Ini adalah ringkasan sistem presensi wajah.</p>
    </div>

    <!-- Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Total Students -->
        <div class="bg-white rounded-xl card-shadow p-5 sm:p-6 border-t-4 border-orange-500 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-slideInUp" style="animation-delay: 0.1s;">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-orange-100 p-2.5 sm:p-3 rounded-full animate-pulse-slow">
                    <svg class="w-6 sm:w-8 h-6 sm:h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Total Siswa</p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">{{ $totalStudents }}</p>
                </div>
            </div>
        </div>

        <!-- Today's Attendance -->
        <div class="bg-white rounded-xl card-shadow p-5 sm:p-6 border-t-4 border-green-500 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-slideInUp" style="animation-delay: 0.2s;">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 p-2.5 sm:p-3 rounded-full animate-pulse-slow" style="animation-delay: 0.1s;">
                    <svg class="w-6 sm:w-8 h-6 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Presensi Hari Ini</p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">{{ $todayAttendance }}</p>
                </div>
            </div>
        </div>

        <!-- Total Tests -->
        <div class="bg-white rounded-xl card-shadow p-5 sm:p-6 border-t-4 border-blue-500 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-slideInUp" style="animation-delay: 0.3s;">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 p-2.5 sm:p-3 rounded-full animate-pulse-slow" style="animation-delay: 0.2s;">
                    <svg class="w-6 sm:w-8 h-6 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Total Testing</p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">{{ $totalTests }}</p>
                </div>
            </div>
        </div>

        <!-- Latest Accuracy -->
        <div class="bg-white rounded-xl card-shadow p-5 sm:p-6 border-t-4 border-purple-500 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-slideInUp" style="animation-delay: 0.4s;">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 p-2.5 sm:p-3 rounded-full animate-pulse-slow" style="animation-delay: 0.3s;">
                    <svg class="w-6 sm:w-8 h-6 sm:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Akurasi Terbaru</p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">
                        {{ $latestMetrics ? $latestMetrics->accuracy . '%' : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="bg-white rounded-xl card-shadow p-5 sm:p-6 hover:shadow-xl transition-all duration-300 animate-slideInUp" style="animation-delay: 0.5s;">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
            <h3 class="text-base sm:text-lg md:text-xl font-semibold text-gray-900 mb-2 sm:mb-0">Presensi Terbaru</h3>
            <div class="text-xs sm:text-sm text-gray-500">Memperlihatkan 10 data terbaru</div>
        </div>
        <div class="responsive-table-container max-h-[240px] sm:max-h-[320px] overflow-y-auto rounded-lg border border-gray-100">
            <table class="w-full min-w-[500px]">
                <thead class="bg-orange-50 sticky top-0">
                    <tr>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">Siswa</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider hidden sm:table-cell">Tanggal</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">Waktu</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">Status</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider hidden md:table-cell">Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @if($recentAttendance->count() > 0)
                        @foreach($recentAttendance as $attendance)
                            <tr class="hover:bg-orange-50 transition-colors duration-200 animate-fadeIn" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $attendance->student->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $attendance->student->nis }}</div>
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                    <div class="text-xs sm:text-sm text-gray-900">{{ optional($attendance->attendance_date)->format('d M Y') }}</div>
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm text-gray-900">{{ $attendance->attendance_time }}</div>
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $attendance->status === 'hadir' ? 'bg-green-100 text-green-800' : ($attendance->status === 'sakit' ? 'bg-yellow-100 text-yellow-800' : ($attendance->status === 'izin' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                    <div class="text-xs sm:text-sm text-gray-900 truncate max-w-[150px]">{{ $attendance->location ?? '-' }}</div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data presensi
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
