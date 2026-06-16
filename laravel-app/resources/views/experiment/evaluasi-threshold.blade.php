@extends('layouts.app')

@section('title', 'Evaluasi Threshold')

@section('content')
<div class="max-w-7xl mx-auto px-4 pt-20">
    <!-- Evaluasi Threshold -->
    <div id="evaluasi-threshold" class="bg-white rounded-xl card-shadow p-8 mb-8">
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-semibold">Evaluasi Threshold</h2>
            <div class="flex flex-wrap gap-2 items-center">
                <select id="threshold-range-select" class="bg-gradient-to-r from-red-500 to-red-700 text-white border-0 rounded-xl px-5 py-3 font-semibold focus:ring-4 focus:ring-red-300 transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer">
                    <option value="all" class="text-gray-900 bg-white">Semua Threshold</option>
                    <option value="0" class="text-gray-900 bg-white">0</option>
                    <option value="10" class="text-gray-900 bg-white">10</option>
                    <option value="20" class="text-gray-900 bg-white">20</option>
                    <option value="30" class="text-gray-900 bg-white">30</option>
                    <option value="40" class="text-gray-900 bg-white">40</option>
                    <option value="50" class="text-gray-900 bg-white">50</option>
                    <option value="60" class="text-gray-900 bg-white">60</option>
                    <option value="70" class="text-gray-900 bg-white">70</option>
                    <option value="80" class="text-gray-900 bg-white">80</option>
                    <option value="90" class="text-gray-900 bg-white">90</option>
                    <option value="100" class="text-gray-900 bg-white">100</option>
                </select>
                <button id="calculate-threshold-btn" class="text-white px-4 py-3 rounded-lg font-semibold hover:shadow-xl transition-all duration-300 hover:-translate-y-1" style="background-color: #f97316;">
                    Hitung Evaluasi Threshold
                </button>
                <button id="view-threshold-chart-btn" class="text-white px-4 py-3 rounded-lg font-semibold hover:shadow-xl transition-all duration-300 hover:-translate-y-1" style="background-color: #7c3aed;">
                    Lihat Grafik
                </button>
                <div class="relative" id="threshold-download-container">
                    <button type="button" id="threshold-download-btn" class="text-white px-4 py-3 rounded-lg font-semibold hover:shadow-xl transition-all duration-300 hover:-translate-y-1" style="background-color: #16a34a;">
                        Download
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden" id="threshold-dropdown">
                        <button onclick="downloadThresholdExcel()" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Excel</button>
                        <button onclick="downloadThresholdPDF()" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PDF</button>
                        <button onclick="downloadThresholdPNG()" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PNG</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container untuk menampilkan hasil threshold -->
        <div id="threshold-results-container" class="hidden">
            <!-- Tabel threshold -->
            <div class="overflow-x-auto max-h-[320px] overflow-y-auto mb-6" id="threshold-table-container">
                <table class="w-full">
                    <thead class="bg-orange-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-orange-800">Threshold</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-orange-800">Total Tests</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-orange-800">Correct Predictions</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-orange-800">Accuracy</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-orange-800">Precision</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-orange-800">Recall</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-orange-800">FAR</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-orange-800">FRR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="threshold-table-body">
                    </tbody>
                </table>
            </div>

            <!-- Rekomendasi Optimal Threshold -->
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-6" id="optimal-threshold-container">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Rekomendasi Threshold Optimal</h3>
                <div id="optimal-threshold-content"></div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Threshold Chart -->
    <div id="threshold-chart-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Grafik Evaluasi Threshold</h2>
                <button onclick="closeThresholdChartModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="h-96">
                <canvas id="threshold-chart"></canvas>
            </div>
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

// Variabel untuk threshold
let allThresholdMetrics = [];
let optimalThresholdData = null;
let selectedThresholdMetrics = null;
let thresholdChart = null;

// Dropdown Download Threshold
const thresholdDownloadBtn = document.getElementById('threshold-download-btn');
if (thresholdDownloadBtn) {
    thresholdDownloadBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const dropdown = document.getElementById('threshold-dropdown');
        dropdown.classList.toggle('hidden');
    });
}

