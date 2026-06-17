@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 animate-fadeIn">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 sm:mb-8 gap-4">
        <div class="animate-slideInLeft text-center sm:text-left">
            <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2">Riwayat Presensi</h1>
            <p class="text-gray-600">Lihat semua catatan presensi siswa</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 animate-slideInRight w-full sm:w-auto">
            <a href="{{ route('attendance.face') }}" class="btn-primary text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold text-center flex-1 sm:flex-none text-xs sm:text-base">
                📸 Face Recognition
            </a>
            <a href="{{ route('attendance.create') }}" class="btn-purple text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold text-center flex-1 sm:flex-none text-xs sm:text-base">
                + Tambah Manual
            </a>
            <button id="sendWhatsAppBtn" class="btn-green text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold text-center flex-1 sm:flex-none text-xs sm:text-base">
                📱 Kirim Semua
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl card-shadow overflow-hidden animate-slideInUp" style="animation-delay: 0.1s;">
        <div class="responsive-table-container max-h-[240px] sm:max-h-[320px] overflow-y-auto">
            <table class="w-full min-w-[500px]">
                <thead class="bg-orange-50">
                    <tr>
                        <th class="px-2 sm:px-4 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-orange-600 rounded">
                        </th>
                        <th class="px-2 sm:px-4 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">Siswa</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider hidden sm:table-cell">Tanggal</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">Waktu</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">Status</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-4 text-left text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider hidden lg:table-cell">Lokasi</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-4 text-center text-xs sm:text-sm font-semibold text-orange-800 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($attendances as $attendance)
                        <tr class="hover:bg-orange-50 transition-colors duration-200 animate-fadeIn" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                            <td class="px-2 sm:px-4 py-2 sm:py-4">
                                @if($attendance->student->parent_whatsapp)
                                    <input type="checkbox" class="attendance-checkbox w-4 h-4 text-orange-600 rounded" data-student-id="{{ $attendance->student->id }}" data-student-name="{{ $attendance->student->name }}" data-whatsapp-number="{{ $attendance->student->parent_whatsapp }}" data-date="{{ optional($attendance->attendance_date)->format('d M Y') }}" data-status="{{ $attendance->status }}">
                                @endif
                            </td>
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
                            <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-center">
                                <span class="px-2 sm:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $attendance->status === 'hadir' ? 'bg-green-100 text-green-700' : ($attendance->status === 'sakit' ? 'bg-yellow-100 text-yellow-700' : ($attendance->status === 'izin' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')) }}">
                                    {{ $attendance->status }}
                                </span>
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap hidden lg:table-cell">
                                <div class="text-xs sm:text-sm text-gray-900 truncate max-w-[150px]">{{ $attendance->location ?? '-' }}</div>
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-4">
                                <div class="flex flex-wrap gap-2 justify-center">
                                    <a href="{{ route('attendance.edit', $attendance) }}" class="text-blue-600 hover:text-blue-800 px-2 sm:px-3 py-1 rounded-lg hover:bg-blue-50 transition-all duration-200 font-medium text-center text-xs sm:text-sm">Edit</a>
                                    @if($attendance->student->parent_whatsapp)
                                        <button onclick="sendAttendanceWhatsApp({{ $attendance->student->id }}, '{{ $attendance->student->name }}', '{{ $attendance->student->parent_whatsapp }}', '{{ optional($attendance->attendance_date)->format('d M Y') }}', '{{ $attendance->status }}')" class="text-green-600 hover:text-green-800 px-2 sm:px-3 py-1 rounded-lg hover:bg-green-50 transition-all duration-200 font-medium text-center text-xs sm:text-sm">Kirim</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Kirim WhatsApp -->
