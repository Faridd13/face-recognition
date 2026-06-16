@extends('layouts.app')

@section('title', 'Tambah Presensi Manual')

@section('content')
<div class="max-w-2xl mx-auto px-4">
    <div class="mb-6">
        <a href="{{ route('attendance.index') }}" class="text-orange-600 hover:text-orange-700">← Kembali</a>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Tambah Presensi Manual</h1>
    <div class="bg-white rounded-xl card-shadow p-8">
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Siswa</label>
                    <select name="student_id" id="student_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500" required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->nis }} - {{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="attendance_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="attendance_date" id="attendance_date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500" required>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500">
                        <option value="hadir">Hadir</option>
                        <option value="sakit">Sakit</option>
                        <option value="izin">Izin</option>
                        <option value="alpha">Alpha</option>
                    </select>
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">Simpan</button>
                    <a href="{{ route('attendance.index') }}" class="px-8 py-3 rounded-lg font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
