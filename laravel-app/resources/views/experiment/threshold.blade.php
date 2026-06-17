@extends('layouts.app')

@section('title', 'Threshold Evaluation')

@section('content')
<!-- Modal untuk Grafik -->
<div id="chartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-4 sm:p-8 max-w-4xl w-full mx-2 sm:mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4 sm:mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900" id="chartModalTitle">Grafik Evaluasi Threshold</h2>
            <button onclick="closeChartModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <div class="h-64 sm:h-96">
            <canvas id="thresholdChart"></canvas>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-3 sm:px-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Evaluasi Threshold</h1>
        <div class="flex flex-wrap gap-3 w-full sm:w-auto">
            <div class="relative flex-1 sm:flex-none">
                <select id="thresholdSelect" class="w-full sm:w-auto border border-orange-500 rounded-lg px-4 py-2 sm:py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm sm:text-base">
                    <option value="all">Semua Threshold</option>
                    @for($i=0; $i<=100; $i+=10)
                        <option value="{{ $i }}">{{ $i }}%</option>
                    @endfor
                    @foreach($uniqueThresholds as $th)
                        @if(!in_array($th, range(0,100,10)))
                            <option value="{{ $th }}">{{ $th }}%</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <button id="hitungThresholdBtn" class="btn-primary text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold flex-1 sm:flex-none w-full sm:w-auto text-xs sm:text-base">
                Hitung Threshold
            </button>
            <button id="lihatGrafikBtn" class="btn-purple text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold flex-1 sm:flex-none w-full sm:w-auto text-xs sm:text-base">
                Grafik
            </button>
            <div class="relative flex-1 sm:flex-none w-full sm:w-auto" id="downloadContainer">
                <button type="button" class="btn-green text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold w-full sm:w-auto text-xs sm:text-base" id="downloadBtn">
                    Download
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-10 hidden border border-orange-100" id="downloadDropdown">
                    <button onclick="downloadExcel()" class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-2m3 2v-2m-9-6h12a2 2 0 012 2v6a2 0 01-2 2H6a2 0 01-2-2v-6a2 0 012-2z"></path></svg>
                            Excel
                        </span>
                    </button>
                    <button onclick="downloadPDF()" class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            PDF
                        </span>
                    </button>
                    <button onclick="downloadPNG()" class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 0 002-2V6a2 0 00-2-2H6a2 0 00-2-2v12a2 0 002z"></path></svg>
                            PNG
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    
    
    <!-- Tabel Evaluasi Threshold -->
    <div class="bg-white rounded-xl card-shadow p-4 sm:p-8 mb-6 sm:mb-8" id="thresholdTableContainer">
        <h2 class="text-lg sm:text-xl font-semibold mb-4">Evaluasi Threshold</h2>
        <div class="responsive-table-container max-h-[240px] sm:max-h-96 overflow-y-auto">
            <table class="w-full min-w-[700px]" id="thresholdTable">
                <thead class="bg-orange-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">No</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Threshold</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Avg Confidence</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Avg Latency</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Accuracy</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Precision</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">Recall</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">FAR</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-orange-800">FRR</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="thresholdTableBody">
                    @foreach($allThresholdMetrics as $index => $metric)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900 font-semibold">{{ $metric['threshold'] }}%</td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric['avg_confidence'] }}%</td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric['avg_latency'] }}ms</td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric['accuracy'] }}%</td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric['precision'] }}%</td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric['recall'] }}%</td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric['far'] }}%</td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">{{ $metric['frr'] }}%</td>
                        </tr>
                    @endforeach
                    @if(empty($allThresholdMetrics))
                        <tr>
                            <td colspan="9" class="px-2 sm:px-4 py-8 sm:py-12 text-center text-xs sm:text-sm text-gray-500">
                                Belum ada data evaluasi threshold
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Hasil Threshold Optimal -->
    <div id="optimalThresholdContainer" class="hidden bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl card-shadow p-4 sm:p-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Threshold Optimal</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4" id="optimalThresholdData">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.31/dist/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
const PYTHON_API_URL = "{{ config('app.python_api_url', env('PYTHON_API_URL', 'http://localhost:5000')) }}";
let allThresholdMetrics = @json($allThresholdMetrics);
let optimalThreshold = null;
let individualLogs = @json($individualLogsJson);
let thresholdChart = null;

// Dropdown download toggle
document.getElementById('downloadBtn').addEventListener('click', (e) => {
    e.stopPropagation();
    document.getElementById('downloadDropdown').classList.toggle('hidden');
});
document.addEventListener('click', (e) => {
    const container = document.getElementById('downloadContainer');
    if (!container.contains(e.target)) {
        document.getElementById('downloadDropdown').classList.add('hidden');
    }
});




// Threshold select change listener
document.getElementById('thresholdSelect').addEventListener('change', (e) => {
    updateThresholdTable();
});




