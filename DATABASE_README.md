# Struktur Database ClipHub

Dokumen ini menjabarkan struktur tabel secara komprehensif dalam database ClipHub. Database ini tidak hanya terdiri dari tabel logika bisnis aplikasi (seperti campaign, transaksi, dll) tetapi juga tabel-tabel bawaan dari *framework* Laravel yang menangani *background jobs*, *caching*, dan *session*.

Aplikasi ini memiliki tiga role utama: **Admin**, **Brand**, dan **Kreator**, dan database dirancang untuk mendukung interaksi antar role ini, seperti campaign, submisi video, pemotongan video AI (clips), serta sistem keuangan.

---

## A. TABEL LOGIKA BISNIS (CORE)

### 1. Tabel `users`
Menyimpan data otentikasi dan profil pengguna untuk semua role.
*   **id**: Primary Key (bigint unsigned).
*   **name**: Nama pengguna.
*   **email**: Email unik untuk login.
*   **role**: Peran pengguna (enum: `'admin'`, `'kreator'`, `'brand'`). Default: `'kreator'`.
*   **balance**: Saldo pengguna (bigint). Bagi *Brand*, ini adalah sisa deposit. Bagi *Kreator*, ini adalah pendapatan dari campaign.
*   **bank_name**: Nama bank (opsional, untuk withdrawal kreator).
*   **bank_account**: Nomor rekening (opsional).
*   **password**: Password terenkripsi.
*   **email_verified_at**: Waktu saat email diverifikasi.
*   **remember_token**: Token "Remember Me" untuk sesi login.
*   **timestamps**: `created_at` & `updated_at`.

### 2. Tabel `campaigns`
Menyimpan data pekerjaan (campaign) yang dibuat oleh *Brand* untuk dikerjakan oleh *Kreator*.
*   **id**: Primary Key.
*   **user_id**: Foreign Key ke tabel `users` (Brand yang membuat campaign).
*   **title**: Judul campaign.
*   **type**: Jenis campaign (video, dll).
*   **slots**: Jumlah maksimal kreator/submisi yang dibutuhkan.
*   **thumbnail**: Gambar thumbnail campaign.
*   **desc**: Deskripsi singkat.
*   **full_brief**: Penjelasan detail dan instruksi (longtext).
*   **donts**: Larangan atau pantangan dalam video.
*   **assets_url**: Link referensi atau aset (misal: Google Drive).
*   **deadline**: Tanggal tenggat waktu campaign.
*   **video_length**: Durasi video yang diminta.
*   **link**: URL target (misal: link produk di e-commerce).
*   **platform**: Platform yang ditargetkan.
*   **budget**: Total budget yang disiapkan oleh Brand.
*   **price_per_1k**: Harga atau bayaran per 1000 views.
*   **status**: Status campaign.

### 3. Tabel `submissions`
Menyimpan tugas/video (UGC) yang disubmit oleh *Kreator* untuk sebuah Campaign.
*   **id**: Primary Key.
*   **user_id**: Foreign Key ke tabel `users` (Kreator yang mengirimkan tugas).
*   **campaign_id**: Foreign Key ke tabel `campaigns`.
*   **platform**: Platform tempat video diunggah (`'TikTok'`, `'Instagram'`, `'YouTube'`).
*   **views_claimed**: Jumlah views yang diklaim (berdasarkan screenshot analitik).
*   **video_url**: Link video konten kreator.
*   **analytics_proof_path**: Path gambar bukti analitik.
*   **estimated_reward**: Estimasi pendapatan (decimal).
*   **status**: Status submisi (`'pending'`, `'approved'`, `'rejected'`).
*   **rejection_reason**: Alasan penolakan.

### 4. Tabel `deposits`
Menyimpan riwayat top-up saldo oleh *Brand*.
*   **id**: Primary Key.
*   **user_id**: Foreign Key ke tabel `users` (Brand yang top-up).
*   **order_id**: ID pesanan yang unik (misal: DEP-12345).
*   **amount**: Jumlah uang yang di-deposit (bigint).
*   **status**: Status pembayaran (`'pending'`, `'success'`, `'failed'`, `'expired'`).
*   **payment_type**: Metode pembayaran (misal: gopay, bank_transfer).
*   **snap_token**: Token dari payment gateway (Midtrans).

### 5. Tabel `withdrawals`
Menyimpan riwayat penarikan dana (payout) oleh *Kreator*.
*   **id**: Primary Key.
*   **user_id**: Foreign Key ke tabel `users`.
*   **amount**: Jumlah uang yang ditarik (decimal).
*   **bank_name**: Nama Bank tujuan.
*   **bank_account**: Nomor Rekening tujuan.
*   **account_name**: Nama pemilik rekening.
*   **status**: Status pencairan (`'pending'`, `'completed'`, `'rejected'`).

