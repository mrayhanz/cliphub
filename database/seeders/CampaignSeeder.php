<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $brand = User::where('role', 'brand')->first();

        // Siapkan beberapa user brand tambahan agar tampilannya beragam
        $brandTokopedia = User::firstOrCreate(
            ['email' => 'tokopedia@cliphub.com'],
            ['name' => 'Tokopedia', 'password' => bcrypt('password'), 'role' => 'brand', 'balance' => 5000000]
        );
        $brandWardah = User::firstOrCreate(
            ['email' => 'wardah@cliphub.com'],
            ['name' => 'Wardah Beauty', 'password' => bcrypt('password'), 'role' => 'brand', 'balance' => 5000000]
        );
        $brandGojek = User::firstOrCreate(
            ['email' => 'gojek@cliphub.com'],
            ['name' => 'Gojek', 'password' => bcrypt('password'), 'role' => 'brand', 'balance' => 5000000]
        );
        $brandShopee = User::firstOrCreate(
            ['email' => 'shopee@cliphub.com'],
            ['name' => 'Shopee', 'password' => bcrypt('password'), 'role' => 'brand', 'balance' => 5000000]
        );

        $campaigns = [
            [
                'user_id' => $brandTokopedia->id,
                'title' => 'Promo Waktu Indonesia Belanja (WIB)',
                'type' => 'clip',
                'slots' => 10,
                'thumbnail' => 'images/campaigns/tokopedia.png',
                'desc' => 'Buat video unboxing atau promo WIB menggunakan fitur auto-clipper dari live streaming Tokopedia.',
                'full_brief' => 'Kreator WAJIB menggunakan 100% materi video/asset yang telah disediakan oleh brand untuk kebutuhan clipper campaign Promo Waktu Indonesia Belanja. TIDAK diperbolehkan upload konten yang tidak ada hubungannya dengan event Tokopedia WIB.',
                'donts' => 'Dilarang mempromosikan platform e-commerce lain dalam video.',
                'deadline' => Carbon::now()->addDays(20),
                'video_length' => '30 - 60 Detik',
                'link' => 'https://tokopedia.com',
                'platform' => 'Tiktok',
                'budget' => 2000000,
                'price_per_1k' => 20000,
                'status' => 'active'
            ],
            [
                'user_id' => $brandWardah->id,
                'title' => 'Skincare Routine Challenge',
                'type' => 'ugc',
                'slots' => 50,
                'thumbnail' => 'images/campaigns/wardah.png',
                'desc' => 'Promosikan serum terbaru Wardah. Link referral wajib disisipkan di kolom komentar atau BIO.',
                'full_brief' => 'Kreator diminta untuk membuat video A-day-in-my-life atau Get Ready With Me (GRWM) menggunakan Skincare terbaru dari Wardah. Pastikan wajah terlihat jelas (glowing).',
                'donts' => 'Dilarang menggunakan produk dari merk kompetitor selama video berlangsung.',
                'deadline' => Carbon::now()->addDays(15),
                'video_length' => '1 Menit',
                'link' => 'https://wardahbeauty.com',
                'platform' => 'Tiktok',
                'budget' => 5000000,
                'price_per_1k' => 25000,
                'status' => 'active'
            ],
            [
                'user_id' => $brandGojek->id,
                'title' => 'Promo GoRide Nyaman',
                'type' => 'clip',
                'slots' => 15,
                'thumbnail' => 'images/campaigns/gojek.png',
                'desc' => 'Potong video podcast yang membahas pengalaman menggunakan Gojek. Tag akun @gojekindonesia.',
                'full_brief' => 'Silakan ambil video podcast resmi Gojek dari YouTube dan potong bagian di mana bintang tamu menceritakan pengalaman lucu atau berkesan naik GoRide.',
                'donts' => 'Jangan mengubah konteks pembicaraan podcast. Harus original.',
                'deadline' => Carbon::now()->addDays(30),
                'video_length' => '60 Detik',
                'link' => 'https://gojek.com',
                'platform' => 'Instagram',
                'budget' => 1500000,
                'price_per_1k' => 18000,
                'status' => 'active'
            ],
            [
                'user_id' => $brandShopee->id,
                'title' => 'Haul Shopee 12.12',
                'type' => 'ugc',
                'slots' => 0, // Penuh / Habis
                'thumbnail' => 'images/campaigns/shopee.png',
                'desc' => 'Review barang unik dari Shopee. Pastikan hashtag #shopeehaul ada di deskripsi konten kamu.',
                'full_brief' => 'Tampilkan minimal 3 produk unik yang kamu beli dari Shopee. Video berdurasi 30-60 detik dengan voiceover yang ceria.',
                'donts' => 'Dilarang menampilkan logo kompetitor secara gamblang.',
                'deadline' => Carbon::now()->subDays(2), // Sudah lewat / habis
                'video_length' => '30 - 60 Detik',
                'link' => 'https://shopee.co.id',
                'platform' => 'all',
                'budget' => 500000,
                'price_per_1k' => 22000,
                'status' => 'active'
            ],
            [
                'user_id' => $brand->id,
                'title' => 'Glowing Skin Challenge',
                'type' => 'ugc',
                'slots' => 20,
                'thumbnail' => 'images/campaigns/skincare.png',
                'desc' => 'Ikuti challenge Glowing Skin 14 Hari menggunakan produk perawatan dini kami.',
                'full_brief' => 'Campaign ini berfokus pada hasil 14 hari pemakaian. Dokumentasikan hari pertama hingga terakhir.',
                'donts' => 'Jangan pakai filter beauty',
                'deadline' => Carbon::now()->addDays(40),
                'video_length' => '1 - 2 Menit',
                'link' => 'https://skincare.dummy',
                'platform' => 'Tiktok',
                'budget' => 3000000,
                'price_per_1k' => 30000,
                'status' => 'active'
            ],
        ];

        foreach ($campaigns as $campaign) {
            Campaign::create($campaign);
        }
    }
}
