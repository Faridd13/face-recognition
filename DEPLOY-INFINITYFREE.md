# Panduan Deploy ke InfinityFree

InfinityFree adalah hosting free PHP + MySQL yang cocok untuk Laravel.

---

## Langkah 1: Buat Akun & Hosting di InfinityFree

1. Buka [infinityfree.net](https://infinityfree.net) dan daftar/login
2. Klik **Create Account**
3. Isi nama subdomain (misalnya `faceattendance.infinityfreeapp.com`)
4. Klik **Create Account**

Tunggu beberapa menit sampai akun aktif!

---

## Langkah 2: Siapkan Laravel untuk Production

1. Buka `laravel-app/.env`
2. Ubah konfigurasi:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://faceattendance.infinityfreeapp.com (ganti dengan domain kamu)
   ```
3. Build assets (CSS/JS):
   ```bash
   cd laravel-app
   npm install
   npm run build
   ```

---

## Langkah 3: Upload File ke InfinityFree

### Cara 1: Pakai File Manager (Mudah)
1. Di InfinityFree Dashboard, klik **Control Panel** (CPanel)
2. Klik **File Manager**
3. Buka folder `htdocs`
4. Upload **seluruh isi folder `laravel-app`** ke `htdocs` (tidak usah folder `laravel-app`nya, langsung file-filenya)

### Cara 2: Pakai FTP (FileZilla)
1. Di InfinityFree Dashboard, lihat **FTP Details**:
   - Host: misalnya `ftpupload.net`
   - Username: misalnya `epiz_12345678`
   - Password: password hosting kamu
2. Download dan buka [FileZilla](https://filezilla-project.org)
3. Masukkan detail FTP dan connect
4. Upload seluruh isi folder `laravel-app` ke folder `htdocs`

---

## Langkah 4: Ubah Konfigurasi untuk Public Folder

Karena di InfinityFree, root folder adalah `htdocs`, tapi Laravel punya folder `public`:

1. Di `htdocs`, **pindahkan semua isi folder `public` ke `htdocs`** (file `index.php`, `.htaccess`, dll.)
2. Edit file `htdocs/index.php`:
   - Ubah `__DIR__.'/../vendor/autoload.php'` menjadi `__DIR__.'/vendor/autoload.php'`
   - Ubah `__DIR__.'/../bootstrap/app.php'` menjadi `__DIR__.'/bootstrap/app.php'`
3. Buat file `.htaccess` di `htdocs` (jika belum ada):
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteRule ^ index.php [L]
   </IfModule>
   ```

---

## Langkah 5: Buat & Konfigurasi Database

1. Di InfinityFree Control Panel, klik **MySQL Databases**
2. Buat database baru (nama database akan seperti `epiz_12345678_face_attendance`)
3. Catat:
   - Database Host: misalnya `sql105.infinityfree.com`
   - Database Name: nama database kamu
   - Database Username: sama dengan username FTP
   - Database Password: password hosting kamu
4. Buka **phpMyAdmin** (di Control Panel)
5. Import file `database/face_attendance.sql` (atau jalankan migrasi via Laravel)

---

## Langkah 6: Update Environment Variables

Kamu bisa edit file `htdocs/.env` langsung via File Manager:
```env
APP_NAME=Face Attendance
APP_ENV=production
APP_KEY=base64:Uun/cSs3By52inpI1RU9xNc/KGLYHxb/g79FlepL8Ok=
APP_DEBUG=false
APP_URL=https://faceattendance.infinityfreeapp.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=sql105.infinityfree.com (ganti dengan host kamu)
DB_PORT=3306
DB_DATABASE=epiz_12345678_face_attendance (ganti dengan nama database kamu)
DB_USERNAME=epiz_12345678 (ganti dengan username kamu)
DB_PASSWORD=password_kamu (ganti dengan password kamu)

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

---

## Langkah 7: Jalankan Migrasi (Opsional)

Jika kamu ingin jalankan migrasi Laravel (bukan import SQL):
1. Buat file `htdocs/migrate.php`:
   ```php
   <?php
   require __DIR__.'/vendor/autoload.php';
   $app = require_once __DIR__.'/bootstrap/app.php';
   $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
   
   echo "Running migrations...<br>";
   \Artisan::call('migrate:fresh', ['--seed' => true]);
   echo \Artisan::output();
   
   echo "<br><br>Migrations done!";
   echo "<br><b>DELETE FILE migrate.php NOW!</b>";
   ?>
   ```
2. Buka `https://domainkamu.infinityfreeapp.com/migrate.php` di browser
3. **HAPUS file `migrate.php` setelah selesai!** (untuk keamanan)

---

## Selesai! 🎉

Sekarang website kamu sudah online di InfinityFree!