### 6. Tabel `clips`
Digunakan oleh fitur **AI Auto-Clipper**. Menyimpan data tugas pemotongan video dari format landscape (YouTube) menjadi format vertikal (Shorts/Reels/TikTok).
*   **id**: Primary Key.
*   **user_id**: Foreign Key ke tabel `users`.
*   **title**: Judul clip.
*   **hook**: Kalimat menarik (hook).
*   **source_url**: Link sumber video aslinya.
*   **video_id**: ID video asli.
*   **ratio**: Aspek rasio (contoh: '9:16').
*   **start_time** & **end_time**: Detik mulai dan selesai potongan.
*   **duration**: Durasi video hasil klip (varchar).
*   **has_captions**: Apakah video diberikan caption (tinyint).
*   **transcript**: Teks dari audio.
*   **score**: Skor prediktif/viral (int).
*   **status**: Status pemrosesan.
*   **file_path**: Lokasi output video.
*   **file_size**: Ukuran file.

---

## B. TABEL SISTEM & FRAMEWORK (LARAVEL)

Selain tabel bisnis di atas, terdapat tabel-tabel bawaan dari *framework* Laravel yang wajib ada untuk menjalankan fitur inti *backend*:

### 7. Tabel `sessions`
Digunakan jika Laravel di-set untuk menyimpan session di database.
*   **id**: ID unik untuk session yang sedang aktif (PK).
*   **user_id**: Referensi ke tabel `users` untuk mengetahui siapa yang login di sesi tersebut.
*   **ip_address** & **user_agent**: Mencatat IP dan perangkat browser (device info).
*   **payload**: Data cookie/session yang terenkripsi.
*   **last_activity**: Waktu (timestamp epoch) aktivitas terakhir.

### 8. Tabel `password_reset_tokens`
Menyimpan token keamanan saat user menekan fitur "Lupa Password".
*   **email**: Email tujuan.
*   **token**: String acak sebagai kunci akses mereset password.
*   **created_at**: Kapan link dikirim (link bisa kadaluarsa dalam waktu tertentu).

### 9. Tabel `migrations`
Tabel fundamental untuk *version control* database.
*   **id**: Primary Key.
*   **migration**: Nama file migration PHP yang sudah dieksekusi.
*   **batch**: Urutan batch/gelombang eksekusi migration. Ini yang memungkinkan developer melakukan *rollback* struktur database ke versi sebelumnya.

### 10. Tabel `cache` & `cache_locks`
Tabel ini ada karena Laravel mengkonfigurasi driver "database" untuk *caching*.
*   **cache**: Menyimpan data temporer yang butuh diakses super cepat (menghindari query berat berulang-ulang). Berisi **key** (nama unik), **value** (data), dan **expiration** (waktu kedaluwarsa).
*   **cache_locks**: Menghindari aksi *race condition* (misalnya: saat ada dua proses PHP yang mencoba memproses top-up di waktu mili-detik yang sama, lock ini memastikan hanya 1 yang berjalan).

### 11. Tabel `jobs`, `job_batches`, `failed_jobs`
Tabel-tabel ini adalah nyawa dari fitur antrean (Queue / Background Processing). Sangat penting untuk ClipHub karena pemrosesan video dan email bisa berjalan lambat, sehingga dikerjakan di "latar belakang".
*   **jobs**: Daftar tugas yang sedang antre (menunggu dijalankan). Contoh isinya mungkin perintah "Render video untuk ID klip ke-10", disimpan di kolom `payload`.
*   **job_batches**: Jika memproses puluhan video sekaligus, ini akan membungkus puluhan `jobs` menjadi satu kloter/batch agar lebih mudah dipantau progress-nya (`pending_jobs`, `total_jobs`).
*   **failed_jobs**: Jika suatu tugas gagal (misalnya karena server memori penuh, koneksi API AI terputus), Laravel memindahkannya dari `jobs` ke tabel ini. Developer bisa melihat error log spesifik di kolom `exception`, dan merestart job tersebut tanpa harus mengulang dari browser.

---

## Relasi Utama Keseluruhan
*   **User (Brand) -> Campaigns**: 1 to Many.
*   **User (Kreator) -> Submissions**: 1 to Many.
*   **Campaign -> Submissions**: 1 to Many (Satu campaign dikerjakan banyak kreator).
*   **User -> Deposits / Withdrawals / Clips / Sessions**: 1 to Many.