<div id="whatsAppModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 animate-slideInUp">
        <h2 class="text-xl font-bold mb-4 text-gray-900">Kirim Pesan WhatsApp</h2>
        <form id="whatsAppForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Siswa</label>
                <input type="text" id="modalStudentName" readonly class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                <input type="text" id="modalWhatsAppNumber" readonly class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                <textarea id="modalMessage" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200" placeholder="Tulis pesan di sini..."></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal()" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200 font-medium">Batal</button>
                <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
    let allAttendances = @json($attendances);
    const sendBtn = document.getElementById('sendWhatsAppBtn');

    // Fungsi untuk update teks tombol
    function updateSendButtonText() {
        const checkedCount = document.querySelectorAll('.attendance-checkbox:checked').length;
        const totalCount = document.querySelectorAll('.attendance-checkbox').length;
        
        if (checkedCount === totalCount || checkedCount === 0) {
            sendBtn.innerHTML = '📱 Kirim Semua';
        } else {
            sendBtn.innerHTML = `📱 Kirim ${checkedCount} yang Dipilih`;
        }
    }

    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.attendance-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSendButtonText();
    });

    // Update teks tombol ketika checkbox berubah
    document.querySelectorAll('.attendance-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSendButtonText);
    });

    // Send button click
    sendBtn.addEventListener('click', function() {
        const selectedCheckboxes = document.querySelectorAll('.attendance-checkbox:checked');
        let checkboxesToSend;
        
        if (selectedCheckboxes.length === 0) {
            checkboxesToSend = document.querySelectorAll('.attendance-checkbox');
            if (checkboxesToSend.length === 0) {
                alert('Tidak ada data presensi yang dapat dikirim!');
                return;
            }
        } else {
            checkboxesToSend = selectedCheckboxes;
        }
        
        sendMultipleWhatsApp(checkboxesToSend);
    });

    function sendMultipleWhatsApp(checkboxes) {
        checkboxes.forEach((checkbox, index) => {
            setTimeout(() => {
                const studentName = checkbox.dataset.studentName;
                const whatsappNumber = checkbox.dataset.whatsappNumber.replace(/\D/g, '');
                const date = checkbox.dataset.date;
                const status = checkbox.dataset.status;

                let statusText = '';
                if (status === 'hadir') {
                    statusText = 'Hadir';
                } else if (status === 'sakit') {
                    statusText = 'Sakit';
                } else if (status === 'izin') {
                    statusText = 'Izin';
                } else {
                    statusText = 'Alpha';
                }

                const message = `Assalamualaikum Wr. Wb.

Yth. Bapak/Ibu Orang Tua/Wali dari ${studentName},

Kami informasikan bahwa putra/putri Anda ${statusText} pada tanggal ${date}.

Terima kasih.`;
                const url = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
                window.open(url, '_blank');
            }, index * 500); // Jeda 500ms per pesan
        });
    }

    function sendAttendanceWhatsApp(studentId, studentName, whatsappNumber, date, status) {
        document.getElementById('modalStudentName').value = studentName;
        document.getElementById('modalWhatsAppNumber').value = whatsappNumber;
        
        let statusText = '';
        if (status === 'hadir') {
            statusText = 'Hadir';
        } else if (status === 'sakit') {
            statusText = 'Sakit';
        } else if (status === 'izin') {
            statusText = 'Izin';
        } else {
            statusText = 'Alpha';
        }
        
        document.getElementById('modalMessage').value = `Assalamualaikum Wr. Wb.

Yth. Bapak/Ibu Orang Tua/Wali dari ${studentName},

Kami informasikan bahwa putra/putri Anda ${statusText} pada tanggal ${date}.

Terima kasih.`;
        
        document.getElementById('whatsAppModal').classList.remove('hidden');
        document.getElementById('whatsAppModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('whatsAppModal').classList.add('hidden');
        document.getElementById('whatsAppModal').classList.remove('flex');
    }

    document.getElementById('whatsAppForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const whatsappNumber = document.getElementById('modalWhatsAppNumber').value.replace(/\D/g, '');
        const message = encodeURIComponent(document.getElementById('modalMessage').value);
        const url = `https://wa.me/${whatsappNumber}?text=${message}`;
        window.open(url, '_blank');
        closeModal();
    });

    // Close modal when clicking outside
    document.getElementById('whatsAppModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endsection
