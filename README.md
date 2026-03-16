# Clipfluence

Clipfluence adalah platform berbasis web yang dibangun menggunakan **Laravel 12** dan **TailwindCSS 4**.

## Prasyarat

Sebelum menginstal dan menjalankan website ini, pastikan sistem Anda telah terinstal:
- **PHP** >= 8.2
- **Composer** (untuk dependensi backend PHP)
- **Node.js** dan **npm** (untuk dependensi frontend JS/CSS)
- Server Database (MySQL / MariaDB / SQLite / PostgreSQL dll). Penggunaan **Laragon** sangat disarankan jika Anda menggunakan Windows.

---

## Langkah-Langkah Menjalankan Website (Lokal)

Ikuti langkah-langkah di bawah ini untuk mengonfigurasi dan menjalankan project Clipfluence di komputer Anda:

### 1. Kloning Repository (Opsional)
Jika Anda mengambil source code melalui Git, lakukan cloning:
```bash
git clone https://github.com/hafisc/clipfluence.git
cd clipfluence
```
*(Lewati langkah ini jika Anda sudah berada di dalam folder source code)*

### 2. Instalasi Dependensi PHP (Backend)
Jalankan perintah Composer di terminal untuk menginstal kerangka kerja Laravel dan package pihak ketiga lainnya:
```bash
composer install
```

### 3. Instalasi Dependensi NPM (Frontend)
Jalankan NPM untuk menginstal library Javascript, Vite, dan Tailwind CSS:
```bash
npm install
```

### 4. Konfigurasi Environment (`.env`)
Laravel membutuhkan file `.env` untuk pengaturan dasar (seperti database). Anda perlu menyalinnya dari file contoh:
```bash
cp .env.example .env
```
*(Pengguna Windows di Command Prompt bisa menggunakan `copy .env.example .env` atau dapat men-copy paste file secara manual).*

Setelah `.env` dibuat, **buka file tersebut** dan sesuaikan baris *Database* (biasanya di baris-baris awal). Contoh penggunaan MySQL dengan Laragon standar:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clipfluence
DB_USERNAME=root
DB_PASSWORD=
```
> **Catatan:** Pastikan Anda sudah membuat database kosong bernama `clipfluence` pada HeidiSQL / phpMyAdmin agar aplikasi bisa terkoneksi dengan sukses. Jika ingin langkah yang instan, ubah `DB_CONNECTION=sqlite` dan hapus konfigurasi koneksi DB lainnya.

### 5. Generate Application Key
Lakukan generate kunci keamanan utama aplikasi Laravel dengan perintah:
```bash
php artisan key:generate
```

### 6. Migrasi Database
Buat dan susun tabel-tabel di database (tabel User, dll) menggunakan fitur migrasi Laravel dengan perintah:
```bash
php artisan migrate
```
*(Bila ada prompt/konfirmasi pembuatan database saat menjalankan migrasi, ketik `yes`)*

### 7. Menjalankan Server Pengembangan (Dev Server)
Karena project ini menggunakan Vite untuk kompilasi CSS (Tailwind) dan Javascript, Anda perlu menjalankan server backend Laravel dan server frontend Vite secara bersamaan.

Di Laravel versi 11 ke atas yang menggunakan `concurrently`, cukup jalankan 1 perintah ini saja di terminal:
```bash
composer run dev
```

**ATAU (Cara Alternatif):**
Bila Anda perlu membuka **dua (2)** tab Terminal pada folder project dan menjalankan server secara terpisah:

- **Terminal 1** (untuk Backend):
  ```bash
  php artisan serve
  ```
- **Terminal 2** (untuk Frontend):
  ```bash
  npm run dev
  ```

### 8. Selesai 🎉
Buka browser dan kunjungi: **http://127.0.0.1:8000** atau **http://localhost:8000**.
Jika Anda menggunakan fitur Auto Virtual Hosts Laragon, Anda juga bisa langsung mengakses alamat **http://clipfluence.test**.

---

## Tumpukan Teknologi (Tech Stack)
- **Framework Utama:** [Laravel 12](https://laravel.com/)
- **Frontend / Styling:** [Tailwind CSS 4](https://tailwindcss.com/)
- **Bundler:** [Vite](https://vitejs.dev/)
- **HTTP Client (AJAX):** [Axios](https://axios-http.com/)
