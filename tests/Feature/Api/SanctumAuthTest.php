<?php

namespace Tests\Feature\Api;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SanctumAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(EnsureFrontendRequestsAreStateful::class);
    }

    public function test_api_login_returns_sanctum_token(): void
    {
        User::factory()->create([
            'email' => 'api@example.com',
            'password' => 'password123',
            'role' => UserRole::Admin,
        ]);

        $response = $this->postJson(route('api.login'), [
            'email' => 'api@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'token_type', 'user' => ['id', 'email', 'role']]);

        $this->assertEquals('Bearer', $response->json('token_type'));
    }

    public function test_authenticated_api_user_can_fetch_profile(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);

        Sanctum::actingAs($user);

        $this->getJson(route('api.user'))
            ->assertOk()
            ->assertJsonPath('user.email', $user->email)
            ->assertJsonPath('user.role', 'user');
    }

    public function test_api_logout_revokes_access(): void
    {
        User::factory()->create([
            'email' => 'logout@example.com',
            'password' => 'password123',
        ]);

        $token = $this->postJson(route('api.login'), [
            'email' => 'logout@example.com',
            'password' => 'password123',
        ])->json('token');

        $this->json('POST', route('api.logout'), [], [
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
        $this->assertNull(PersonalAccessToken::findToken($token));
    }
}
