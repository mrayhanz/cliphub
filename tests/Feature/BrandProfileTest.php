<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\BrandProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure storage fake for public disk
        Storage::fake('public');
    }

    /** @test */
    public function brand_user_can_view_profile_page()
    {
        $user = User::factory()->create(['role' => 'brand']);
        $this->actingAs($user);

        $response = $this->get(route('brand.profile'));
        $response->assertStatus(200);
        $response->assertViewIs('brand.profile.index');
        $response->assertViewHas('otherBrands');
    }

    /** @test */
    public function brand_user_can_update_profile_and_upload_logo()
    {
        $user = User::factory()->create(['role' => 'brand']);
        $this->actingAs($user);

        $logo = UploadedFile::fake()->image('logo.png');

        $data = [
            'company_name' => 'Test Brand',
            'contact_name' => 'John Doe',
            'phone' => '08123456789',
            'address' => '123 Test St',
            'website' => 'https://example.com',
            'logo' => $logo,
        ];

        $response = $this->put(route('brand.profile.update'), $data);
        $response->assertRedirect(route('brand.profile'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('brand_profiles', [
            'user_id' => $user->id,
            'company_name' => 'Test Brand',
            'contact_name' => 'John Doe',
            'phone' => '08123456789',
            'address' => '123 Test St',
            'website' => 'https://example.com',
        ]);

        $profile = BrandProfile::first();
        $this->assertNotNull($profile->logo_path);
        // Verify file stored on fake disk
        Storage::disk('public')->assertExists($profile->logo_path);
    }

    /** @test */
    public function logo_validation_rejects_invalid_file()
    {
        $user = User::factory()->create(['role' => 'brand']);
        $this->actingAs($user);

        $invalidFile = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $data = [
            'company_name' => 'Test Brand',
            'logo' => $invalidFile,
        ];

        $response = $this->from(route('brand.profile'))->put(route('brand.profile.update'), $data);
        $response->assertRedirect(route('brand.profile'));
        $response->assertSessionHasErrors('logo');
    }

    /** @test */
    public function brand_user_can_update_password_with_correct_current_password()
    {
        $user = User::factory()->create([
            'role' => 'brand',
            'password' => bcrypt('old_password'),
        ]);
        $this->actingAs($user);

        $response = $this->put(route('brand.profile.password'), [
            'current_password' => 'old_password',
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
        ]);

        $response->assertRedirect(route('brand.profile'));
        $response->assertSessionHas('success');

        // Check password changed
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new_password123', $user->fresh()->password));
    }

    /** @test */
    public function brand_user_cannot_update_password_with_incorrect_current_password()
    {
        $user = User::factory()->create([
            'role' => 'brand',
            'password' => bcrypt('old_password'),
        ]);
        $this->actingAs($user);

        $response = $this->put(route('brand.profile.password'), [
            'current_password' => 'wrong_password',
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('old_password', $user->fresh()->password));
    }

    /** @test */
    public function brand_user_cannot_update_password_with_mismatched_confirmation()
    {
        $user = User::factory()->create([
            'role' => 'brand',
            'password' => bcrypt('old_password'),
        ]);
        $this->actingAs($user);

        $response = $this->put(route('brand.profile.password'), [
            'current_password' => 'old_password',
            'password' => 'new_password123',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('old_password', $user->fresh()->password));
    }
}
