# Face Recognition Attendance System

Sistem presensi berbasis pengenalan wajah dengan multi-condition testing untuk keperluan penelitian.

## Struktur Proyek

```
Face-AI/
├── database/          # File database MySQL
├── python-backend/    # Backend Python (Face Recognition: Haar Cascade + LBPH)
└── laravel-app/       # Frontend & Backend Laravel
```

## Fitur Utama

- **Multi-condition testing**: 6 kondisi (pencahayaan: terang/normal/gelap, sudut: depan/kiri/kanan, jarak: dekat/sedang/jauh)
- **Data Capture**: 5 gambar per kondisi, total 30 gambar per siswa
- **Training & Testing**: 5x training dan 5x testing per siswa (total 100 data testing untuk 20 siswa)
- **Evaluasi Metrik**: Accuracy, Precision, Recall, FAR, FRR, Latency
- **Anti Duplikat Presensi**: Batas 1 presensi per sesi
- **Role-based Access**: Admin & Guru
- **CRUD Data Siswa**
- **Riwayat Presensi** (edit status: hadir/sakit/izin/alpha)
- **Dashboard & Reports**
- **UI Modern**: Tema putih-orange seperti Jibble

## Teknologi

- **Backend Python**: OpenCV (Haar Cascade, LBPH), Flask
- **Web Framework**: Laravel 10
- **Database**: MySQL
- **Frontend**: Tailwind CSS

---

## Panduan Setup

### 1. Database Setup

1. Buat database MySQL baru:
   ```sql
   CREATE DATABASE face_attendance;
   ```
2. Import schema:
   ```bash
   mysql -u root -p face_attendance < database/schema.sql
   ```

### 2. Python Backend Setup

1. Masuk ke direktori:
   ```bash
   cd python-backend
   ```
2. Install dependencies:
   ```bash
   pip install -r requirements.txt
   ```
3. Edit konfigurasi database di `config.py` jika perlu
4. Jalankan Flask server:
   ```bash
   python api.py
   ```
   Server akan berjalan di `http://localhost:5000`

### 3. Laravel Setup

1. Masuk ke direktori:
   ```bash
   cd laravel-app
   ```
2. Install Laravel (pastikan Composer sudah terinstall):
   ```bash
   composer install
   ```
3. Copy file environment:
   ```bash
   cp .env.example .env
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Konfigurasi database di `.env`:
   ```
   DB_DATABASE=face_attendance
   DB_USERNAME=root
   DB_PASSWORD=
   ```
6. Install Laravel Breeze untuk authentication:
   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install blade
   npm install && npm run dev
   ```
7. Jalankan Laravel server:
   ```bash
   php artisan serve
   ```

### 4. Default Login

- **Admin**:
  - Email: `admin@example.com`
  - Password: `password` (lihat di schema.sql)
- **Guru**:
  - Email: `guru@example.com`
  - Password: `password`

---

## Alur Penelitian

1. **Tambah Siswa**: Masukkan 20 siswa ke sistem
2. **Capture Data**: Untuk setiap siswa, capture 5 gambar per kondisi (total 6 kondisi)
3. **Training**: Latih model LBPH dengan semua data
4. **Testing**: Setiap siswa melakukan testing 5x (total 100 test)
5. **Hitung Metrik**: Lihat hasil evaluasi di menu Experiment > Metrics

---

## Catatan Penting

- Pastikan webcam terdeteksi dengan baik
- Sesuaikan konfigurasi database di kedua sistem (Python & Laravel)
- Untuk hasil optimal, pastikan pencahayaan memadai saat capture dan testing
