<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandSubmissionReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function brand_user_can_view_submissions_list_of_their_campaigns()
    {
        $brand = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator']);

        $campaign = Campaign::create([
            'user_id' => $brand->id,
            'title' => 'Brand Campaign X',
            'type' => 'video',
            'slots' => 5,
            'desc' => 'Deskripsi campaign',
            'budget' => 1000000,
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaign->id,
            'platform' => 'TikTok',
            'views_claimed' => 50000,
            'video_url' => 'https://tiktok.com/@creator/video/1234',
            'analytics_proof_path' => 'proofs/proof.jpg',
            'status' => 'pending',
        ]);

        $this->actingAs($brand);

        $response = $this->get(route('brand.submissions'));
        $response->assertStatus(200);
        $response->assertViewIs('brand.submissions.index');
        $response->assertSee('Brand Campaign X');
        $response->assertSee($creator->name);
    }

    /** @test */
    public function brand_user_cannot_view_submissions_of_other_brands_campaigns()
    {
        $brand1 = User::factory()->create(['role' => 'brand']);
        $brand2 = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator']);

        $campaignOfBrand2 = Campaign::create([
            'user_id' => $brand2->id,
            'title' => 'Brand 2 Campaign',
            'type' => 'video',
            'slots' => 5,
            'desc' => 'Deskripsi campaign',
            'budget' => 1000000,
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaignOfBrand2->id,
            'platform' => 'TikTok',
            'views_claimed' => 50000,
            'video_url' => 'https://tiktok.com/@creator/video/1234',
            'analytics_proof_path' => 'proofs/proof.jpg',
            'status' => 'pending',
        ]);

        $this->actingAs($brand1);

        $response = $this->get(route('brand.submissions'));
        $response->assertStatus(200);
        $response->assertDontSee('Brand 2 Campaign');
    }

    /** @test */
    public function brand_user_can_approve_submission_atomically()
    {
        $brand = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator', 'balance' => 1000]);

        $campaign = Campaign::create([
            'user_id' => $brand->id,
            'title' => 'Brand Campaign',
            'type' => 'video',
            'slots' => 5,
            'desc' => 'Deskripsi campaign',
            'budget' => 1000000,
            'price_per_1k' => 20000, // Rp 20,000 per 1k views
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaign->id,
            'platform' => 'TikTok',
            'views_claimed' => 50000, // 50 * 20,000 = Rp 1,000,000 reward
            'video_url' => 'https://tiktok.com/@creator/video/123',
            'analytics_proof_path' => 'proofs/proof.jpg',
            'status' => 'pending',
        ]);

        $this->actingAs($brand);

        $response = $this->from(route('brand.submissions'))
            ->post(route('brand.submissions.approve', $submission->id));
        $response->assertRedirect(route('brand.submissions'));
        $response->assertSessionHas('success');

        // Verify status and calculated reward
        $submission->refresh();
        $this->assertEquals('approved', $submission->status);
        $this->assertEquals(1000000, $submission->estimated_reward);

        // Verify creator balance has been increased by the exact reward amount (1000 original + 1000000 reward = 1001000)
        $creator->refresh();
        $this->assertEquals(1001000, $creator->balance);
    }

    /** @test */
    public function brand_user_cannot_approve_already_processed_submission()
    {
        $brand = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator', 'balance' => 0]);

        $campaign = Campaign::create([
            'user_id' => $brand->id,
            'title' => 'Campaign',
            'type' => 'video',
            'slots' => 5,
            'desc' => 'Deskripsi',
            'budget' => 500000,
            'price_per_1k' => 10000,
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaign->id,
            'platform' => 'TikTok',
            'views_claimed' => 10000,
            'video_url' => 'https://tiktok.com/@creator/video/123',
            'analytics_proof_path' => 'proofs/proof.jpg',
            'status' => 'approved', // already approved
            'estimated_reward' => 100000,
        ]);

        $this->actingAs($brand);

        $response = $this->post(route('brand.submissions.approve', $submission->id));
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Submission ini sudah diproses sebelumnya.');

        // Creator balance shouldn't change
        $creator->refresh();
        $this->assertEquals(0, $creator->balance);
    }

    /** @test */
    public function brand_user_can_reject_submission_with_reason()
    {
        $brand = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator']);

        $campaign = Campaign::create([
            'user_id' => $brand->id,
            'title' => 'Campaign Promo',
            'type' => 'video',
            'slots' => 5,
            'desc' => 'Desc',
            'budget' => 500000,
            'price_per_1k' => 10000,
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaign->id,
            'platform' => 'TikTok',
            'views_claimed' => 10000,
            'video_url' => 'https://tiktok.com/@creator/video/123',
            'analytics_proof_path' => 'proofs/proof.jpg',
            'status' => 'pending',
        ]);

        $this->actingAs($brand);

        $response = $this->from(route('brand.submissions'))->post(route('brand.submissions.reject', $submission->id), [
            'rejection_reason' => 'Video resolusi rendah dan tidak menyebutkan nama brand.',
        ]);

        $response->assertRedirect(route('brand.submissions'));
        $response->assertSessionHas('warning');

        $submission->refresh();
        $this->assertEquals('rejected', $submission->status);
        $this->assertEquals('Video resolusi rendah dan tidak menyebutkan nama brand.', $submission->rejection_reason);
    }

    /** @test */
    public function brand_user_cannot_reject_without_reason()
    {
        $brand = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator']);

        $campaign = Campaign::create([
            'user_id' => $brand->id,
            'title' => 'Campaign Promo',
            'type' => 'video',
            'slots' => 5,
            'desc' => 'Desc',
            'budget' => 500000,
            'price_per_1k' => 10000,
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaign->id,
            'platform' => 'TikTok',
            'views_claimed' => 10000,
            'video_url' => 'https://tiktok.com/@creator/video/123',
            'analytics_proof_path' => 'proofs/proof.jpg',
            'status' => 'pending',
        ]);

        $this->actingAs($brand);

        $response = $this->post(route('brand.submissions.reject', $submission->id), [
            'rejection_reason' => '',
        ]);

        $response->assertSessionHasErrors('rejection_reason');
        
        $submission->refresh();
        $this->assertEquals('pending', $submission->status);
    }

    /** @test */
    public function brand_user_cannot_approve_submission_of_other_brand_campaign()
    {
        $brand1 = User::factory()->create(['role' => 'brand']);
        $brand2 = User::factory()->create(['role' => 'brand']);
        $creator = User::factory()->create(['role' => 'kreator']);

        $campaignOfBrand2 = Campaign::create([
            'user_id' => $brand2->id,
            'title' => 'Brand 2 Campaign',
            'type' => 'video',
            'slots' => 5,
            'desc' => 'Deskripsi campaign',
            'budget' => 1000000,
            'price_per_1k' => 10000,
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaignOfBrand2->id,
            'platform' => 'TikTok',
            'views_claimed' => 50000,
            'video_url' => 'https://tiktok.com/@creator/video/1234',
            'analytics_proof_path' => 'proofs/proof.jpg',
            'status' => 'pending',
        ]);

        $this->actingAs($brand1);

        $response = $this->post(route('brand.submissions.approve', $submission->id));
        $response->assertStatus(403);

        $submission->refresh();
        $this->assertEquals('pending', $submission->status);
    }
}
