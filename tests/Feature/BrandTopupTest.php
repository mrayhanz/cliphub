<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Deposit;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandTopupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Configure fake Midtrans server key
        config(['midtrans.server_key' => 'fake_server_key']);
        config(['midtrans.is_production' => false]);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    private function getSignatureKey($orderId, $statusCode, $grossAmount)
    {
        $serverKey = config('midtrans.server_key');
        return hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
    }

    /** @test */
    public function brand_user_can_view_finance_page()
    {
        $user = User::factory()->create(['role' => 'brand', 'balance' => 250000]);
        $this->actingAs($user);

        // Create mock active campaigns
        Campaign::create([
            'user_id' => $user->id,
            'title' => 'Campaign Test A',
            'budget' => 500000,
            'status' => 'active',
        ]);

        Campaign::create([
            'user_id' => $user->id,
            'title' => 'Campaign Test B',
            'budget' => 300000,
            'status' => 'draft', // Draft should not count as escrow
        ]);

        // Create some deposits
        $deposit1 = Deposit::create([
            'user_id' => $user->id,
            'order_id' => 'DEP-' . $user->id . '-1',
            'amount' => 100000,
            'status' => 'success',
        ]);

        $deposit2 = Deposit::create([
            'user_id' => $user->id,
            'order_id' => 'DEP-' . $user->id . '-2',
            'amount' => 50000,
            'status' => 'pending',
            'snap_token' => 'mock-token',
        ]);

        $response = $this->get(route('brand.finance'));
        $response->assertStatus(200);
        
        // Check view variables
        $response->assertViewHas('balance', 250000);
        $response->assertViewHas('escrow', 500000); // Only active campaign budget
        $response->assertViewHas('activeCampaigns');
        $response->assertViewHas('deposits');
        
        $response->assertSee('Campaign Test A');
        $response->assertDontSee('Campaign Test B'); // Draft shouldn't be listed under active escrow
    }

    /** @test */
    public function brand_user_can_initiate_topup()
    {
        $user = User::factory()->create(['role' => 'brand']);
        $this->actingAs($user);

        // Mock Midtrans Snap token generation
        $snapMock = \Mockery::mock('alias:Midtrans\Snap');
        $snapMock->shouldReceive('getSnapToken')
            ->once()
            ->andReturn('fake-snap-token-123');

        $response = $this->postJson(route('brand.finance.topup'), [
            'amount' => 50000
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'snap_token' => 'fake-snap-token-123'
        ]);

        $this->assertDatabaseHas('deposits', [
            'user_id' => $user->id,
            'amount' => 50000,
            'status' => 'pending',
            'snap_token' => 'fake-snap-token-123'
        ]);
    }

    /** @test */
    public function midtrans_webhook_updates_deposit_to_success()
    {
        $user = User::factory()->create(['role' => 'brand', 'balance' => 0]);
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'order_id' => 'DEP-12345',
            'amount' => 100000,
            'status' => 'pending',
        ]);

        $statusCode = '200';
        $grossAmount = '100000.00';
        $signatureKey = $this->getSignatureKey($deposit->order_id, $statusCode, $grossAmount);

        $response = $this->postJson(route('midtrans.webhook'), [
            'order_id' => $deposit->order_id,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'transaction_status' => 'settlement',
            'payment_type' => 'gopay',
            'signature_key' => $signatureKey,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'OK']);

        // Verify deposit updated
        $this->assertEquals('success', $deposit->fresh()->status);
        $this->assertEquals('gopay', $deposit->fresh()->payment_type);

        // Verify user balance incremented
        $this->assertEquals(100000, $user->fresh()->balance);
    }

    /** @test */
    public function midtrans_webhook_is_idempotent()
    {
        $user = User::factory()->create(['role' => 'brand', 'balance' => 0]);
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'order_id' => 'DEP-12345',
            'amount' => 100000,
            'status' => 'pending',
        ]);

        $statusCode = '200';
        $grossAmount = '100000.00';
        $signatureKey = $this->getSignatureKey($deposit->order_id, $statusCode, $grossAmount);

        // Send first webhook
        $response1 = $this->postJson(route('midtrans.webhook'), [
            'order_id' => $deposit->order_id,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'transaction_status' => 'settlement',
            'payment_type' => 'gopay',
            'signature_key' => $signatureKey,
        ]);
        $response1->assertStatus(200);

        // Send second duplicate webhook
        $response2 = $this->postJson(route('midtrans.webhook'), [
            'order_id' => $deposit->order_id,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'transaction_status' => 'settlement',
            'payment_type' => 'gopay',
            'signature_key' => $signatureKey,
        ]);
        $response2->assertStatus(200);
        $response2->assertJson(['message' => 'Already updated']);

        // Verify balance was ONLY incremented once
        $this->assertEquals(100000, $user->fresh()->balance);
    }

    /** @test */
    public function midtrans_webhook_handles_expired_status()
    {
        $user = User::factory()->create(['role' => 'brand', 'balance' => 0]);
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'order_id' => 'DEP-12345',
            'amount' => 100000,
            'status' => 'pending',
        ]);

        $statusCode = '200';
        $grossAmount = '100000.00';
        $signatureKey = $this->getSignatureKey($deposit->order_id, $statusCode, $grossAmount);

        $response = $this->postJson(route('midtrans.webhook'), [
            'order_id' => $deposit->order_id,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'transaction_status' => 'expire',
            'signature_key' => $signatureKey,
        ]);

        $response->assertStatus(200);
        $this->assertEquals('expired', $deposit->fresh()->status);
        $this->assertEquals(0, $user->fresh()->balance); // Balance not updated
    }

    /** @test */
    public function midtrans_webhook_handles_failed_status()
    {
        $user = User::factory()->create(['role' => 'brand', 'balance' => 0]);
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'order_id' => 'DEP-12345',
            'amount' => 100000,
            'status' => 'pending',
        ]);

        $statusCode = '200';
        $grossAmount = '100000.00';
        $signatureKey = $this->getSignatureKey($deposit->order_id, $statusCode, $grossAmount);

        $response = $this->postJson(route('midtrans.webhook'), [
            'order_id' => $deposit->order_id,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'transaction_status' => 'cancel',
            'signature_key' => $signatureKey,
        ]);

        $response->assertStatus(200);
        $this->assertEquals('failed', $deposit->fresh()->status);
        $this->assertEquals(0, $user->fresh()->balance); // Balance not updated
    }

    /** @test */
    public function midtrans_webhook_invalid_signature_is_rejected()
    {
        $user = User::factory()->create(['role' => 'brand']);
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'order_id' => 'DEP-12345',
            'amount' => 100000,
            'status' => 'pending',
        ]);

        $response = $this->postJson(route('midtrans.webhook'), [
            'order_id' => $deposit->order_id,
            'status_code' => '200',
            'gross_amount' => '100000.00',
            'transaction_status' => 'settlement',
            'signature_key' => 'invalid_signature_key',
        ]);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'Invalid signature']);
    }

    /** @test */
    public function brand_user_can_complete_topup_via_callback()
    {
        $user = User::factory()->create(['role' => 'brand', 'balance' => 50000]);
        $this->actingAs($user);

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'order_id' => 'DEP-12345',
            'amount' => 100000,
            'status' => 'pending',
        ]);

        $response = $this->postJson(route('brand.finance.topup.callback'), [
            'order_id' => $deposit->order_id,
            'payment_type' => 'credit_card',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertEquals('success', $deposit->fresh()->status);
        $this->assertEquals('credit_card', $deposit->fresh()->payment_type);
        $this->assertEquals(150000, $user->fresh()->balance); // 50000 + 100000
    }

    /** @test */
    public function brand_user_callback_is_idempotent()
    {
        $user = User::factory()->create(['role' => 'brand', 'balance' => 50000]);
        $this->actingAs($user);

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'order_id' => 'DEP-12345',
            'amount' => 100000,
            'status' => 'pending',
        ]);

        // Send first callback
        $response1 = $this->postJson(route('brand.finance.topup.callback'), [
            'order_id' => $deposit->order_id,
            'payment_type' => 'credit_card',
        ]);
        $response1->assertStatus(200);

        // Send second callback
        $response2 = $this->postJson(route('brand.finance.topup.callback'), [
            'order_id' => $deposit->order_id,
            'payment_type' => 'credit_card',
        ]);
        $response2->assertStatus(200);

        // Verify balance was ONLY incremented once
        $this->assertEquals(150000, $user->fresh()->balance);
    }
}
