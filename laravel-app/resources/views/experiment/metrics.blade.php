@extends('layouts.app')

@section('title', 'Evaluation Metrics')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4">
    <!-- Modal untuk Grafik -->
    <div id="chartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-4 sm:p-8 max-w-4xl w-full mx-2 sm:mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900" id="chartModalTitle">Grafik Evaluasi</h2>
                <button onclick="closeChartModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="h-64 sm:h-96">
                <canvas id="metricsChart"></canvas>
            </div>
        </div>
    </div>
    


    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">Evaluation Metrics</h1>

    @if($metrics)
        <div class="bg-white rounded-xl card-shadow p-4 sm:p-8 mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-semibold mb-4 sm:mb-6">Hasil Evaluasi Keseluruhan Terbaru</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-4 sm:mb-8">
                <div class="text-center p-3 sm:p-6 bg-orange-50 rounded-xl border border-orange-100">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Total Tests</p>
                    <p class="text-2xl sm:text-4xl font-bold text-orange-600">{{ $metrics->total_tests }}</p>
                </div>
                <div class="text-center p-3 sm:p-6 bg-green-50 rounded-xl border border-green-100">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Correct Predictions</p>
                    <p class="text-2xl sm:text-4xl font-bold text-green-600">{{ $metrics->correct_predictions }}</p>
                </div>
                <div class="text-center p-3 sm:p-6 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Accuracy</p>
                    <p class="text-2xl sm:text-4xl font-bold text-blue-600">{{ $metrics->accuracy }}%</p>
                </div>
                <div class="text-center p-3 sm:p-6 bg-yellow-50 rounded-xl border border-yellow-100">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Avg Latency</p>
                    <p class="text-2xl sm:text-4xl font-bold text-yellow-600">{{ $metrics->avg_latency }}ms</p>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                <div class="text-center p-3 sm:p-6 bg-purple-50 rounded-xl border border-purple-100">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Precision</p>
                    <p class="text-2xl sm:text-4xl font-bold text-purple-600">{{ $metrics->precision }}%</p>
                </div>
                <div class="text-center p-3 sm:p-6 bg-pink-50 rounded-xl border border-pink-100">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Recall</p>
                    <p class="text-2xl sm:text-4xl font-bold text-pink-600">{{ $metrics->recall }}%</p>
                </div>
                <div class="text-center p-3 sm:p-6 bg-red-50 rounded-xl border border-red-100">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">FAR</p>
                    <p class="text-2xl sm:text-4xl font-bold text-red-600">{{ $metrics->far }}%</p>
                </div>
                <div class="text-center p-3 sm:p-6 bg-red-50 rounded-xl border border-red-100">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">FRR</p>
                    <p class="text-2xl sm:text-4xl font-bold text-red-600">{{ $metrics->frr }}%</p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl card-shadow p-4 sm:p-8 mb-6 sm:mb-8 text-center">
            <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0 a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Belum Ada Data Evaluasi</h3>
            <p class="text-sm sm:text-base text-gray-600 mb-4">Klik "Hitung Metrik" di bawah untuk menghitung metrik evaluasi dari data experiment yang ada</p>
        </div>
    @endif

    <!-- Riwayat Evaluasi -->
    <div class="bg-white rounded-xl card-shadow p-4 sm:p-8 mb-6 sm:mb-8" id="overall-evaluation">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
            <h2 class="text-lg sm:text-xl font-semibold">Riwayat Evaluasi</h2>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <form id="calculateForm" action="{{ route('experiment.metrics.calculate') }}" method="POST" class="flex-1 sm:flex-none">
                    @csrf
                    <button type="submit" id="calculateSubmitBtn" class="btn-primary text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold w-full sm:w-auto text-xs sm:text-base">
                        Hitung Metrik
                    </button>
                </form>
                <button type="button" class="view-overall-chart-btn btn-purple text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold flex-1 sm:flex-none text-xs sm:text-base">
                    Grafik
                </button>
                <div class="relative flex-1 sm:flex-none w-full sm:w-auto" id="overall-download-container">
                    <button type="button" class="overall-download-btn btn-green text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold w-full sm:w-auto text-xs sm:text-base">
                        Download
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-10 hidden border border-gray-100" id="overall-download-dropdown">
                        <button onclick="downloadOverallExcel()" class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-2m3 2v-2m-9-6h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2z"></path></svg>
                                Excel
                            </span>
                        </button>
                        <button onclick="downloadOverallPDF()" class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                PDF
                            </span>
                        </button>
                        <button onclick="downloadOverallPNG()" class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                PNG
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="responsive-table-container max-h-[240px] sm:max-h-[320px] overflow-y-auto">
            <table class="w-full min-w-[800px]">
                    <thead class="bg-orange-50 sticky top-0">
                        <tr>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">No</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Actual</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Predicted</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Conf</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Light</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Angle</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Dist</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Acc</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Prec</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Rec</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">FAR</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">FRR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($allMetrics as $index => $metric)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->experimentLog?->actual_identity ?? '-' }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->experimentLog?->predicted_identity ?? 'Tdk' }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->experimentLog?->confidence ?? '-' }}%</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->experimentLog?->light_condition ?? '-' }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->experimentLog?->face_angle ?? '-' }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->experimentLog?->distance_condition ?? '-' }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->accuracy }}%</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->precision }}%</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->recall }}%</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->far }}%</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->frr }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>



    <!-- Evaluasi Per Kondisi -->
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Evaluasi Per Kondisi</h2>
    @foreach($conditions as $index => $condition)
        @php
            $condMetric = $conditionMetrics[$condition->id] ?? null;
            $condHistory = $conditionEvaluationHistory[$condition->id] ?? [];
        @endphp
        <div class="bg-white rounded-xl card-shadow p-4 sm:p-8 mb-6 sm:mb-8" id="condition-{{ $condition->id }}">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
                <h3 class="text-base sm:text-xl font-semibold break-words">
                    Kondisi {{ $index + 1 }}: 
                    ({{ ucfirst($condition->light_condition) }} | {{ ucfirst($condition->face_angle) }} | {{ ucfirst($condition->distance_condition) }})
                </h3>
                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    <button type="button" class="calculate-condition-btn btn-primary text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold flex-1 sm:flex-none text-xs sm:text-base" 
                        data-light="{{ $condition->light_condition }}" 
                        data-angle="{{ $condition->face_angle }}" 
                        data-distance="{{ $condition->distance_condition }}"
                        data-condition-id="{{ $condition->id }}"
                        data-index="{{ $index }}">
                        Hitung Metrik
                    </button>
                    <button type="button" class="view-chart-btn btn-purple text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold flex-1 sm:flex-none text-xs sm:text-base" data-condition-id="{{ $condition->id }}">
                        Grafik
                    </button>
                    <div class="relative flex-1 sm:flex-none w-full sm:w-auto" id="download-container-{{ $condition->id }}">
                        <button type="button" class="download-btn btn-green text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold w-full sm:w-auto text-xs sm:text-base" data-condition-id="{{ $condition->id }}">
                            Download
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-10 hidden border border-gray-100" id="dropdown-{{ $condition->id }}">
                            <button onclick="downloadExcel({{ $condition->id }})" class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-2m3 2v-2m-9-6h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2z"></path></svg>
                                    Excel
                                </span>
                            </button>
                            <button onclick="downloadPDF({{ $condition->id }})" class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    PDF
                                </span>
                            </button>
                            <button onclick="downloadPNG({{ $condition->id }})" class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    PNG
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabel Riwayat Evaluasi Per Kondisi -->
            <div class="mb-6 sm:mb-8">
                <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Riwayat Evaluasi Kondisi Ini</h4>
                <div class="responsive-table-container max-h-[240px] sm:max-h-[320px] overflow-y-auto">
                    <table class="w-full min-w-[600px]">
                        <thead class="bg-orange-50 sticky top-0">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">No</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Actual</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Predicted</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Conf</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Acc</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Prec</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Rec</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">FAR</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">FRR</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($condHistory as $histIndex => $metric)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $histIndex + 1 }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->experimentLog?->actual_identity ?? '-' }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->experimentLog?->predicted_identity ?? 'Tdk' }}</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->experimentLog?->confidence ?? '-' }}%</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->accuracy }}%</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->precision }}%</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->recall }}%</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->far }}%</td>
                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric->frr }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Area Hasil Hitung Metrik Keseluruhan Per Kondisi -->
            <div id="condition-summary-{{ $condition->id }}" class="hidden"></div>
        </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.31/dist/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
