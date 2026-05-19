<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandCampaignLaunchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function brand_user_can_view_campaigns_list_with_search_and_filters()
    {
        $user = User::factory()->create(['role' => 'brand']);
        $this->actingAs($user);

        $activeCampaign = Campaign::create([
            'user_id' => $user->id,
            'title' => 'Active Promo WIB',
            'type' => 'video',
            'slots' => 10,
            'desc' => 'Deskripsi aktif',
            'budget' => 500000,
            'status' => 'active',
        ]);

        $draftCampaign = Campaign::create([
            'user_id' => $user->id,
            'title' => 'Draft Promo Wardah',
            'type' => 'clip',
            'slots' => 5,
            'desc' => 'Deskripsi draft',
            'budget' => 300000,
            'status' => 'draft',
        ]);

        // 1. View all
        $response = $this->get(route('brand.campaigns'));
        $response->assertStatus(200);
        $response->assertSee('Active Promo WIB');
        $response->assertSee('Draft Promo Wardah');

        // 2. Filter Active
        $response = $this->get(route('brand.campaigns', ['status' => 'active']));
        $response->assertStatus(200);
        $response->assertSee('Active Promo WIB');
        $response->assertDontSee('Draft Promo Wardah');

        // 3. Search Title
        $response = $this->get(route('brand.campaigns', ['search' => 'Wardah']));
        $response->assertStatus(200);
        $response->assertSee('Draft Promo Wardah');
        $response->assertDontSee('Active Promo WIB');
    }

    /** @test */
    public function brand_user_can_create_draft_campaign_without_affecting_balance()
    {
        $user = User::factory()->create([
            'role' => 'brand',
            'balance' => 1000000,
        ]);
        $this->actingAs($user);

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $data = [
            'title' => 'Promo Tokopedia',
            'type' => 'video',
            'slots' => 20,
            'thumbnail' => $thumbnail,
            'desc' => 'Tujuan singkat campaign',
            'full_brief' => 'Brief detail',
            'donts' => 'Terlarang',
            'deadline' => '2026-06-01',
            'video_length' => '30 Detik',
            'link' => 'https://tokopedia.com',
            'platform' => 'tiktok',
            'budget' => 600000,
            'price_per_1k' => 10000,
            'action' => 'draft',
        ];

        $response = $this->post(route('brand.campaigns.store'), $data);
        $response->assertRedirect(route('brand.campaigns'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('campaigns', [
            'user_id' => $user->id,
            'title' => 'Promo Tokopedia',
            'status' => 'draft',
            'budget' => 600000,
        ]);

        $this->assertEquals(1000000, $user->fresh()->balance);
    }

    /** @test */
    public function brand_user_can_launch_active_campaign_with_sufficient_balance()
    {
        $user = User::factory()->create([
            'role' => 'brand',
            'balance' => 1000000,
        ]);
        $this->actingAs($user);

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $data = [
            'title' => 'Promo Gojek',
            'type' => 'clip',
            'slots' => 15,
            'thumbnail' => $thumbnail,
            'desc' => 'Tujuan singkat',
            'full_brief' => 'Brief detail',
            'donts' => 'Terlarang',
            'deadline' => '2026-06-01',
            'video_length' => '60 Detik',
            'link' => 'https://gojek.com',
            'platform' => 'all',
            'budget' => 400000,
            'price_per_1k' => 15000,
            'action' => 'active',
        ];

        $response = $this->post(route('brand.campaigns.store'), $data);
        $response->assertRedirect(route('brand.campaigns'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('campaigns', [
            'user_id' => $user->id,
            'title' => 'Promo Gojek',
            'status' => 'active',
            'budget' => 400000,
        ]);

        $this->assertEquals(600000, $user->fresh()->balance);
    }

    /** @test */
    public function brand_user_cannot_launch_active_campaign_with_insufficient_balance()
    {
        $user = User::factory()->create([
            'role' => 'brand',
            'balance' => 300000,
        ]);
        $this->actingAs($user);

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $data = [
            'title' => 'Promo Gojek Mahal',
            'type' => 'clip',
            'slots' => 15,
            'thumbnail' => $thumbnail,
            'desc' => 'Tujuan singkat',
            'full_brief' => 'Brief detail',
            'donts' => 'Terlarang',
            'deadline' => '2026-06-01',
            'video_length' => '60 Detik',
            'link' => 'https://gojek.com',
            'platform' => 'all',
            'budget' => 400000,
            'price_per_1k' => 15000,
            'action' => 'active',
        ];

        $response = $this->from(route('brand.campaigns.create'))
            ->post(route('brand.campaigns.store'), $data);

        $response->assertRedirect(route('brand.campaigns.create'));
        $response->assertSessionHasErrors('budget');

        $this->assertDatabaseMissing('campaigns', [
            'title' => 'Promo Gojek Mahal',
        ]);

        $this->assertEquals(300000, $user->fresh()->balance);
    }

    /** @test */
    public function brand_user_can_activate_draft_campaign_with_sufficient_balance()
    {
        $user = User::factory()->create([
            'role' => 'brand',
            'balance' => 1000000,
        ]);
        $this->actingAs($user);

        $campaign = Campaign::create([
            'user_id' => $user->id,
            'title' => 'Draft Tokopedia WIB',
            'type' => 'video',
            'slots' => 10,
            'budget' => 600000,
            'status' => 'draft',
        ]);

        $response = $this->put(route('brand.campaigns.activate', $campaign->id));
        $response->assertRedirect(route('brand.campaigns'));
        $response->assertSessionHas('success');

        $this->assertEquals('active', $campaign->fresh()->status);
        $this->assertEquals(400000, $user->fresh()->balance);
    }

    /** @test */
    public function brand_user_cannot_activate_draft_campaign_with_insufficient_balance()
    {
        $user = User::factory()->create([
            'role' => 'brand',
            'balance' => 400000,
        ]);
        $this->actingAs($user);

        $campaign = Campaign::create([
            'user_id' => $user->id,
            'title' => 'Draft Tokopedia WIB Mahal',
            'type' => 'video',
            'slots' => 10,
            'budget' => 600000,
            'status' => 'draft',
        ]);

        $response = $this->from(route('brand.campaigns'))
            ->put(route('brand.campaigns.activate', $campaign->id));

        $response->assertRedirect(route('brand.campaigns'));
        $response->assertSessionHas('error');

        $this->assertEquals('draft', $campaign->fresh()->status);
        $this->assertEquals(400000, $user->fresh()->balance);
    }

    /** @test */
    public function brand_user_can_cancel_active_campaign_and_receive_refund()
    {
        $user = User::factory()->create([
            'role' => 'brand',
            'balance' => 600000,
        ]);
        $this->actingAs($user);

        $campaign = Campaign::create([
            'user_id' => $user->id,
            'title' => 'Active Gojek Promo',
            'type' => 'video',
            'slots' => 10,
            'budget' => 400000,
            'status' => 'active',
        ]);

        $response = $this->put(route('brand.campaigns.cancel', $campaign->id));
        $response->assertRedirect(route('brand.campaigns'));
        $response->assertSessionHas('success');

        $this->assertEquals('cancelled', $campaign->fresh()->status);
        $this->assertEquals(1000000, $user->fresh()->balance);
    }

    /** @test */
    public function brand_user_cannot_manage_other_brands_campaigns()
    {
        $brand1 = User::factory()->create(['role' => 'brand']);
        $brand2 = User::factory()->create(['role' => 'brand', 'balance' => 1000000]);

        $campaign = Campaign::create([
            'user_id' => $brand2->id,
            'title' => 'Brand 2 Promo',
            'type' => 'video',
            'slots' => 10,
            'budget' => 400000,
            'status' => 'draft',
        ]);

        $this->actingAs($brand1);

        $response = $this->put(route('brand.campaigns.activate', $campaign->id));
        $response->assertStatus(403);

        $response = $this->put(route('brand.campaigns.cancel', $campaign->id));
        $response->assertStatus(403);
    }

    /** @test */
    public function brand_user_can_view_their_own_campaign_details_page()
    {
        $user = User::factory()->create(['role' => 'brand']);
        $this->actingAs($user);

        $campaign = Campaign::create([
            'user_id' => $user->id,
            'title' => 'My Premium Campaign WIB',
            'type' => 'video',
            'slots' => 10,
            'budget' => 500000,
            'desc' => 'Tujuan brief premium ini',
            'full_brief' => 'Instruksi penuh cara kerja',
            'donts' => 'Terlarang dilakukan',
            'status' => 'active',
        ]);

        $creator = User::factory()->create([
            'role' => 'kreator',
            'name' => 'Sarah TikToker',
        ]);

        $submission = \App\Models\Submission::create([
            'user_id' => $creator->id,
            'campaign_id' => $campaign->id,
            'platform' => 'TikTok',
            'views_claimed' => 12500,
            'video_url' => 'https://tiktok.com/@sarah/video/123',
            'analytics_proof_path' => 'proofs/123.jpg',
            'estimated_reward' => 250000,
            'status' => 'pending',
        ]);

        $response = $this->get(route('brand.campaigns.show', $campaign->id));
        $response->assertStatus(200);
        $response->assertViewIs('brand.campaigns.show');
        $response->assertSee('My Premium Campaign WIB');
        $response->assertSee('Tujuan brief premium ini');
        $response->assertSee('Instruksi penuh cara kerja');
        $response->assertSee('Terlarang dilakukan');

        // Verify creator submission is listed
        $response->assertSee('Sarah TikToker');
        $response->assertSee('TikTok');
        $response->assertSee('12.500');
        $response->assertSee('250.000');
        $response->assertSee('https://tiktok.com/@sarah/video/123');
    }

    /** @test */
    public function brand_user_cannot_view_other_brands_campaign_details_page()
    {
        $brand1 = User::factory()->create(['role' => 'brand']);
        $brand2 = User::factory()->create(['role' => 'brand']);

        $campaign = Campaign::create([
            'user_id' => $brand2->id,
            'title' => 'Brand 2 Top Secret Campaign',
            'type' => 'video',
            'slots' => 10,
            'budget' => 500000,
            'status' => 'active',
        ]);

        $this->actingAs($brand1);

        $response = $this->get(route('brand.campaigns.show', $campaign->id));
        $response->assertStatus(403);
    }
}
