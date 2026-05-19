<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function brand_user_can_view_submission_detail_page()
    {
        $brand = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator']);
        $this->actingAs($brand);

        $campaign = Campaign::create([
            'user_id' => $brand->id,
            'title' => 'Test Campaign',
            'type' => 'video',
            'slots' => 10,
            'desc' => 'Campaign Desc',
            'full_brief' => 'Full Brief',
            'donts' => 'Donts',
            'deadline' => now()->addDays(5),
            'video_length' => '15s',
            'link' => 'https://example.com',
            'platform' => 'TikTok',
            'budget' => 500000,
            'price_per_1k' => 50000,
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaign->id,
            'platform' => 'TikTok',
            'views_claimed' => 2000,
            'video_url' => 'https://tiktok.com/@creator/video/12345',
            'analytics_proof_path' => 'proofs/proof.png',
            'estimated_reward' => 100000,
            'status' => 'pending',
        ]);

        $response = $this->get(route('brand.submissions.show', $submission->id));
        $response->assertStatus(200);
        $response->assertSee('Review Workspace');
        $response->assertSee($creator->name);
        $response->assertSee('Test Campaign');
        $response->assertSee('2.000 Views');
    }

    /** @test */
    public function brand_user_cannot_view_another_brands_submission()
    {
        $brandA = User::factory()->create(['role' => 'brand']);
        $brandB = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator']);

        $campaign = Campaign::create([
            'user_id' => $brandB->id, // Owned by brand B
            'title' => 'Brand B Campaign',
            'type' => 'video',
            'slots' => 10,
            'desc' => 'Campaign Desc',
            'full_brief' => 'Full Brief',
            'donts' => 'Donts',
            'deadline' => now()->addDays(5),
            'video_length' => '15s',
            'link' => 'https://example.com',
            'platform' => 'TikTok',
            'budget' => 500000,
            'price_per_1k' => 50000,
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaign->id,
            'platform' => 'TikTok',
            'views_claimed' => 2000,
            'video_url' => 'https://tiktok.com/@creator/video/12345',
            'analytics_proof_path' => 'proofs/proof.png',
            'estimated_reward' => 100000,
            'status' => 'pending',
        ]);

        // Login as Brand A (unauthorized for Brand B's campaign/submissions)
        $this->actingAs($brandA);

        $response = $this->get(route('brand.submissions.show', $submission->id));
        $response->assertStatus(403);
    }

    /** @test */
    public function brand_user_can_approve_submission_and_is_redirected_back_to_previous_page()
    {
        $brand = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator', 'balance' => 0]);
        $this->actingAs($brand);

        $campaign = Campaign::create([
            'user_id' => $brand->id,
            'title' => 'Test Campaign',
            'type' => 'video',
            'slots' => 10,
            'desc' => 'Campaign Desc',
            'full_brief' => 'Full Brief',
            'donts' => 'Donts',
            'deadline' => now()->addDays(5),
            'video_length' => '15s',
            'link' => 'https://example.com',
            'platform' => 'TikTok',
            'budget' => 500000,
            'price_per_1k' => 50000, // Rp 50.000 per 1k views
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaign->id,
            'platform' => 'TikTok',
            'views_claimed' => 2000, // 2000 views => (2000/1000) * 50000 = 100000 reward
            'video_url' => 'https://tiktok.com/@creator/video/12345',
            'analytics_proof_path' => 'proofs/proof.png',
            'status' => 'pending',
        ]);

        // Simulate request originating from the Campaign Show view
        $referrer = route('brand.campaigns.show', $campaign->id);

        $response = $this->from($referrer)
            ->post(route('brand.submissions.approve', $submission->id));

        // Check context-aware redirect back to the Campaign Show view
        $response->assertRedirect($referrer);
        $response->assertSessionHas('success');

        // Check database changes
        $submission->refresh();
        $this->assertEquals('approved', $submission->status);
        $this->assertEquals(100000, $submission->estimated_reward);

        $creator->refresh();
        $this->assertEquals(100000, $creator->balance);
    }

    /** @test */
    public function brand_user_can_reject_submission_with_reason_and_is_redirected_back()
    {
        $brand = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator']);
        $this->actingAs($brand);

        $campaign = Campaign::create([
            'user_id' => $brand->id,
            'title' => 'Test Campaign',
            'type' => 'video',
            'slots' => 10,
            'desc' => 'Campaign Desc',
            'full_brief' => 'Full Brief',
            'donts' => 'Donts',
            'deadline' => now()->addDays(5),
            'video_length' => '15s',
            'link' => 'https://example.com',
            'platform' => 'TikTok',
            'budget' => 500000,
            'price_per_1k' => 50000,
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaign->id,
            'platform' => 'TikTok',
            'views_claimed' => 2000,
            'video_url' => 'https://tiktok.com/@creator/video/12345',
            'analytics_proof_path' => 'proofs/proof.png',
            'status' => 'pending',
        ]);

        // Simulate request originating from the dedicated detail page
        $referrer = route('brand.submissions.show', $submission->id);

        $response = $this->from($referrer)->post(route('brand.submissions.reject', $submission->id), [
            'rejection_reason' => 'Video does not match content guidelines.',
        ]);

        // Assert correct redirect back
        $response->assertRedirect($referrer);
        $response->assertSessionHas('warning');

        // Check database changes
        $submission->refresh();
        $this->assertEquals('rejected', $submission->status);
        $this->assertEquals('Video does not match content guidelines.', $submission->rejection_reason);
    }

    /** @test */
    public function brand_user_can_view_paginated_submissions_list()
    {
        $brand = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator']);
        $this->actingAs($brand);

        $campaign = Campaign::create([
            'user_id' => $brand->id,
            'title' => 'Test Campaign',
            'type' => 'video',
            'slots' => 10,
            'desc' => 'Campaign Desc',
            'full_brief' => 'Full Brief',
            'donts' => 'Donts',
            'deadline' => now()->addDays(5),
            'video_length' => '15s',
            'link' => 'https://example.com',
            'platform' => 'TikTok',
            'budget' => 500000,
            'price_per_1k' => 50000,
            'status' => 'active',
        ]);

        // Create 12 submissions (which exceeds the pagination limit of 10)
        for ($i = 0; $i < 12; $i++) {
            Submission::create([
                'user_id' => $creator->id,
                'campaign_id' => $campaign->id,
                'platform' => 'TikTok',
                'views_claimed' => 2000 + $i,
                'video_url' => 'https://tiktok.com/@creator/video/12345' . $i,
                'analytics_proof_path' => 'proofs/proof.png',
                'status' => 'pending',
            ]);
        }

        $response = $this->get(route('brand.submissions'));
        $response->assertStatus(200);
        // Verify we see pagination elements
        $response->assertSee('Menampilkan');
        $response->assertSee('dari');
        $response->assertSee('12');
        $response->assertSee('hasil');
    }

    /** @test */
    public function brand_user_can_view_paginated_campaigns_list()
    {
        $brand = User::factory()->create(['role' => 'brand']);
        $this->actingAs($brand);

        // Create 8 campaigns (exceeds the pagination limit of 6)
        for ($i = 0; $i < 8; $i++) {
            Campaign::create([
                'user_id' => $brand->id,
                'title' => 'Test Campaign ' . $i,
                'type' => 'video',
                'slots' => 10,
                'desc' => 'Campaign Desc ' . $i,
                'full_brief' => 'Full Brief',
                'donts' => 'Donts',
                'deadline' => now()->addDays(5),
                'video_length' => '15s',
                'link' => 'https://example.com',
                'platform' => 'TikTok',
                'budget' => 500000,
                'price_per_1k' => 50000,
                'status' => 'active',
            ]);
        }

        $response = $this->get(route('brand.campaigns'));
        $response->assertStatus(200);
        // Verify we see pagination details
        $response->assertSee('Menampilkan');
        $response->assertSee('dari');
        $response->assertSee('8');
        $response->assertSee('hasil');
    }
}