// Tutup dropdown ketika klik di luar
document.addEventListener('click', (e) => {
    const dropdown = document.getElementById('threshold-dropdown');
    if (!document.getElementById('threshold-download-container').contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});

// Event listener untuk hitung threshold
const calculateThresholdBtn = document.getElementById('calculate-threshold-btn');
if (calculateThresholdBtn) {
    calculateThresholdBtn.addEventListener('click', async () => {
        calculateThresholdBtn.disabled = true;
        calculateThresholdBtn.innerHTML = `
            <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Menghitung...
        `;

        const thresholdRange = document.getElementById('threshold-range-select').value;
        const params = { threshold_range: thresholdRange };

        try {
            const response = await fetch("{{ route('experiment.metrics.calculate-threshold') }}?" + new URLSearchParams(params), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (result.status === 'success') {
                allThresholdMetrics = result.all_threshold_metrics;
                optimalThresholdData = result.optimal_threshold;

                // Tampilkan tabel dan hasil
                renderThresholdTable();
                renderOptimalThreshold();
                document.getElementById('threshold-results-container').classList.remove('hidden');
            } else {
                alert('Gagal menghitung evaluasi threshold: ' + (result.message || 'Unknown error'));
            }
        } catch (err) {
            console.error('Error calculating threshold:', err);
            alert('Gagal terhubung ke server!');
        } finally {
            calculateThresholdBtn.disabled = false;
            calculateThresholdBtn.innerHTML = 'Hitung Evaluasi Threshold';
        }
    });
}

// Render tabel threshold
function renderThresholdTable() {
    const tbody = document.getElementById('threshold-table-body');
    tbody.innerHTML = '';
    
    allThresholdMetrics.forEach(metric => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="px-4 py-3 text-gray-900">${metric.threshold}</td>
            <td class="px-4 py-3 text-gray-900">${metric.total_tests}</td>
            <td class="px-4 py-3 text-gray-900">${metric.correct_predictions}</td>
            <td class="px-4 py-3 text-gray-900">${metric.accuracy}%</td>
            <td class="px-4 py-3 text-gray-900">${metric.precision}%</td>
            <td class="px-4 py-3 text-gray-900">${metric.recall}%</td>
            <td class="px-4 py-3 text-gray-900">${metric.far}%</td>
            <td class="px-4 py-3 text-gray-900">${metric.frr}%</td>
        `;
        tbody.appendChild(row);
    });
}

// Render optimal threshold
function renderOptimalThreshold() {
    const container = document.getElementById('optimal-threshold-content');
    container.innerHTML = `
        <div class="grid sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="text-center p-3 bg-orange-50 rounded-lg border border-orange-100">
                <p class="text-xs text-gray-600 mb-1">Threshold Optimal</p>
                <p class="text-xl font-bold text-orange-600">${optimalThresholdData.threshold}</p>
            </div>
            <div class="text-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                <p class="text-xs text-gray-600 mb-1">Accuracy</p>
                <p class="text-xl font-bold text-blue-600">${optimalThresholdData.accuracy}%</p>
            </div>
            <div class="text-center p-3 bg-purple-50 rounded-lg border border-purple-100">
                <p class="text-xs text-gray-600 mb-1">Precision</p>
                <p class="text-xl font-bold text-purple-600">${optimalThresholdData.precision}%</p>
            </div>
            <div class="text-center p-3 bg-pink-50 rounded-lg border border-pink-100">
                <p class="text-xs text-gray-600 mb-1">Recall</p>
                <p class="text-xl font-bold text-pink-600">${optimalThresholdData.recall}%</p>
            </div>
            <div class="text-center p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                <p class="text-xs text-gray-600 mb-1">FAR</p>
                <p class="text-xl font-bold text-yellow-600">${optimalThresholdData.far}%</p>
            </div>
            <div class="text-center p-3 bg-red-50 rounded-lg border border-red-100">
                <p class="text-xs text-gray-600 mb-1">FRR</p>
                <p class="text-xl font-bold text-red-600">${optimalThresholdData.frr}%</p>
            </div>
        </div>
    `;
}

// Fungsi untuk threshold chart
const viewThresholdChartBtn = document.getElementById('view-threshold-chart-btn');
if (viewThresholdChartBtn) {
    viewThresholdChartBtn.addEventListener('click', () => {
        if (!allThresholdMetrics.length) {
            alert('Silakan hitung evaluasi threshold terlebih dahulu!');
            return;
        }
        openThresholdChartModal();
    });
}

function openThresholdChartModal() {
    const modal = document.getElementById('threshold-chart-modal');
    const chartCanvas = document.getElementById('threshold-chart').getContext('2d');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    const labels = allThresholdMetrics.map(m => m.threshold);
    const colors = {
        accuracy: '#ef4444',
        precision: '#eab308',
        recall: '#22c55e',
        far: '#3b82f6',
        frr: '#a855f7'
    };

    thresholdChart = new Chart(chartCanvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Accuracy (%)',
                    data: allThresholdMetrics.map(m => m.accuracy),
                    borderColor: colors.accuracy,
                    backgroundColor: colors.accuracy + '20',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Precision (%)',
                    data: allThresholdMetrics.map(m => m.precision),
                    borderColor: colors.precision,
                    backgroundColor: colors.precision + '20',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Recall (%)',
                    data: allThresholdMetrics.map(m => m.recall),
                    borderColor: colors.recall,
                    backgroundColor: colors.recall + '20',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'FAR (%)',
                    data: allThresholdMetrics.map(m => m.far),
                    borderColor: colors.far,
                    backgroundColor: colors.far + '20',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'FRR (%)',
                    data: allThresholdMetrics.map(m => m.frr),
                    borderColor: colors.frr,
                    backgroundColor: colors.frr + '20',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
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

function closeThresholdChartModal() {
    document.getElementById('threshold-chart-modal').classList.add('hidden');
    document.getElementById('threshold-chart-modal').classList.remove('flex');
    if (thresholdChart) {
        thresholdChart.destroy();
        thresholdChart = null;
    }
}

// Tutup modal ketika klik di luar
document.getElementById('threshold-chart-modal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('threshold-chart-modal')) {
        closeThresholdChartModal();
    }
});

// Fungsi download Excel threshold
function downloadThresholdExcel() {
    if (!allThresholdMetrics.length) {
        alert('Silakan hitung evaluasi threshold terlebih dahulu!');
        return;
    }

    const data = [
        ['Threshold', 'Total Tests', 'Correct Predictions', 'Accuracy', 'Precision', 'Recall', 'FAR', 'FRR']
    ];

    allThresholdMetrics.forEach(m => {
        data.push([
            m.threshold,
            m.total_tests,
            m.correct_predictions,
            m.accuracy + '%',
            m.precision + '%',
            m.recall + '%',
            m.far + '%',
            m.frr + '%'
        ]);
    });

    const worksheet = XLSX.utils.aoa_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Evaluasi Threshold');
    XLSX.writeFile(workbook, 'evaluasi_threshold.xlsx');
}

// Fungsi download PDF threshold
function downloadThresholdPDF() {
    if (!allThresholdMetrics.length) {
        alert('Silakan hitung evaluasi threshold terlebih dahulu!');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('Hasil Evaluasi Threshold', 14, 20);

    const tableData = [
        ['Threshold', 'Total Tests', 'Correct Predictions', 'Accuracy', 'Precision', 'Recall', 'FAR', 'FRR']
    ];

    allThresholdMetrics.forEach(m => {
        tableData.push([
            m.threshold,
            m.total_tests,
            m.correct_predictions,
            m.accuracy + '%',
            m.precision + '%',
            m.recall + '%',
            m.far + '%',
            m.frr + '%'
        ]);
    });

    doc.autoTable({
        head: [tableData[0]],
        body: tableData.slice(1),
        startY: 30,
        styles: {
            fontSize: 8
        }
    });

    doc.save('evaluasi_threshold.pdf');
}

// Fungsi download PNG threshold
function downloadThresholdPNG() {
    const container = document.getElementById('evaluasi-threshold');
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