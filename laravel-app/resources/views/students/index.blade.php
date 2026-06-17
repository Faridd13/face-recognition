@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 animate-fadeIn">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 sm:mb-8 gap-4">
        <div class="animate-slideInLeft text-center sm:text-left">
            <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2">Data Siswa</h1>
            <p class="text-gray-600">Kelola semua data siswa di sistem</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 animate-slideInRight w-full sm:w-auto">
            <a href="{{ route('students.create') }}" class="btn-primary text-white px-3 sm:px-6 py-1.5 sm:py-3 rounded-lg font-semibold text-center flex-1 sm:flex-none text-xs sm:text-base">
                + Tambah Siswa
            </a>
        </div>
    </div>
    
    <div class="bg-white rounded-xl card-shadow overflow-hidden animate-slideInUp" style="animation-delay: 0.1s;">
        <div class="responsive-table-container max-h-[240px] sm:max-h-[320px] overflow-y-auto">
            <table class="w-full min-w-[500px] table-fixed">
                <thead class="bg-orange-50 sticky top-0">
                    <tr>
                        <th class="w-1/6 px-4 py-3 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">NIS</th>
                        <th class="w-1/3 px-4 py-3 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">Nama</th>
                        <th class="w-1/6 px-4 py-3 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">Kelas</th>
                        <th class="w-1/4 px-4 py-3 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider hidden sm:table-cell">Nomor WhatsApp Orang Tua</th>
                        <th class="w-1/6 px-4 py-3 text-center text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($students as $student)
                        <tr class="hover:bg-orange-50 transition-colors duration-200 animate-fadeIn" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                            <td class="w-1/6 px-4 py-3 text-xs sm:text-sm text-gray-900 font-medium truncate">{{ $student->nis }}</td>
                            <td class="w-1/3 px-4 py-3 text-xs sm:text-sm text-gray-900 truncate">{{ $student->name }}</td>
                            <td class="w-1/6 px-4 py-3 text-xs sm:text-sm text-gray-900 truncate">{{ $student->class }}</td>
                            <td class="w-1/4 px-4 py-3 text-xs sm:text-sm text-gray-600 hidden sm:table-cell truncate">{{ $student->parent_whatsapp ?? '-' }}</td>
                            <td class="w-1/6 px-4 py-3">
                                <div class="flex flex-wrap gap-1 justify-center">
                                    <a href="{{ route('students.edit', $student) }}" class="text-blue-600 hover:text-blue-800 px-2.5 py-1 rounded-md hover:bg-blue-50 transition-all duration-200 font-medium text-center text-xs sm:text-sm">Edit</a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 px-2.5 py-1 rounded-md hover:bg-red-50 transition-all duration-200 font-medium text-center text-xs sm:text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
