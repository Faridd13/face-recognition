# Face Recognition Attendance System

Sistem presensi berbasis pengenalan wajah dengan multi-condition testing untuk keperluan penelitian.

## Struktur Proyek

```
Face-AI/
├── database/              # File database MySQL
│   └── face_attendance.sql # Schema database (opsional, rekomendasi pakai Laravel migration)
├── python-backend/        # Backend Python (Face Recognition: Haar Cascade + LBPH)
│   ├── api.py             # Flask API untuk face recognition
│   ├── config.py          # Konfigurasi database Python
│   ├── face_recognition_system.py  # Logic face recognition utama
│   ├── requirements.txt   # Dependencies Python
│   └── trained_model.yml  # Model LBPH yang sudah dilatih
└── laravel-app/           # Frontend & Backend Laravel 10
    ├── app/
    │   ├── Http/Controllers/  # Semua controller aplikasi
    │   └── Models/            # Semua model (User, Student, Attendance, dll)
    ├── database/
    │   ├── migrations/        # Database migrations Laravel (RECOMMENDED)
    │   └── seeders/           # Database seeders (user default & kondisi)
    └── resources/views/       # Semua Blade template
```

## Fitur Utama

- **Multi-condition testing**: 6 kondisi (pencahayaan: terang/redup, sudut: frontal/nonfrontal, jarak: dekat/jauh)
- **Data Capture**: Capture gambar wajah siswa per kondisi
- **Training & Testing**: Latih dan uji model LBPH
- **Evaluasi Metrik**: Accuracy, Precision, Recall, FAR, FRR, Avg Latency
- **Evaluasi Threshold**: Hitung threshold optimal untuk menentukan keberhasilan pengenalan
- **Anti Duplikat Presensi**: Batas 1 presensi per sesi
- **Role-based Access**: Admin & Guru
- **CRUD Data Siswa**: Tambah, edit, hapus data siswa beserta nomor WhatsApp orang tua
- **Riwayat Presensi**: Lihat, edit status (hadir/sakit/izin/alpha)
- **Kirim WhatsApp**: Kirim notifikasi presensi ke orang tua via WhatsApp
- **Dashboard**: Statistik ringkas untuk admin
- **UI Responsif**: Tampilan bagus di mobile & desktop, tema putih-orange

## Teknologi

- **Backend Python**: OpenCV (Haar Cascade, LBPH), Flask
- **Web Framework**: Laravel 10 (Blade)
- **Database**: MySQL
- **Frontend**: Tailwind CSS

---

## Panduan Setup Lengkap

### 1. Prasyarat
Pastikan kamu sudah install:
- PHP 8.1+
- Composer
- Node.js & npm
- Python 3.8+
- MySQL

### 2. Setup Database MySQL

1. Buat database baru di MySQL:
   ```sql
   CREATE DATABASE face_attendance;
   ```

### 3. Setup Laravel App

1. Masuk ke direktori:
   ```bash
   cd laravel-app
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy file environment (jika belum ada):
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Konfigurasi database di file `.env` (pastikan sesuai dengan MySQL kamu):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=face_attendance
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Jalankan migration & seeder untuk membuat tabel dan mengisi data awal (user + kondisi):
   ```bash
   php artisan migrate:fresh --seed
   ```

7. Install & build frontend dependencies:
   ```bash
   npm install
   npm run build
   ```

8. Jalankan Laravel development server:
   ```bash
   php artisan serve
   ```
   Server akan berjalan di `http://127.0.0.1:8000`

### 4. Setup Python Backend

1. Buka terminal baru, masuk ke direktori:
   ```bash
   cd python-backend
   ```

2. Install dependencies Python (disarankan pakai virtual environment):
   ```bash
   pip install -r requirements.txt
   ```

3. Cek konfigurasi database di `config.py`, pastikan sesuai:
   ```python
   DB_CONFIG = {
       'host': '127.0.0.1',
       'user': 'root',
       'password': '',
       'database': 'face_attendance'
   }
   ```

4. Jalankan Flask API server:
   ```bash
   python api.py
   ```
   Server akan berjalan di `http://localhost:5000`

---

## Default Login Akun

Setelah menjalankan `php artisan migrate:fresh --seed`, kamu bisa login dengan:

- **Admin**:
  - Email: `admin123@gmail.com`
  - Password: `adminnyaganteng`

- **Guru**:
  - Email: `gurufarid@gmail.com`
  - Password: `faridtampan`

---

## Alur Penelitian / Penggunaan

1. **Login sebagai Admin**
2. **Tambah Siswa**: Di menu "Siswa", tambahkan data siswa (NIS, Nama, Kelas, Nomor WhatsApp Orang Tua)
3. **Capture Data**: Di menu "Experiment > Capture Data", pilih siswa dan kondisi, lalu capture gambar wajah
4. **Train Model**: Di menu "Experiment > Train Model", latih model LBPH dengan semua data yang sudah di-capture
5. **Test Model**: Di menu "Experiment > Test Model", uji model untuk setiap kondisi
6. **Lihat Logs**: Di menu "Experiment > Logs", lihat semua hasil testing
7. **Hitung Metrik**: Di menu "Experiment > Metrics", hitung dan lihat evaluasi metrik keseluruhan
8. **Hitung Threshold**: Di menu "Experiment > Threshold", hitung threshold optimal
9. **Presensi**: Di menu "Presensi", lakukan presensi via Face Recognition atau tambah manual
10. **Kirim WhatsApp**: Di halaman presensi, kirim notifikasi ke orang tua

---

## Panduan Deploy ke Production

Untuk deploy ke Vercel (Laravel) + Render (Python Backend), lihat panduan lengkap di [DEPLOY.md](./DEPLOY.md)

---

## Catatan Penting

- Pastikan **kedua server berjalan** (Laravel di port 8000 & Python Flask di port 5000)
- Pastikan **webcam terdeteksi** dengan baik di browser
- Untuk hasil optimal, pastikan **pencahayaan memadai** saat capture dan testing
- Jika ingin reset database, jalankan `php artisan migrate:fresh --seed` di direktori `laravel-app`
