@extends('layouts.app')

@section('title', 'Experiment Logs')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">Experiment Logs</h1>
    <div class="bg-white rounded-xl card-shadow overflow-hidden">
        <div class="responsive-table-container max-h-[240px] sm:max-h-[320px] overflow-y-auto">
            <table class="w-full min-w-[700px]">
            <thead class="bg-orange-50 sticky top-0">
                <tr>
                    <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800">Siswa</th>
                    <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800">Actual</th>
                    <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800">Predicted</th>
                    <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800">Confidence</th>
                    <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800">Latency</th>
                    <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800">Kondisi</th>
                    <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800">Type</th>
                    <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800">Correct</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900">{{ $log->student->name }}</td>
                        <td class="px-2 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900">{{ $log->actual_identity }}</td>
                        <td class="px-2 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900">{{ $log->predicted_identity ?? '-' }}</td>
                        <td class="px-2 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900">{{ $log->confidence ? $log->confidence . '%' : '-' }}</td>
                        <td class="px-2 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900">{{ $log->latency ? $log->latency . 'ms' : '-' }}</td>
                        <td class="px-2 sm:px-6 py-2 sm:py-4 text-gray-600 text-xs sm:text-sm">
                            {{ $log->light_condition }} | {{ $log->face_angle }} | {{ $log->distance_condition }}
                        </td>
                        <td class="px-2 sm:px-6 py-2 sm:py-4">
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $log->experiment_type === 'training' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ $log->experiment_type }}
                            </span>
                        </td>
                        <td class="px-2 sm:px-6 py-2 sm:py-4">
                            @if($log->is_correct)
                                <span class="text-green-600 font-semibold text-lg sm:text-xl">✓</span>
                            @else
                                <span class="text-red-600 font-semibold text-lg sm:text-xl">✗</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        <div class="px-3 sm:px-6 py-3 sm:py-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
