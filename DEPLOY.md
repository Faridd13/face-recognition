# Panduan Deploy ke Vercel (Laravel) + Render (Python Backend)

## 1. Deploy Laravel ke Vercel

### Langkah 1: Siapkan Repository
- Push seluruh proyek ke GitHub/GitLab/Bitbucket (pastikan folder `laravel-app` ada di repo)
- Pastikan file `vercel.json` dan `.vercelignore` sudah ada di folder `laravel-app`

### Langkah 2: Buat Project di Vercel
1. Buka [vercel.com](https://vercel.com) dan login
2. Klik **New Project** → Pilih repo kamu
3. Di bagian **Root Directory**, pilih `laravel-app`
4. Klik **Deploy**

### Langkah 3: Atur Environment Variables
Setelah deploy pertama selesai:
1. Buka **Settings** → **Environment Variables**
2. Tambahkan variabel berikut:
   - `APP_NAME`: Face Attendance
   - `APP_ENV`: production
   - `APP_KEY`: base64:Uun/cSs3By52inpI1RU9xNc/KGLYHxb/g79FlepL8Ok= (atau generate baru dengan `php artisan key:generate --show`)
   - `APP_DEBUG`: false
   - `APP_URL`: (URL Vercel kamu, misalnya `https://face-attendance.vercel.app`)
   - `DB_CONNECTION`: mysql
   - `DB_HOST`: (host database production kamu, misalnya dari PlanetScale/Railway)
   - `DB_PORT`: 3306
   - `DB_DATABASE`: (nama database)
   - `DB_USERNAME`: (username database)
   - `DB_PASSWORD`: (password database)
   - `PYTHON_API_URL`: (URL Python backend kamu, misalnya `https://face-attendance-python.onrender.com`)
3. Klik **Save** → Redeploy project

### Langkah 4: Migrasi Database
Untuk menjalankan migrasi di production:
1. Buka **Project Settings** → **Git** → **Integrations** → Install **Vercel CLI**
2. Jalankan di terminal:
   ```bash
   npm i -g vercel
   vercel link
   vercel env pull .env.production
   ```
3. Atur koneksi database di lokal ke database production, lalu jalankan:
   ```bash
   cd laravel-app
   php artisan migrate --seed
   ```

---

## 2. Deploy Python Backend ke Render

### Langkah 1: Siapkan Repository
- Pastikan folder `python-backend` ada di repo kamu
- Pastikan `requirements.txt` dan `api.py` sudah ada

### Langkah 2: Buat Project di Render
1. Buka [render.com](https://render.com) dan login
2. Klik **New +** → **Web Service**
3. Pilih repo kamu
4. Di bagian **Root Directory**, pilih `python-backend`
5. Isi konfigurasi:
   - **Runtime**: Python 3
   - **Build Command**: `pip install -r requirements.txt`
   - **Start Command**: `python api.py`
6. Klik **Create Web Service**

### Langkah 3: Atur Environment Variables di Render
Di bagian **Environment** → tambahkan:
- (jika perlu) variabel koneksi database, sama dengan di Laravel

---

## Catatan Penting
- Vercel untuk Laravel membutuhkan runtime `vercel-php` (sudah diatur di `vercel.json`)
- File storage (gambar wajah, dll.) sebaiknya pakai S3/Cloudinary, karena Vercel storage tidak persisten
- Python backend untuk face recognition butuh server dengan OpenCV support (Render cocok untuk ini)
