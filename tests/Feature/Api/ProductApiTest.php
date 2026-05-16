<?php

namespace Tests\Feature\Api;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(EnsureFrontendRequestsAreStateful::class);
    }

    public function test_guest_cannot_access_products_api(): void
    {
        $this->getJson(route('api.products.index'))->assertUnauthorized();
    }

    public function test_user_can_create_product_via_api(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);

        Sanctum::actingAs($user);

        $this->postJson(route('api.products.store'), [
            'title' => 'API Product',
            'description' => '<p>Created through Sanctum protected API.</p>',
            'price' => '49.99',
            'date_available' => now()->addDay()->toDateString(),
        ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'API Product');
    }

    public function test_standard_user_cannot_update_other_users_product_via_api(): void
    {
        $owner = User::factory()->create(['role' => UserRole::User]);
        $other = User::factory()->create(['role' => UserRole::User]);
        $product = Product::factory()->create(['user_id' => $owner->id]);

        Sanctum::actingAs($other);

        $this->putJson(route('api.products.update', $product), [
            'title' => 'Blocked',
            'description' => '<p>Should not apply.</p>',
            'price' => '1.00',
            'date_available' => now()->toDateString(),
        ])->assertForbidden();
    }
}