const PYTHON_API_URL = "{{ config('app.python_api_url', env('PYTHON_API_URL', 'http://localhost:5000')) }}";
const calculateForm = document.getElementById('calculateForm');
const calculateSubmitBtn = document.getElementById('calculateSubmitBtn');
const calculateConditionBtns = document.querySelectorAll('.calculate-condition-btn');
const downloadBtns = document.querySelectorAll('.download-btn');
const viewChartBtns = document.querySelectorAll('.view-chart-btn');
let metricsChart = null;

// Data untuk semua kondisi dan riwayat evaluasi (dari Controller)
const conditionMetricsData = @json($conditionMetrics);
const conditionEvaluationHistoryData = @json($conditionEvaluationHistory);
const conditionsData = @json($conditions->map(function($cond) {
    return [
        'id' => $cond->id,
        'name' => "Kondisi: {$cond->light_condition} | {$cond->face_angle} | {$cond->distance_condition}"
    ];
}));
const allEvaluationHistory = @json($allMetrics->reverse()->values()); // Reverse agar dari test pertama ke terakhir



// Hitung Metrik Keseluruhan
calculateForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    calculateSubmitBtn.disabled = true;
    calculateSubmitBtn.innerHTML = `
        <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Menghitung...
    `;
    
    try {
        const response = await fetch(PYTHON_API_URL + '/api/metrics', {
            method: 'GET'
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            alert('Metrik evaluasi berhasil dihitung!');
            window.location.reload();
        } else {
            alert('Gagal menghitung metrik: ' + (result.error || 'Unknown error'));
        }
    } catch (err) {
        console.error('Error calculating metrics:', err);
        alert('Gagal terhubung ke server! Pastikan server Python berjalan di ' + PYTHON_API_URL);
    } finally {
        calculateSubmitBtn.disabled = false;
        calculateSubmitBtn.innerHTML = 'Hitung Metrik';
    }
});

