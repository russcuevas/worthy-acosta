<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthAndRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure test users exist
        User::updateOrCreate(
            ['email' => 'admin@worthyacosta.ph'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'assistant@worthyacosta.ph'],
            [
                'name' => 'Assistant Officer',
                'username' => 'assistant',
                'password' => Hash::make('password'),
                'role' => 'assistant',
            ]
        );
    }

    public function test_guest_can_see_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign in to');
        $response->assertSee('Administrator');
        $response->assertSee('Assistant');
    }

    public function test_guest_cannot_access_admin_portal(): void
    {
        $response = $this->get('/admin/electoral');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_assistant_portal(): void
    {
        $response = $this->get('/assistant/electoral');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_login_with_email_and_redirects_to_admin_electoral(): void
    {
        $response = $this->post('/authenticate', [
            'email' => 'admin@worthyacosta.ph',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.electoral'));
        $this->assertAuthenticated();
    }

    public function test_admin_can_login_with_username_and_redirects_to_admin_electoral(): void
    {
        $response = $this->post('/authenticate', [
            'email' => 'admin',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.electoral'));
        $this->assertAuthenticated();
    }

    public function test_assistant_can_login_with_email_and_redirects_to_assistant_electoral(): void
    {
        $response = $this->post('/authenticate', [
            'email' => 'assistant@worthyacosta.ph',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('assistant.electoral'));
        $this->assertAuthenticated();
    }

    public function test_assistant_can_login_with_username_and_redirects_to_assistant_electoral(): void
    {
        $response = $this->post('/authenticate', [
            'email' => 'assistant',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('assistant.electoral'));
        $this->assertAuthenticated();
    }

    public function test_admin_cannot_access_assistant_routes_and_is_redirected(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/assistant/electoral');
        $response->assertRedirect(route('admin.electoral'));
        $response->assertSessionHas('info');
    }

    public function test_assistant_cannot_access_admin_routes_and_is_redirected(): void
    {
        $assistant = User::where('role', 'assistant')->first();

        $response = $this->actingAs($assistant)->get('/admin/electoral');
        $response->assertRedirect(route('assistant.electoral'));
        $response->assertSessionHas('error');
    }

    public function test_authenticated_admin_visiting_login_redirects_to_admin(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/login');
        $response->assertRedirect(route('admin.electoral'));
    }

    public function test_authenticated_assistant_visiting_login_redirects_to_assistant(): void
    {
        $assistant = User::where('role', 'assistant')->first();

        $response = $this->actingAs($assistant)->get('/login');
        $response->assertRedirect(route('assistant.electoral'));
    }

    public function test_invalid_login_credentials_fail(): void
    {
        $response = $this->post('/authenticate', [
            'email' => 'admin@worthyacosta.ph',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post('/logout');
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
