# Dokumentasi Komprehensif: Peran Pengguna Brand (Brand User Role)

Dokumen ini adalah acuan utama (*single source of truth*) yang merangkum keseluruhan arsitektur, alur kerja (workflow), serta logika backend yang beroperasi pada peran pengguna **Brand** di platform ClipHub. Dokumentasi ini bertujuan untuk memudahkan developer maupun AI agent di masa depan dalam memahami relasi antar komponen di dalam modul Brand.

---

## 1. Ringkasan Eksekutif & Behavior Role Brand

Peran **Brand** dirancang sebagai entitas bisnis atau perorangan yang bertujuan untuk memasarkan produk mereka melalui kreator konten (UGC). Berbeda dengan kreator yang mencari proyek, Brand bertindak sebagai penyedia dana dan pembuat proyek (*Campaign*).

### **Alur Perilaku Utama (Core Behavior Workflow):**
1. **Pendanaan (Top-Up):** Sebelum Brand dapat meluncurkan kampanye, mereka diwajibkan untuk mengisi saldo (*wallet balance*) melalui integrasi gerbang pembayaran Midtrans. Status saldo ini mutlak dibutuhkan sebagai jaminan (*escrow*) bagi para kreator.
2. **Peluncuran Kampanye (Campaign Launch):** Brand membuat draf pekerjaan yang mencakup *brief* detail, platform target, dan alokasi budget (Harga per 1K Views & Jumlah Kuota). Sistem akan **menahan dana (escrow)** secara otomatis dari saldo aktif mereka untuk menjamin bahwa kreator yang mengambil pekerjaan tersebut pasti akan dibayar.
3. **Pemantauan (Monitoring):** Melalui Dashboard, Brand memantau kinerja kampanye mereka. Sistem agregasi akan menarik data *views* organik dari kreator yang berpartisipasi dan memproyeksikannya dalam bentuk analitik waktu nyata (*real-time metrics*).
4. **Audit & Persetujuan (Submission Review):** Kreator mengirimkan tautan video serta tangkapan layar bukti analitik. Brand memiliki tanggung jawab untuk melakukan audit:
   - **Approve:** Jika video sesuai kriteria, sistem akan langsung mencairkan dana *escrow* dan mentransfernya ke dompet kreator berdasarkan performa *views* saat itu.
   - **Reject:** Jika tidak sesuai, Brand memberikan alasan penolakan, mengembalikan status submission ke awal, dan mengosongkan slot agar kreator lain dapat mendaftar.

---

## 2. Pemetaan Alur Kerja (Workflow Pages) & Antarmuka UI

Keseluruhan antarmuka modul Brand dibangun menggunakan arsitektur visual **Midnight Neon** (kombinasi mode gelap ekstrem, aksen *Emerald* menyala, dan *glassmorphism*).

### A. Dashboard Utama (`/brand/dashboard`)
Pusat kendali (Command Center) bagi Brand. 
- **Fungsi:** Menampilkan agregasi data tingkat tinggi seperti Total Saldo Tersedia, Kampanye Aktif, Dana Tertahan (Escrow), dan Jumlah Submission yang Menunggu Review.
- **UI Highlight:** Memiliki grafik *Area Chart* (ApexCharts) interaktif yang memplot performa penayangan (*views*) dari seluruh UGC dalam 7 hari terakhir.

### B. Daftar Campaign (`/brand/campaigns`)
Halaman pelacakan seluruh kampanye pemasaran yang pernah dibuat.
- **Fungsi:** Menampilkan daftar kartu kampanye (Aktif, Selesai, Draft, Dibatalkan).
- **UX Tambahan:** Dilengkapi dengan komponen reusabel `filter-search.blade.php` yang mendukung pencarian kata kunci spesifik dan fungsi **Sort By** (Terbaru, Terlama, Nama A-Z, Budget Tertinggi/Terendah).

### C. Pembuatan Campaign (`/brand/campaigns/create`)
Formulir *wizard-style* untuk memulai kampanye baru.
- **Fungsi:** Mengambil data esensial seperti *Title*, *Brief*, *Do's & Don'ts*, serta *Budgeting*. 
- **Validasi Kritis:** Mengimplementasikan penjagaan (guard) di mana Brand **tidak dapat** menerbitkan kampanye jika Saldo (Balance) mereka lebih kecil dari total Budget (Slots × Harga per 1K Views).

### D. Review Submission (`/brand/submissions`)
Halaman audit pekerjaan kreator.
- **Fungsi:** Berisi tabel/kartu submission. Memiliki tombol interaktif dengan Alpine.js untuk membuka modal *Pop-up* detail (menampilkan tautan konten dan bukti gambar statistik).
- **Aksi:** Terdapat dua tombol mutlak: "Approve" (menjalankan pencairan dana idempoten) dan "Reject" (menampilkan modal untuk mengetik alasan penolakan). Fitur *Sort By* (Nama Kreator, Reward Tertinggi, Views Terbanyak) juga terintegrasi.