// Hitung Metrik Per Kondisi (dan tampilkan summary)
calculateConditionBtns.forEach(btn => {
    btn.addEventListener('click', async () => {
        const conditionId = btn.dataset.conditionId;
        const light = btn.dataset.light;
        const angle = btn.dataset.angle;
        const distance = btn.dataset.distance;
        
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Menghitung...
        `;

        const summaryDiv = document.getElementById(`condition-summary-${conditionId}`);
        summaryDiv.classList.remove('hidden');

        try {
            const params = new URLSearchParams({
                light_condition: light,
                face_angle: angle,
                distance_condition: distance
            });

            const response = await fetch("{{ route('experiment.metrics.calculate-by-condition') }}?" + params, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                const metrics = result.metrics;
                
                // Tampilkan summary
                summaryDiv.innerHTML = `
                    <div class="border-t border-gray-200 pt-4 sm:pt-6">
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Hasil Hitung Metrik Keseluruhan Kondisi Ini</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-4 sm:mb-6">
                            <div class="text-center p-3 sm:p-6 bg-orange-50 rounded-xl border border-orange-100">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Total Tests</p>
                                <p class="text-2xl sm:text-4xl font-bold text-orange-600">${metrics.total_tests}</p>
                            </div>
                            <div class="text-center p-3 sm:p-6 bg-green-50 rounded-xl border border-green-100">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Correct Predictions</p>
                                <p class="text-2xl sm:text-4xl font-bold text-green-600">${metrics.correct_predictions}</p>
                            </div>
                            <div class="text-center p-3 sm:p-6 bg-blue-50 rounded-xl border border-blue-100">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Accuracy</p>
                                <p class="text-2xl sm:text-4xl font-bold text-blue-600">${metrics.accuracy}%</p>
                            </div>
                            <div class="text-center p-3 sm:p-6 bg-yellow-50 rounded-xl border border-yellow-100">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Avg Latency</p>
                                <p class="text-2xl sm:text-4xl font-bold text-yellow-600">${metrics.avg_latency}ms</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                            <div class="text-center p-3 sm:p-6 bg-purple-50 rounded-xl border border-purple-100">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Precision</p>
                                <p class="text-2xl sm:text-4xl font-bold text-purple-600">${metrics.precision}%</p>
                            </div>
                            <div class="text-center p-3 sm:p-6 bg-pink-50 rounded-xl border border-pink-100">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Recall</p>
                                <p class="text-2xl sm:text-4xl font-bold text-pink-600">${metrics.recall}%</p>
                            </div>
                            <div class="text-center p-3 sm:p-6 bg-red-50 rounded-xl border border-red-100">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">FAR</p>
                                <p class="text-2xl sm:text-4xl font-bold text-red-600">${metrics.far}%</p>
                            </div>
                            <div class="text-center p-3 sm:p-6 bg-red-50 rounded-xl border border-red-100">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">FRR</p>
                                <p class="text-2xl sm:text-4xl font-bold text-red-600">${metrics.frr}%</p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                alert('Gagal menghitung metrik: ' + (result.message || 'Unknown error'));
            }
        } catch (err) {
            console.error('Error calculating metrics:', err);
            alert('Gagal terhubung ke server!');
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Hitung Metrik';
        }
    });
});

