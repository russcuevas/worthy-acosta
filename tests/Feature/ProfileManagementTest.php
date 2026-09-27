<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $assistantUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'name' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admin@test.ph',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->assistantUser = User::create([
            'name' => 'Assistant Test',
            'username' => 'assistanttest',
            'email' => 'assistant@test.ph',
            'password' => Hash::make('password123'),
            'role' => 'assistant',
        ]);
    }

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');

        $updateResp = $this->postJson('/profile/update', [
            'name' => 'Hacker',
            'username' => 'hacker',
            'email' => 'hacker@test.ph',
        ]);
        $updateResp->assertStatus(401);

        $passResp = $this->postJson('/profile/password', [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $passResp->assertStatus(401);
    }

    public function test_admin_can_view_profile_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/profile');
        $response->assertStatus(200);
        $response->assertSee('Admin Test');
        $response->assertSee('admintest');
        $response->assertSee('admin@test.ph');
    }

    public function test_assistant_can_view_profile_page(): void
    {
        $response = $this->actingAs($this->assistantUser)->get('/assistant/profile');
        $response->assertStatus(200);
        $response->assertSee('Assistant Test');
        $response->assertSee('assistanttest');
        $response->assertSee('assistant@test.ph');
    }

    public function test_user_can_update_profile_info(): void
    {
        $response = $this->actingAs($this->assistantUser)->postJson('/profile/update', [
            'name' => 'Maria Santos',
            'username' => 'mariasantos',
            'email' => 'maria@worthyacosta.ph',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'user' => [
                'name' => 'Maria Santos',
                'username' => 'mariasantos',
                'email' => 'maria@worthyacosta.ph',
                'initials' => 'MA',
            ],
        ]);

        $this->assistantUser->refresh();
        $this->assertEquals('Maria Santos', $this->assistantUser->name);
        $this->assertEquals('mariasantos', $this->assistantUser->username);
        $this->assertEquals('maria@worthyacosta.ph', $this->assistantUser->email);
    }

    public function test_user_cannot_take_existing_username_or_email(): void
    {
        $response = $this->actingAs($this->assistantUser)->postJson('/profile/update', [
            'name' => 'Assistant New',
            'username' => 'admintest', // Already used by adminUser
            'email' => 'assistant@test.ph',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['username']);

        $responseEmail = $this->actingAs($this->assistantUser)->postJson('/profile/update', [
            'name' => 'Assistant New',
            'username' => 'assistanttest',
            'email' => 'admin@test.ph', // Already used by adminUser
        ]);

        $responseEmail->assertStatus(422);
        $responseEmail->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $response = $this->actingAs($this->adminUser)->postJson('/profile/password', [
            'current_password' => 'password123',
            'password' => 'newsecret2026',
            'password_confirmation' => 'newsecret2026',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Password changed successfully!',
        ]);

        $this->adminUser->refresh();
        $this->assertTrue(Hash::check('newsecret2026', $this->adminUser->password));
    }

    public function test_user_cannot_change_password_with_wrong_current_password(): void
    {
        $response = $this->actingAs($this->adminUser)->postJson('/profile/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newsecret2026',
            'password_confirmation' => 'newsecret2026',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonValidationErrors(['current_password']);

        $this->adminUser->refresh();
        $this->assertTrue(Hash::check('password123', $this->adminUser->password));
    }

    public function test_user_cannot_change_password_with_mismatched_confirmation(): void
    {
        $response = $this->actingAs($this->adminUser)->postJson('/profile/password', [
            'current_password' => 'password123',
            'password' => 'newsecret2026',
            'password_confirmation' => 'differentsecret',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_login_with_updated_password(): void
    {
        // 1. Update password
        $this->actingAs($this->assistantUser)->postJson('/profile/password', [
            'current_password' => 'password123',
            'password' => 'securepass456',
            'password_confirmation' => 'securepass456',
        ]);

        // 2. Logout
        $this->post('/logout');
        $this->assertGuest();

        // 3. Attempt login with old password -> should fail
        $failLogin = $this->post('/authenticate', [
            'email' => 'assistanttest',
            'password' => 'password123',
        ]);
        $this->assertGuest();

        // 4. Attempt login with new password -> should succeed and redirect to assistant portal
        $successLogin = $this->post('/authenticate', [
            'email' => 'assistanttest',
            'password' => 'securepass456',
        ]);
        $this->assertAuthenticatedAs($this->assistantUser);
        $successLogin->assertRedirect('/assistant/electoral');
    }
}