### E. Keuangan / Top-up (`/brand/finance`)
Manajemen dompet digital instansi Brand.
- **Fungsi:** Menampilkan riwayat mutasi dana deposit. Terdapat panel sebelah kiri untuk memicu *pop-up* Snap Midtrans agar Brand dapat langsung mentransfer uang ke sistem (Top-up). Fitur *Sort By* disertakan berdasarkan Order ID dan Nominal Deposit.

### F. Profil Brand (`/brand/profile`)
Halaman manajemen identitas.
- **Fungsi:** Mengatur logo instansi, nama PIC, alamat domisili, tautan toko online, serta pembaruan kata sandi. 
- **UI Highlight:** Menggunakan layout `flex-1` dinamis yang membentang menutupi ketinggian layar secara presisi.

---

## 3. Pemetaan Controller per Aksi (Action Controllers)

Berikut adalah struktur logika *Backend* (Laravel Controllers) yang menggerakkan antarmuka di atas:

| Controller | Aksi (Method) | Tanggung Jawab & Logika Bisnis |
| :--- | :--- | :--- |
| **`DashboardController`** | `index()` | Agregator utama. Menghitung `balance`, query `$activeCampaigns`, `$pendingReview`, total *escrow*, serta menarik rentang tanggal 7 hari terakhir dari tabel `submissions` yang *approved* untuk disuplai ke ApexCharts. |
| **`CampaignController`** | `index()` | Menjalankan *query builder* untuk daftar kampanye milik pengguna beserta filter pencarian dan opsi *Sort By* bawaan SQL (`orderBy()`). |
| | `create()` | Merender tampilan formulir. |
| | `store()` | **Sangat Kritis:** Melakukan komputasi matematika `total_budget = slots * price_per_1k`. Menjalankan `DB::transaction()` untuk memotong `user->balance` sebagai jaminan (escrow) secara atomik, lalu menyimpan data kampanye. |
| **`SubmissionController`** | `index()` | Menarik seluruh pengajuan (*submissions*) yang terikat secara relasional pada kampanye milik Brand. Mendukung Join SQL (`join('users')`) khusus untuk mengurutkan berdasarkan nama kreator. |
| | `approve()` | **Sangat Kritis:** Menangani transfer dana pencairan. Menghitung `payout = (views / 1000) * price_per_1k`. Jika dana mencukupi, sistem mendebet sisa anggaran kampanye dan mengkredit ke dompet kreator dalam sebuah `DB::transaction()`. Mencegah persetujuan ganda (idempoten). |
| | `reject()` | Menangkap input `reason_rejected`, mengubah status submission kembali ke "rejected", sehingga kreator dapat merevisi atau menyerah pada slot tersebut. |
| **`FinanceController`** | `index()` | Mengambil relasi `user->deposits()` dengan filter status Midtrans (Pending, Success, Expired). |
| | `topup()` | Menerima input nominal, membuat `order_id` unik (contoh: `DEP-BRAND-1234`), mengontak API Snap Midtrans, dan menyimpan entri deposit sebagai `pending`. |
| | `webhook()` | Titik masuk integrasi server-to-server Midtrans. Memverifikasi *signature key* dan memperbarui status deposit menjadi `success` serta menambahkan `balance` ke pengguna secara otomatis. |
| **`ProfileController`** | `index()` | Merender tab informasi read-only, form edit profil, dan form ubah kata sandi. |
| | `update()` | Menangani pengunggahan file logo (validasi mime, penyimpanan via `Storage::disk('public')`) dan pembaruan informasi kontak. |
| | `password()` | Memverifikasi kata sandi lama (`Hash::check`) dan mengenkripsi kata sandi baru. |

---

## 4. Sorotan UX & Fitur Tambahan (Modernisasi Fase 2)

Dalam upaya standarisasi estetika dan fungsionalitas, telah dilakukan pembaruan berskala besar:

1. **Migrasi Ikonologi:** Mengubah seluruh antarmuka yang sebelumnya mengandalkan Emoji standar menjadi ikon vektor presisi (SVG) menggunakan pustaka **Lucide Icons** untuk tampilan profesional.
2. **Modul Komponen Responsif:** Arsitektur UI dipecah menjadi komponen *reusable* (`filter-search.blade.php`). Komponen ini diberikan parameter kecerdasan `$compact => true` yang memungkinkan komponen berubah dari tata letak melebar (*row-based*) menjadi tata letak bertumpuk vertikal (*column-based*) apabila dirender di dalam kontainer yang sempit seperti pada halaman Keuangan (Finance).
3. **Standardisasi Data (Sorting):** Menghapus fungsionalitas pengurutan statis (`latest()`) dan menggantinya dengan query dinamis dua arah yang memungkinkan Brand melakukan audit data secara lebih tajam (misal: "Siapa kreator dengan Views tertinggi?").