// Hitung Threshold Optimal button
document.getElementById('hitungThresholdBtn').addEventListener('click', async () => {
    const btn = document.getElementById('hitungThresholdBtn');
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Menghitung...
    `;
    
    try {
        let bestScore = -1;
        let bestThreshold = null;
        
        // Cari threshold optimal dengan menghitung score untuk setiap threshold
        allThresholdMetrics.forEach(metric => {
            const score = metric.accuracy + metric.precision + metric.recall - metric.far - metric.frr;
            if (score > bestScore) {
                bestScore = score;
                bestThreshold = metric;
            }
        });
        
        optimalThreshold = bestThreshold;
        updateOptimalThreshold();
        alert('Hitung threshold optimal berhasil!');
    } catch (err) {
        console.error('Error calculating threshold:', err);
        alert('Gagal menghitung threshold!');
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Hitung Threshold Optimal';
    }
});

// Update tabel threshold
function updateThresholdTable() {
    const selectedThreshold = document.getElementById('thresholdSelect').value;
    const tbody = document.getElementById('thresholdTableBody');
    tbody.innerHTML = '';
    
    let filteredMetrics = allThresholdMetrics;
    if (selectedThreshold !== 'all') {
        filteredMetrics = allThresholdMetrics.filter(m => m.threshold === parseInt(selectedThreshold));
    }
    
    if (filteredMetrics.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="px-2 sm:px-4 py-8 sm:py-12 text-center text-xs sm:text-sm text-gray-500">Tidak ada data untuk threshold ini</td></tr>';
        return;
    }
    
    filteredMetrics.forEach((metric, index) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">${index + 1}</td>
            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900 font-semibold">${metric.threshold}%</td>
            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">${metric.avg_confidence}%</td>
            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">${metric.avg_latency}ms</td>
            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">${metric.accuracy}%</td>
            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">${metric.precision}%</td>
            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">${metric.recall}%</td>
            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">${metric.far}%</td>
            <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900">${metric.frr}%</td>
        `;
        tbody.appendChild(row);
    });
}

// Update optimal threshold display
function updateOptimalThreshold() {
    const container = document.getElementById('optimalThresholdContainer');
    const dataDiv = document.getElementById('optimalThresholdData');
    
    if (!optimalThreshold) {
        container.classList.add('hidden');
        return;
    }
    
    container.classList.remove('hidden');
    dataDiv.innerHTML = `
        <div class="text-center p-3 sm:p-4 bg-white rounded-xl shadow-sm">
            <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Threshold</p>
            <p class="text-xl sm:text-2xl font-bold text-orange-600">${optimalThreshold.threshold}%</p>
        </div>
        <div class="text-center p-3 sm:p-4 bg-white rounded-xl shadow-sm">
            <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Accuracy</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600">${optimalThreshold.accuracy}%</p>
        </div>
        <div class="text-center p-3 sm:p-4 bg-white rounded-xl shadow-sm">
            <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Precision</p>
            <p class="text-xl sm:text-2xl font-bold text-purple-600">${optimalThreshold.precision}%</p>
        </div>
        <div class="text-center p-3 sm:p-4 bg-white rounded-xl shadow-sm">
            <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">Recall</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">${optimalThreshold.recall}%</p>
        </div>
        <div class="text-center p-3 sm:p-4 bg-white rounded-xl shadow-sm">
            <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">FAR</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600">${optimalThreshold.far}%</p>
        </div>
        <div class="text-center p-3 sm:p-4 bg-white rounded-xl shadow-sm">
            <p class="text-xs sm:text-sm text-gray-600 mb-1 sm:mb-2">FRR</p>
            <p class="text-xl sm:text-2xl font-bold text-pink-600">${optimalThreshold.frr}%</p>
        </div>
    `;
}

// Chart functions
function closeChartModal() {
    document.getElementById('chartModal').classList.add('hidden');
    document.getElementById('chartModal').classList.remove('flex');
    if (thresholdChart) {
        thresholdChart.destroy();
        thresholdChart = null;
    }
}

