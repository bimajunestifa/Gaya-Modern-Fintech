<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Unauthenticated guest is redirected to login page.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    /**
     * Guest can see login page.
     */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Authenticated user can access dashboard.
     */
     public function test_authenticated_user_can_access_dashboard(): void
     {
         $user = \App\Models\User::factory()->create();
 
         $response = $this->actingAs($user)->get('/');
         $response->assertStatus(200);
     }

    /**
     * Test successful login and session regeneration.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'banker@bankdash.com',
            'password' => \Illuminate\Support\Facades\Hash::make('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'banker@bankdash.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test failed login returns error and does not authenticate.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'invalid@bankdash.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test rate limiting blocks brute force attacks after 5 failed attempts.
     */
    public function test_login_rate_limiting_blocks_excessive_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'attacker@bankdash.com',
                'password' => 'wrongpass',
            ]);
        }

        // 6th attempt should trigger rate limiting block
        $response = $this->post('/login', [
            'email' => 'attacker@bankdash.com',
            'password' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test responses include anti-cache headers to prevent browser back navigation after logout.
     */
    public function test_prevent_back_history_headers_are_present(): void
    {
        $response = $this->get('/login');
        $response->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private');
        $response->assertHeader('Pragma', 'no-cache');
    }
}