// Dropdown Download (Kondisi)
downloadBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const conditionId = btn.dataset.conditionId;
        const dropdown = document.getElementById(`dropdown-${conditionId}`);
        
        // Tutup semua dropdown lain
        document.querySelectorAll('[id^="dropdown-"]').forEach(otherDropdown => {
            if (otherDropdown.id !== `dropdown-${conditionId}`) {
                otherDropdown.classList.add('hidden');
            }
        });
        
        dropdown.classList.toggle('hidden');
    });
});

// Tutup dropdown ketika klik di luar
document.addEventListener('click', (e) => {
    // Tutup semua dropdown, termasuk overall
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"], #overall-download-dropdown');
    allDropdowns.forEach(dropdown => {
        const container = dropdown.closest('[id^="download-container-"], #overall-download-container');
        if (!container.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});

// Fungsi untuk menutup modal grafik
function closeChartModal() {
    document.getElementById('chartModal').classList.add('hidden');
    document.getElementById('chartModal').classList.remove('flex');
    if (metricsChart) {
        metricsChart.destroy();
        metricsChart = null;
    }
}

// Fungsi untuk membuka modal dan membuat grafik
function openChartModal(conditionId) {
    const modal = document.getElementById('chartModal');
    const modalTitle = document.getElementById('chartModalTitle');
    const chartCanvas = document.getElementById('metricsChart').getContext('2d');

    // Cari data kondisi
    const condition = conditionsData.find(c => c.id === conditionId);
    const history = conditionEvaluationHistoryData[conditionId] || [];

    if (!condition) {
        alert('Data tidak ditemukan!');
        return;
    }

    modalTitle.textContent = `Grafik Evaluasi: ${condition.name}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Buat grafik garis (line chart) dari semua riwayat
    const labels = history.map((_, index) => `Test ${index + 1}`);
    const datasets = [
        {
            label: 'Accuracy',
            data: history.map(m => m.accuracy),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.3,
            fill: true
        },
        {
            label: 'Precision',
            data: history.map(m => m.precision),
            borderColor: '#eab308',
            backgroundColor: 'rgba(234, 179, 8, 0.1)',
            tension: 0.3,
            fill: true
        },
        {
            label: 'Recall',
            data: history.map(m => m.recall),
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.3,
            fill: true
        },
        {
            label: 'FAR',
            data: history.map(m => m.far),
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.3,
            fill: true
        },
        {
            label: 'FRR',
            data: history.map(m => m.frr),
            borderColor: '#a855f7',
            backgroundColor: 'rgba(168, 85, 247, 0.1)',
            tension: 0.3,
            fill: true
        }
    ];

    metricsChart = new Chart(chartCanvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

// Listener untuk tombol Lihat Grafik
viewChartBtns.forEach(btn => {
    const conditionDiv = btn.closest('[id^="condition-"]');
    const conditionId = parseInt(conditionDiv.id.replace('condition-', ''));
    btn.addEventListener('click', () => openChartModal(conditionId));
});

// Tutup modal ketika klik di luar
document.getElementById('chartModal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('chartModal')) {
        closeChartModal();
    }
});

// Fungsi download Excel
function downloadExcel(conditionId) {
    const condition = conditionsData.find(c => c.id === conditionId);
    const metrics = conditionMetricsData[conditionId];
    if (!condition || !metrics) {
        alert('Data tidak ditemukan!');
        return;
    }

    const data = [
        ['Parameter', 'Nilai'],
        ['Total Tests', metrics.total_tests],
        ['Correct Predictions', metrics.correct_predictions],
        ['Accuracy (%)', metrics.accuracy],
        ['Precision (%)', metrics.precision],
        ['Recall (%)', metrics.recall],
        ['FAR (%)', metrics.far],
        ['FRR (%)', metrics.frr],
        ['Avg Latency (ms)', metrics.avg_latency]
    ];

    const worksheet = XLSX.utils.aoa_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Evaluasi Kondisi');
    XLSX.writeFile(workbook, `evaluasi_kondisi_${conditionId}.xlsx`);
}

// Fungsi download PDF
function downloadPDF(conditionId) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const condition = conditionsData.find(c => c.id === conditionId);
    const metrics = conditionMetricsData[conditionId];
    if (!condition || !metrics) {
        alert('Data tidak ditemukan!');
        return;
    }

    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('Hasil Evaluasi Kondisi', 14, 20);
    doc.setFontSize(12);
    doc.setFont('helvetica', 'normal');
    doc.text(condition.name, 14, 30);

    const tableData = [
        ['Parameter', 'Nilai'],
        ['Total Tests', metrics.total_tests],
        ['Correct Predictions', metrics.correct_predictions],
        ['Accuracy (%)', metrics.accuracy],
        ['Precision (%)', metrics.precision],
        ['Recall (%)', metrics.recall],
        ['FAR (%)', metrics.far],
        ['FRR (%)', metrics.frr],
        ['Avg Latency (ms)', metrics.avg_latency]
    ];

    doc.autoTable({
        head: [tableData[0]],
        body: tableData.slice(1),
        startY: 40
    });

    doc.save(`evaluasi_kondisi_${conditionId}.pdf`);
}

// Fungsi download PNG (hanya tabel riwayat dan summary)
function downloadPNG(conditionId) {
    const conditionDiv = document.getElementById(`condition-${conditionId}`);
    const table = conditionDiv.querySelector('table');
    
    if (!table) {
        alert('Elemen tidak ditemukan!');
        return;
    }

    // Buat wrapper sementara untuk styling bagus
    const wrapper = document.createElement('div');
    wrapper.style.padding = '24px';
    wrapper.style.backgroundColor = '#ffffff';
    wrapper.style.borderRadius = '12px';
    wrapper.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
    
    // Clone hanya tabel
    wrapper.appendChild(table.cloneNode(true));
    document.body.appendChild(wrapper);

    html2canvas(wrapper, {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff',
        logging: false
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = `evaluasi_kondisi_${conditionId}.png`;
        link.href = canvas.toDataURL();
        link.click();
        document.body.removeChild(wrapper); // Hapus wrapper sementara
    }).catch(err => {
        console.error(err);
        alert('Gagal download PNG!');
        document.body.removeChild(wrapper);
    });
}

// Overall download dropdown toggle
document.querySelector('.overall-download-btn').addEventListener('click', (e) => {
    e.stopPropagation();
    document.getElementById('overall-download-dropdown').classList.toggle('hidden');
});

// Overall grafik listener
document.querySelector('.view-overall-chart-btn').addEventListener('click', () => {
    const modal = document.getElementById('chartModal');
    const modalTitle = document.getElementById('chartModalTitle');
    const chartCanvas = document.getElementById('metricsChart').getContext('2d');

    modalTitle.textContent = 'Grafik Evaluasi Keseluruhan';
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Buat grafik garis (line chart) dari semua riwayat
    const labels = allEvaluationHistory.map((_, index) => `Test ${index + 1}`);
    const datasets = [
        {
            label: 'Accuracy',
            data: allEvaluationHistory.map(m => m.accuracy),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.3,
            fill: true
        },
        {
            label: 'Precision',
            data: allEvaluationHistory.map(m => m.precision),
            borderColor: '#eab308',
            backgroundColor: 'rgba(234, 179, 8, 0.1)',
            tension: 0.3,
            fill: true
        },
        {
            label: 'Recall',
            data: allEvaluationHistory.map(m => m.recall),
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.3,
            fill: true
        },
        {
            label: 'FAR',
            data: allEvaluationHistory.map(m => m.far),
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.3,
            fill: true
        },
        {
            label: 'FRR',
            data: allEvaluationHistory.map(m => m.frr),
            borderColor: '#a855f7',
            backgroundColor: 'rgba(168, 85, 247, 0.1)',
            tension: 0.3,
            fill: true
        }
    ];

    metricsChart = new Chart(chartCanvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});

// Overall download functions
function downloadOverallExcel() {
    const data = allEvaluationHistory.map(m => [
        m.experiment_log?.actual_identity ?? '-',
        m.experiment_log?.predicted_identity ?? 'Tidak terdeteksi',
        (m.experiment_log?.confidence ?? '-') + '%',
        m.accuracy + '%',
        m.precision + '%',
        m.recall + '%',
        m.far + '%',
        m.frr + '%'
    ]);

    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Actual Identity', 'Predicted Identity', 'Confidence', 'Accuracy', 'Precision', 'Recall', 'FAR', 'FRR'],
        ...data
    ]);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Riwayat Evaluasi');
    XLSX.writeFile(workbook, 'riwayat_evaluasi.xlsx');
}

function downloadOverallPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('Riwayat Evaluasi', 14, 20);

    const tableData = allEvaluationHistory.map(m => [
        m.experiment_log?.actual_identity ?? '-',
        m.experiment_log?.predicted_identity ?? 'Tidak terdeteksi',
        (m.experiment_log?.confidence ?? '-') + '%',
        m.accuracy + '%',
        m.precision + '%',
        m.recall + '%',
        m.far + '%',
        m.frr + '%'
    ]);

    doc.autoTable({
        head: [['Actual Identity', 'Predicted Identity', 'Confidence', 'Accuracy', 'Precision', 'Recall', 'FAR', 'FRR']],
        body: tableData,
        startY: 40
    });

    doc.save('riwayat_evaluasi.pdf');
}

function downloadOverallPNG() {
    const container = document.getElementById('overall-evaluation');
    const table = container.querySelector('table');
    
    if (!table) {
        alert('Elemen tidak ditemukan!');
        return;
    }

    const wrapper = document.createElement('div');
    wrapper.style.padding = '24px';
    wrapper.style.backgroundColor = '#ffffff';
    wrapper.style.borderRadius = '12px';
    wrapper.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
    wrapper.appendChild(table.cloneNode(true));
    document.body.appendChild(wrapper);

    html2canvas(wrapper, {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff',
        logging: false
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'riwayat_evaluasi.png';
        link.href = canvas.toDataURL();
        link.click();
        document.body.removeChild(wrapper);
    }).catch(err => {
        console.error(err);
        alert('Gagal download PNG!');
        document.body.removeChild(wrapper);
    });
}
</script>
@endsection
