@extends('layouts.app')

@section('title', 'Edit Status Presensi')

@section('content')
<div class="max-w-2xl mx-auto px-4">
    <div class="mb-6">
        <a href="{{ route('attendance.index') }}" class="text-orange-600 hover:text-orange-700">← Kembali</a>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Status Presensi</h1>
    <div class="bg-white rounded-xl card-shadow p-8">
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <p class="text-gray-600">Siswa: <span class="font-semibold text-gray-900">{{ $attendance->student->name }}</span></p>
            <p class="text-gray-600">Tanggal: <span class="font-semibold text-gray-900">{{ optional($attendance->attendance_date)->format('d M Y') }}</span></p>
            <p class="text-gray-600">Waktu: <span class="font-semibold text-gray-900">{{ $attendance->attendance_time }}</span></p>
        </div>
        <form action="{{ route('attendance.update', $attendance) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500">
                        <option value="hadir" {{ $attendance->status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="sakit" {{ $attendance->status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="izin" {{ $attendance->status === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="alpha" {{ $attendance->status === 'alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">Update</button>
                    <a href="{{ route('attendance.index') }}" class="px-8 py-3 rounded-lg font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
