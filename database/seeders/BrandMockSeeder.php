<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Submission;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BrandMockSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure primary demo brand user exists
        $brand = User::where('email', 'tokopedia@cliphub.com')->first();
        // if (!$brand) {
        //     $brand = User::create([
        //         'name'     => 'Skincare Brand',
        //         'email'    => 'brand@cliphub.com',
        //         'password' => bcrypt('password'),
        //         'role'     => 'brand',
        //         'balance'  => 15000000
        //     ]);
        // }

        // 2. Ensure multiple diverse creator users exist for rich listings
        $creatorsInfo = [
            ['name' => 'Tio Nugroho', 'email' => 'tio@cliphub.com'],
            ['name' => 'Sarah Amalia', 'email' => 'sarah@cliphub.com'],
            ['name' => 'Rian Hidayat', 'email' => 'rian@cliphub.com'],
            ['name' => 'Jessica Wong', 'email' => 'jessica@cliphub.com'],
            ['name' => 'Budi Setiawan', 'email' => 'budi@cliphub.com'],
            ['name' => 'Amalia Putri', 'email' => 'amalia@cliphub.com'],
            ['name' => 'Dimas Pratama', 'email' => 'dimas@cliphub.com'],
            ['name' => 'Farhan Majid', 'email' => 'farhan@cliphub.com']
        ];

        $creators = [];
        foreach ($creatorsInfo as $info) {
            $creators[] = User::firstOrCreate(
                ['email' => $info['email']],
                [
                    'name' => $info['name'],
                    'password' => bcrypt('password'),
                    'role' => 'kreator',
                    'balance' => 0
                ]
            );
        }

        // 3. Generate 18 high-fidelity Campaigns for the main brand user
        $campaignData = [
            // Active
            [
                'title' => 'Glowing Skin Routine Challenge',
                'type' => 'ugc',
                'slots' => 20,
                'desc' => 'Ikuti challenge Glowing Skin 14 Hari menggunakan produk serum perawatan dini kami.',
                'full_brief' => 'Campaign ini berfokus pada hasil 14 hari pemakaian serum. Dokumentasikan hari pertama hingga terakhir pemakaian serum kami.',
                'donts' => 'Jangan memakai filter beauty atau kosmetik lain saat pemakaian.',
                'deadline' => Carbon::now()->addDays(25),
                'video_length' => '1 - 2 Menit',
                'link' => 'https://skincare.dummy/glowing',
                'platform' => 'Tiktok',
                'budget' => 3000000,
                'price_per_1k' => 30000,
                'status' => 'active'
            ],
            [
                'title' => 'Clipper Podcast: Brand Skincare Reveal',
                'type' => 'clip',
                'slots' => 15,
                'desc' => 'Potong video podcast resmi talkshow kecantikan skincare kami dan sebarkan di platform sosial.',
                'full_brief' => 'Kreator diwajibkan memotong segmen podcast kecantikan berdurasi 30-60 detik yang paling menarik.',
                'donts' => 'Jangan melenceng dari topik skincare. Dilarang memotong kata-kata narasumber secara kasar.',
                'deadline' => Carbon::now()->addDays(12),
                'video_length' => '30 - 60 Detik',
                'link' => 'https://skincare.dummy/podcast-assets',
                'platform' => 'Instagram',
                'budget' => 2500000,
                'price_per_1k' => 20000,
                'status' => 'active'
            ],
            [
                'title' => 'Sunscreen Anti-Polusi Launch 2026',
                'type' => 'ugc',
                'slots' => 35,
                'desc' => 'Edukasi audiens tentang pentingnya proteksi kulit UV + Anti-Polusi menggunakan produk terbaru kami.',
                'full_brief' => 'Tunjukkan tekstur ringan sunscreen kami dan demonstrasikan pemakaian outdoor.',
                'donts' => 'Dilarang membandingkan langsung dengan merk kompetitor di video.',
                'deadline' => Carbon::now()->addDays(30),
                'video_length' => '60 Detik',
                'link' => 'https://skincare.dummy/sunscreen',
                'platform' => 'Tiktok',
                'budget' => 5000000,
                'price_per_1k' => 25000,
                'status' => 'active'
            ],
            [
                'title' => 'Night Repair Cream Honest Review',
                'type' => 'ugc',
                'slots' => 12,
                'desc' => 'Ulas secara jujur manfaat krim malam kami untuk mengatasi kemerahan wajah saat bangun tidur.',
                'full_brief' => 'Ambil video malam sebelum tidur dan keesokan paginya. Tunjukkan perbedaan kelembapan wajah.',
                'donts' => 'Dilarang menggunakan make up berlebihan pada video pagi hari.',
                'deadline' => Carbon::now()->addDays(8),
                'video_length' => '60 Detik',
                'link' => 'https://skincare.dummy/nightcream',
                'platform' => 'Tiktok',
                'budget' => 1800000,
                'price_per_1k' => 30000,
                'status' => 'active'
            ],
            [
                'title' => 'Hydrating Toner Mini ASMR',
                'type' => 'ugc',
                'slots' => 25,
                'desc' => 'Buat video bernuansa ASMR menggunakan botol spray hydrating toner kami.',
                'full_brief' => 'Fokus pada suara botol toner, tepukan lembut di wajah, dan efek kesegaran air.',
                'donts' => 'Hindari suara bising atau backsound musik keras. Harus murni bernuansa tenang.',
                'deadline' => Carbon::now()->addDays(15),
                'video_length' => '30 - 45 Detik',
                'link' => 'https://skincare.dummy/toner',
                'platform' => 'Tiktok',
                'budget' => 2000000,
                'price_per_1k' => 18000,
                'status' => 'active'
            ],
            [
                'title' => 'Acne Spot Gel: Bye Bye Jerawat!',
                'type' => 'ugc',
                'slots' => 40,
                'desc' => 'Tunjukkan keampuhan acne spot gel kami mengeringkan jerawat aktif dalam waktu 24 jam.',
                'full_brief' => 'Ambil video close-up area jerawat sebelum pemakaian dan sesudah pemakaian.',
                'donts' => 'Jangan diedit menggunakan Photoshop atau filter penghilang jerawat.',
                'deadline' => Carbon::now()->addDays(20),
                'video_length' => '30 - 60 Detik',
                'link' => 'https://skincare.dummy/acne-gel',
                'platform' => 'Tiktok',
                'budget' => 4500000,
                'price_per_1k' => 22000,
                'status' => 'active'
            ],
            // Completed
            [
                'title' => 'Mouthwash Refreshing Campaign',
                'type' => 'ugc',
                'slots' => 50,
                'desc' => 'Kampanye produk pencuci mulut segar alami anti-bau seharian.',
                'full_brief' => 'Kreator menunjukkan rasa percaya diri bernapas segar setelah kumur mouthwash.',
                'donts' => 'Jangan menggunakan kata-kata kasar.',
                'deadline' => Carbon::now()->subDays(5),
                'video_length' => '45 Detik',
                'link' => 'https://skincare.dummy/mouthwash',
                'platform' => 'all',
                'budget' => 2000000,
                'price_per_1k' => 15000,
                'status' => 'completed'
            ],
            [
                'title' => 'Face Mist Glowing On-the-Go',
                'type' => 'ugc',
                'slots' => 10,
                'desc' => 'Gunakan face mist praktis saat bepergian untuk menjaga kelembapan kulit wajah.',
                'full_brief' => 'Video dibuat di luar ruangan (outdoor) saat cuaca terik.',
                'donts' => 'Dilarang merekam di tempat gelap.',
                'deadline' => Carbon::now()->subDays(10),
                'video_length' => '30 Detik',
                'link' => 'https://skincare.dummy/facemist',
                'platform' => 'Instagram',
                'budget' => 1500000,
                'price_per_1k' => 25000,
                'status' => 'completed'
            ],
            [
                'title' => 'Brightening Body Lotion Launch',
                'type' => 'ugc',
                'slots' => 8,
                'desc' => 'Ulas keunggulan instant-brightening dari produk losion tubuh terbaru.',
                'full_brief' => 'Tunjukkan perbandingan tangan kiri dan tangan kanan yang menggunakan lotion.',
                'donts' => 'Jangan menggunakan filter pencahayaan studio berlebih.',
                'deadline' => Carbon::now()->subDays(15),
                'video_length' => '60 Detik',
                'link' => 'https://skincare.dummy/lotion',
                'platform' => 'Tiktok',
                'budget' => 1200000,
                'price_per_1k' => 30000,
                'status' => 'completed'
            ],
            // Draft
            [
                'title' => '[Draft] Cleansing Balm Melt Challenge',
                'type' => 'ugc',
                'slots' => 30,
                'desc' => 'Tunjukkan bagaimana cleansing balm kami melelehkan make up tebal tahan air dalam 10 detik.',
                'full_brief' => 'Ulas efek melting dari balm saat diaplikasikan di wajah bersolek.',
                'donts' => 'Jangan dibilas kasar.',
                'deadline' => Carbon::now()->addDays(40),
                'video_length' => '60 Detik',
                'link' => 'https://skincare.dummy/cleansing-balm',
                'platform' => 'Tiktok',
                'budget' => 3500000,
                'price_per_1k' => 20000,
                'status' => 'draft'
            ],
            [
                'title' => '[Draft] Clay Mask Detox Weekly Routine',
                'type' => 'ugc',
                'slots' => 15,
                'desc' => 'Demonstrasikan rutinitas mingguan menggunakan masker lumpur charcoal.',
                'full_brief' => 'Tunjukkan pori-pori yang bersih setelah menggunakan masker selama 15 menit.',
                'donts' => 'Jangan mendiamkan masker terlalu kering hingga pecah.',
                'deadline' => Carbon::now()->addDays(45),
                'video_length' => '1 Menit',
                'link' => 'https://skincare.dummy/claymask',
                'platform' => 'Instagram',
                'budget' => 2000000,
                'price_per_1k' => 25000,
                'status' => 'draft'
            ],
            [
                'title' => '[Draft] Lip Balm Watermelon Tint Promo',
                'type' => 'ugc',
                'slots' => 20,
                'desc' => 'Tampilkan warna natural dari pelembap bibir buah semangka kami.',
                'full_brief' => 'Gunakan lip balm sebelum bepergian sekolah atau kuliah.',
                'donts' => 'Dilarang menimpa dengan lipstik matte lainnya.',
                'deadline' => Carbon::now()->addDays(50),
                'video_length' => '30 Detik',
                'link' => 'https://skincare.dummy/lipbalm',
                'platform' => 'Tiktok',
                'budget' => 1500000,
                'price_per_1k' => 15000,
                'status' => 'draft'
            ],
            // Cancelled
            [
                'title' => '[Cancelled] Hair Care Serum Anti-Dandruff',
                'type' => 'ugc',
                'slots' => 10,
                'desc' => 'Mengurangi ketombe kepala dengan formula alami mint hair serum.',
                'full_brief' => 'Tunjukkan rambut berkilau bebas serpihan ketombe.',
                'donts' => 'Jangan garuk kepala dengan kuku tajam di video.',
                'deadline' => Carbon::now()->subDays(1),
                'video_length' => '60 Detik',
                'link' => 'https://skincare.dummy/haircare',
                'platform' => 'Tiktok',
                'budget' => 1000000,
                'price_per_1k' => 20000,
                'status' => 'cancelled'
            ],
            [
                'title' => '[Cancelled] Rose Water Soothing Mist',
                'type' => 'ugc',
                'slots' => 20,
                'desc' => 'Gunakan air mawar organik alami untuk penyegar wajah kusam.',
                'full_brief' => 'Semprotkan tipis-tipis rose water di siang hari terik.',
                'donts' => 'Jangan semprot dekat area mata langsung.',
                'deadline' => Carbon::now()->subDays(5),
                'video_length' => '30 Detik',
                'link' => 'https://skincare.dummy/rosewater',
                'platform' => 'all',
                'budget' => 2000000,
                'price_per_1k' => 18000,
                'status' => 'cancelled'
            ],
            [
                'title' => '[Cancelled] Men Face Wash Active Energy',
                'type' => 'ugc',
                'slots' => 25,
                'desc' => 'Uji ketahanan face wash pria melawan minyak dan polusi setelah motoran.',
                'full_brief' => 'Tunjukkan cuplikan jalanan berdebu lalu cuci muka dengan sabun kami.',
                'donts' => 'Jangan gunakan model wanita di video.',
                'deadline' => Carbon::now()->subDays(8),
                'video_length' => '45 Detik',
                'link' => 'https://skincare.dummy/menwash',
                'platform' => 'Tiktok',
                'budget' => 2500000,
                'price_per_1k' => 25000,
                'status' => 'cancelled'
            ]
        ];

        $campaigns = [];
        foreach ($campaignData as $data) {
            $data['user_id'] = $brand->id;
            $data['thumbnail'] = 'campaigns/dummy_thumb.png'; // Will fall back to nice default placeholder
            $campaigns[] = Campaign::create($data);
        }

        // 4. Generate 25 rich Submissions spread across these campaigns with diverse statuses
        $submissionStates = ['pending', 'approved', 'rejected'];
        $videoUrls = [
            'https://www.tiktok.com/@creator/video/1234567890123456789',
            'https://www.tiktok.com/@influencer/video/9876543210987654321',
            'https://www.instagram.com/reel/C8_xYZ123abc/',
            'https://www.instagram.com/reel/C9_abc456xyz/'
        ];
        $rejectionReasons = [
            'Kualitas resolusi video terlalu rendah dan buram.',
            'Tidak menyertakan botol skincare kami secara jelas di awal video.',
            'Melanggar aturan pantangan (Don\'ts): Memakai filter beauty wajah sangat tebal.',
            'Durasi video kurang dari ketentuan minimal 30 detik.',
            'Tautan video tidak dapat diakses (private / deleted).'
        ];

        for ($i = 1; $i <= 25; $i++) {
            $creator = $creators[array_rand($creators)];
            $campaign = $campaigns[array_rand($campaigns)];
            
            $status = $submissionStates[$i % 3];
            $views = rand(5000, 150000);
            $pricePer1k = $campaign->price_per_1k;
            $estimatedReward = ($views * $pricePer1k) / 1000;
            
            $rejectionReason = ($status === 'rejected') ? $rejectionReasons[array_rand($rejectionReasons)] : null;
            
            $mappedPlatform = match (strtolower($campaign->platform)) {
                'tiktok' => 'TikTok',
                'instagram' => 'Instagram',
                'youtube' => 'YouTube',
                default => 'TikTok',
            };

            Submission::create([
                'user_id' => $creator->id,
                'campaign_id' => $campaign->id,
                'platform' => $mappedPlatform,
                'views_claimed' => $views,
                'video_url' => $videoUrls[array_rand($videoUrls)],
                'analytics_proof_path' => 'campaigns/dummy_proof.png', // View will automatically fall back to beautiful empty placeholder
                'estimated_reward' => $status === 'approved' ? $estimatedReward : 0,
                'status' => $status,
                'rejection_reason' => $rejectionReason,
                'created_at' => Carbon::now()->subHours($i * 4),
            ]);
        }

        // 5. Generate 15 high-fidelity Financial Deposits for the main brand user
        $depositStates = ['success', 'pending', 'expired', 'failed'];
        $paymentTypes = ['credit_card', 'bank_transfer', 'gopay', 'shopeepay'];
        
        for ($j = 1; $j <= 15; $j++) {
            $status = $depositStates[$j % 4];
            $amount = rand(2, 50) * 100000; // Rp 200,000 to Rp 5,000,000
            $orderId = 'DEP-' . $brand->id . '-' . (time() - ($j * 12 * 3600));
            
            Deposit::create([
                'user_id' => $brand->id,
                'order_id' => $orderId,
                'amount' => $amount,
                'status' => $status,
                'snap_token' => $status === 'pending' ? 'dummy_snap_token_' . uniqid() : null,
                'payment_type' => $status === 'success' ? $paymentTypes[array_rand($paymentTypes)] : null,
                'created_at' => Carbon::now()->subDays($j)->subHours(rand(1, 10)),
            ]);
        }
    }
}