function openChartModal() {
    const modal = document.getElementById('chartModal');
    const chartCanvas = document.getElementById('thresholdChart').getContext('2d');
    
    if (allThresholdMetrics.length === 0) {
        alert('Tidak ada data untuk ditampilkan!');
        return;
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Prepare data
    const labels = allThresholdMetrics.map(m => m.threshold + '%');
    const accuracyData = allThresholdMetrics.map(m => m.accuracy);
    const precisionData = allThresholdMetrics.map(m => m.precision);
    const recallData = allThresholdMetrics.map(m => m.recall);
    const farData = allThresholdMetrics.map(m => m.far);
    const frrData = allThresholdMetrics.map(m => m.frr);
    
    thresholdChart = new Chart(chartCanvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Accuracy',
                    data: accuracyData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Precision',
                    data: precisionData,
                    borderColor: '#eab308',
                    backgroundColor: 'rgba(234, 179, 8, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Recall',
                    data: recallData,
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'FAR',
                    data: farData,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'FRR',
                    data: frrData,
                    borderColor: '#a855f7',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
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

document.getElementById('lihatGrafikBtn').addEventListener('click', openChartModal);

// Tutup modal ketika klik di luar
document.getElementById('chartModal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('chartModal')) {
        closeChartModal();
    }
});

// Download functions
function downloadExcel() {
    if (allThresholdMetrics.length === 0 && individualLogs.length === 0) {
        alert('Tidak ada data untuk di-download!');
        return;
    }
    
    // Create workbook with two sheets: individual logs and threshold metrics
    const workbook = XLSX.utils.book_new();
    
    // Sheet 1: Individual Logs
    const logsData = individualLogs.map(log => [
        log.actual_identity,
        log.predicted_identity || '-',
        log.confidence + '%',
        log.threshold + '%',
        log.light_condition || '-',
        log.face_angle || '-',
        log.distance_condition || '-',
        log.is_correct ? 'Ya' : 'Tidak'
    ]);
    const logsSheet = XLSX.utils.aoa_to_sheet([
        ['Actual Identity', 'Predicted Identity', 'Confidence', 'Threshold', 'Light', 'Angle', 'Distance', 'Correct'],
        ...logsData
    ]);
    XLSX.utils.book_append_sheet(workbook, logsSheet, 'Log Training');
    
    // Sheet 2: Threshold Metrics
    const selectedThreshold = document.getElementById('thresholdSelect').value;
    let filteredMetrics = allThresholdMetrics;
    if (selectedThreshold !== 'all') {
        filteredMetrics = allThresholdMetrics.filter(m => m.threshold === parseInt(selectedThreshold));
    }
    const metricsData = filteredMetrics.map(m => [
        m.threshold + '%',
        m.total_tests,
        m.correct_predictions,
        m.accuracy + '%',
        m.precision + '%',
        m.recall + '%',
        m.far + '%',
        m.frr + '%'
    ]);
    const metricsSheet = XLSX.utils.aoa_to_sheet([
        ['Threshold', 'Total Tests', 'Correct', 'Accuracy', 'Precision', 'Recall', 'FAR', 'FRR'],
        ...metricsData
    ]);
    XLSX.utils.book_append_sheet(workbook, metricsSheet, 'Evaluasi Threshold');
    
    XLSX.writeFile(workbook, 'evaluasi_threshold.xlsx');
}

function downloadPDF() {
    if (allThresholdMetrics.length === 0 && individualLogs.length === 0) {
        alert('Tidak ada data untuk di-download!');
        return;
    }
    
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Page 1: Individual Logs
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('Data Log Training', 14, 20);
    
    const logsTableData = individualLogs.map(log => [
        log.actual_identity,
        log.predicted_identity || '-',
        log.confidence + '%',
        log.threshold + '%',
        log.light_condition || '-',
        log.face_angle || '-',
        log.distance_condition || '-',
        log.is_correct ? 'Ya' : 'Tidak'
    ]);
    
    doc.autoTable({
        head: [['Actual Identity', 'Predicted Identity', 'Confidence', 'Threshold', 'Light', 'Angle', 'Distance', 'Correct']],
        body: logsTableData,
        startY: 30,
        styles: {
            fontSize: 7
        }
    });
    
    // Page 2: Threshold Metrics
    doc.addPage();
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('Evaluasi Threshold', 14, 20);
    
    const selectedThreshold = document.getElementById('thresholdSelect').value;
    let filteredMetrics = allThresholdMetrics;
    if (selectedThreshold !== 'all') {
        filteredMetrics = allThresholdMetrics.filter(m => m.threshold === parseInt(selectedThreshold));
    }
    const metricsTableData = filteredMetrics.map(m => [
        m.threshold + '%',
        m.total_tests,
        m.correct_predictions,
        m.accuracy + '%',
        m.precision + '%',
        m.recall + '%',
        m.far + '%',
        m.frr + '%'
    ]);
    
    doc.autoTable({
        head: [['Threshold', 'Total Tests', 'Correct', 'Accuracy', 'Precision', 'Recall', 'FAR', 'FRR']],
        body: metricsTableData,
        startY: 30,
        styles: {
            fontSize: 8
        }
    });
    
    // Add optimal threshold if available
    if (optimalThreshold) {
        doc.addPage();
        doc.setFontSize(16);
        doc.text('Threshold Optimal', 14, 20);
        doc.autoTable({
            head: [['Threshold', 'Accuracy', 'Precision', 'Recall', 'FAR', 'FRR']],
            body: [[
                optimalThreshold.threshold + '%',
                optimalThreshold.accuracy + '%',
                optimalThreshold.precision + '%',
                optimalThreshold.recall + '%',
                optimalThreshold.far + '%',
                optimalThreshold.frr + '%'
            ]],
            startY: 30
        });
    }
    
    doc.save('evaluasi_threshold.pdf');
}

function downloadPNG() {
    const container = document.getElementById('thresholdTableContainer');
    if (!container) {
        alert('Elemen tidak ditemukan!');
        return;
    }
    
    html2canvas(container, {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff'
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'evaluasi_threshold.png';
        link.href = canvas.toDataURL();
        link.click();
    }).catch(err => {
        console.error(err);
        alert('Gagal download PNG!');
    });
}
</script>
@endsection
