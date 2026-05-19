<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil kreator demo (Tio) dan kreator tambahan
        $kreator1 = User::where('role', 'kreator')->first();
        
        $kreator2 = User::firstOrCreate(
            ['email' => 'siska@cliphub.com'],
            ['name' => 'Siska UGC', 'password' => bcrypt('password'), 'role' => 'kreator']
        );

        $kreator3 = User::firstOrCreate(
            ['email' => 'budigaming@cliphub.com'],
            ['name' => 'Budi Gaming', 'password' => bcrypt('password'), 'role' => 'kreator']
        );

        $kreator4 = User::firstOrCreate(
            ['email' => 'rachel@cliphub.com'],
            ['name' => 'Rachel Review', 'password' => bcrypt('password'), 'role' => 'kreator']
        );

        // Ambil campaigns yang active
        $campaignTokopedia = Campaign::where('title', 'Promo Waktu Indonesia Belanja (WIB)')->first();
        $campaignWardah = Campaign::where('title', 'Skincare Routine Challenge')->first();
        $campaignGojek = Campaign::where('title', 'Promo GoRide Nyaman')->first();
        $campaignShopee = Campaign::where('title', 'Haul Shopee 12.12')->first();
        
        $submissions = [];

        // --- TOKOPEDIA (Clipper) ---
        if ($campaignTokopedia) {
            $submissions[] = [
                'user_id' => $kreator1->id,
                'campaign_id' => $campaignTokopedia->id,
                'platform' => 'TikTok',
                'video_url' => 'https://tiktok.com/@tio/video/123456789',
                'analytics_proof_path' => 'images/proof/tiktok-proof.jpg',
                'views_claimed' => 15000,
                'estimated_reward' => (15000 / 1000) * $campaignTokopedia->price_per_1k,
                'status' => 'approved',
                'created_at' => now()->subDays(5),
            ];
            
            $submissions[] = [
                'user_id' => $kreator3->id,
                'campaign_id' => $campaignTokopedia->id,
                'platform' => 'TikTok',
                'video_url' => 'https://tiktok.com/@budigaming/video/987654321',
                'analytics_proof_path' => 'images/proof/tiktok-proof.jpg',
                'views_claimed' => 8500,
                'estimated_reward' => (8500 / 1000) * $campaignTokopedia->price_per_1k,
                'status' => 'pending',
                'created_at' => now()->subDays(1),
            ];
        }

        // --- WARDAH (UGC) ---
        if ($campaignWardah) {
            $submissions[] = [
                'user_id' => $kreator2->id,
                'campaign_id' => $campaignWardah->id,
                'platform' => 'TikTok',
                'video_url' => 'https://tiktok.com/@siska/video/wardah1',
                'analytics_proof_path' => 'images/proof/tiktok-proof.jpg',
                'views_claimed' => 45000,
                'estimated_reward' => (45000 / 1000) * $campaignWardah->price_per_1k,
                'status' => 'approved',
                'created_at' => now()->subDays(10),
            ];
            
            $submissions[] = [
                'user_id' => $kreator4->id,
                'campaign_id' => $campaignWardah->id,
                'platform' => 'TikTok',
                'video_url' => 'https://tiktok.com/@rachel/video/wardah2',
                'analytics_proof_path' => 'images/proof/tiktok-proof.jpg',
                'views_claimed' => 12000,
                'estimated_reward' => (12000 / 1000) * $campaignWardah->price_per_1k,
                'status' => 'rejected',
                'rejection_reason' => 'Wajah tertutup setengah, tidak sesuai brief.',
                'created_at' => now()->subDays(8),
            ];

            $submissions[] = [
                'user_id' => $kreator1->id,
                'campaign_id' => $campaignWardah->id,
                'platform' => 'TikTok',
                'video_url' => 'https://tiktok.com/@tio/video/wardah3',
                'analytics_proof_path' => 'images/proof/tiktok-proof.jpg',
                'views_claimed' => 5000,
                'estimated_reward' => (5000 / 1000) * $campaignWardah->price_per_1k,
                'status' => 'pending',
                'created_at' => now(),
            ];
        }

        // --- GOJEK (Instagram) ---
        if ($campaignGojek) {
            $submissions[] = [
                'user_id' => $kreator3->id,
                'campaign_id' => $campaignGojek->id,
                'platform' => 'Instagram',
                'video_url' => 'https://instagram.com/p/gojek123',
                'analytics_proof_path' => 'images/proof/ig-proof.jpg',
                'views_claimed' => 22000,
                'estimated_reward' => (22000 / 1000) * $campaignGojek->price_per_1k,
                'status' => 'approved',
                'created_at' => now()->subDays(3),
            ];
        }

        // --- SHOPEE (Completed / Habis) ---
        if ($campaignShopee) {
            $submissions[] = [
                'user_id' => $kreator2->id,
                'campaign_id' => $campaignShopee->id,
                'platform' => 'TikTok',
                'video_url' => 'https://tiktok.com/@siska/video/shopee1',
                'analytics_proof_path' => 'images/proof/tiktok-proof.jpg',
                'views_claimed' => 100000,
                'estimated_reward' => (100000 / 1000) * $campaignShopee->price_per_1k,
                'status' => 'approved',
                'created_at' => now()->subDays(20),
            ];
        }

        foreach ($submissions as $sub) {
            Submission::create($sub);
        }
    }
}
