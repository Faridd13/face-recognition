@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="max-w-2xl mx-auto px-4">
    <div class="mb-6">
        <a href="{{ route('students.index') }}" class="text-orange-600 hover:text-orange-700">← Kembali</a>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Siswa</h1>
    <div class="bg-white rounded-xl card-shadow p-8">
        <form action="{{ route('students.update', $student) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">NIS</label>
                    <input type="text" name="nis" id="nis" value="{{ $student->nis }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                </div>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" name="name" id="name" value="{{ $student->name }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                </div>
                <div>
                    <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <input type="text" name="class" id="class" value="{{ $student->class }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                </div>
                <div>
                    <label for="parent_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp Orang Tua</label>
                    <input type="text" name="parent_whatsapp" id="parent_whatsapp" value="{{ $student->parent_whatsapp }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: 6281234567890">
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">Update</button>
                    <a href="{{ route('students.index') }}" class="px-8 py-3 rounded-lg font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
