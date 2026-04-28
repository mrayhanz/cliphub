# ClipHub

ClipHub adalah aplikasi web berbasis **Laravel 12** dan **Tailwind CSS 4** untuk menghubungkan **Brand** dengan **Kreator** melalui campaign berbasis submission, klaim views, reward, wallet, dan fitur **AI Auto-Clipper**.

## Fitur Utama

### Admin
- Dashboard operasional.
- Manajemen pengguna, kreator, dan brand.
- Manajemen campaign.
- Review submission kreator.
- Monitoring transaksi dan escrow.
- Approval withdrawal kreator.
- Pengaturan platform sederhana.

### Brand
- Dashboard brand.
- Membuat dan melihat campaign.
- Review submission kreator.
- Top-up saldo via Midtrans.
- Monitoring deposit dan escrow campaign.
- Profil brand dasar.

### Kreator
- Dashboard kreator.
- Marketplace campaign.
- Detail campaign.
- Klaim views dengan link konten dan bukti analytics.
- Riwayat submission.
- Wallet dan withdrawal.
- AI Auto-Clipper untuk membuat klip vertikal dari URL video.

## Struktur Database

Tabel bisnis utama:
- `users`
- `campaigns`
- `submissions`
- `clips`
- `deposits`
- `withdrawals`

Tabel sistem Laravel:
- `cache`
- `jobs`
- `sessions`
- `password_reset_tokens`

Dokumentasi database tersedia di:
- `DATABASE_README.md`
- `database_diagram.puml`
- `class_diagram.puml`

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
cd cliphub
```
*(Lewati langkah ini jika Anda sudah berada di dalam folder source code)*

### 2. Instalasi Dependensi PHP (Backend)
Jalankan perintah Composer di terminal untuk menginstal kerangka kerja Laravel dan package pihak ketiga lainnya:
```bash
composer install
```

### 2. Install dependency frontend

```bash
npm install
```

### 3. Siapkan file environment

```bash
copy .env.example .env
```

Untuk Git Bash/Linux/macOS:

```bash
cp .env.example .env
```

### 4. Konfigurasi database

Contoh konfigurasi MySQL Laragon:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cliphub
DB_USERNAME=root
DB_PASSWORD=
```

Buat database kosong bernama `cliphub` sebelum menjalankan migration.

### 5. Generate app key

```bash
php artisan key:generate
```

### 6. Jalankan migration dan seeder

Untuk setup baru:

```bash
php artisan migrate --seed
```

Jika ingin reset total database agar sesuai struktur terbaru:

```bash
php artisan migrate:fresh --seed
```

Catatan: `migrate:fresh` akan menghapus seluruh data lama.

### 7. Storage link

```bash
php artisan storage:link
```

## Konfigurasi Tambahan

### Midtrans

Digunakan untuk top-up saldo brand.

```env
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### AI Auto-Clipper

AI Auto-Clipper menggunakan Groq API, `yt-dlp`, dan `ffmpeg`.

```env
GROQ_API_KEY=
YTDLP_BIN_PATH=
FFMPEG_BIN_PATH=
YT_COOKIES_PATH=
```

`YTDLP_BIN_PATH`, `FFMPEG_BIN_PATH`, dan `YT_COOKIES_PATH` bersifat opsional jika binary sudah tersedia di PATH.

## Menjalankan Aplikasi

Cara paling mudah:

```bash
composer run dev
```

Perintah ini menjalankan:
- Laravel development server
- Queue listener
- Log pail
- Vite dev server

Alternatif manual:

```bash
php artisan serve
```

```bash
npm run dev
```

Untuk proses AI Auto-Clipper dan queue:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

## Akun Demo

Seeder membuat akun demo berikut:

| Role | Email | Password |
| --- | --- | --- |
| Admin | admin@cliphub.com | password |
| Kreator | kreator@cliphub.com | password |
| Brand | brand@cliphub.com | password |

## Route Utama

### Public
- `/`
- `/login`
- `/register`

### Admin
- `/admin/dashboard`
- `/admin/users`
- `/admin/kreators`
- `/admin/brands`
- `/admin/campaigns`
- `/admin/submissions`
- `/admin/payouts`
- `/admin/withdrawals`
- `/admin/settings`

### Brand
- `/brand/dashboard`
- `/brand/campaigns`
- `/brand/campaigns/create`
- `/brand/submissions`
- `/brand/finance`
- `/brand/profile`

### Kreator
- `/kreator/dashboard`
- `/kreator/campaigns`
- `/kreator/submissions`
- `/kreator/submissions/create`
- `/kreator/finance`
- `/kreator/ai-tools`

## Testing

```bash
php artisan test
```

## Tech Stack

- Laravel 12
- PHP 8.2+
- Tailwind CSS 4
- Vite
- Axios
- Midtrans PHP SDK
- Groq API
- yt-dlp
- ffmpeg

