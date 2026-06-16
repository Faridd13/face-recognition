@extends('layouts.app')

@section('title', $student->name)

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="mb-6">
        <a href="{{ route('students.index') }}" class="text-orange-600 hover:text-orange-700">← Kembali</a>
    </div>
    <div class="bg-white rounded-xl card-shadow p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ $student->name }}</h1>
        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Data Siswa</h2>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">NIS</span>
                        <span class="font-semibold">{{ $student->nis }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Nama</span>
                        <span class="font-semibold">{{ $student->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Kelas</span>
                        <span class="font-semibold">{{ $student->class }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Email Orang Tua</span>
                        <span class="font-semibold">{{ $student->parent_email ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Statistik</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-orange-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-orange-600">{{ $student->faceData->count() }}</p>
                        <p class="text-sm text-gray-600">Data Wajah</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $student->attendances->count() }}</p>
                        <p class="text-sm text-gray-600">Presensi</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $student->experimentLogs->count() }}</p>
                        <p class="text-sm text-gray-600">Total Experiment</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ $student->experimentLogs->where('is_correct', true)->count() }}</p>
                        <p class="text-sm text-gray-600">Correct Prediction</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 flex space-x-4">
            <a href="{{ route('students.edit', $student) }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">Edit</a>
            <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 rounded-lg font-semibold bg-red-500 text-white hover:bg-red-600">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection
