<?php

namespace Tests\Feature\Product;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_product(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);

        Sanctum::actingAs($user);

        $response = $this->post(route('admin.products.store'), [
            'title' => 'Test Product',
            'description' => '<p>Valid description content here.</p>',
            'price' => '19.99',
            'date_available' => now()->addDay()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'title' => 'Test Product',
            'user_id' => $user->id,
        ]);
    }

    public function test_standard_user_cannot_update_another_users_product(): void
    {
        $owner = User::factory()->create(['role' => UserRole::User]);
        $other = User::factory()->create(['role' => UserRole::User]);
        $product = Product::factory()->create(['user_id' => $owner->id]);

        Sanctum::actingAs($other);

        $this->put(route('admin.products.update', $product), [
                'title' => 'Hacked',
                'description' => '<p>Attempted change.</p>',
                'price' => '9.99',
                'date_available' => now()->toDateString(),
            ])
            ->assertForbidden();
    }

    public function test_delete_soft_deletes_product(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $product = Product::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $this->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertNull(Product::find($product->id));
        $this->assertNotNull(Product::withTrashed()->find($product->id));
    }

    public function test_available_date_range_filters_products(): void
    {
        $user = User::factory()->create(['role' => UserRole::Admin]);

        Product::factory()->create([
            'user_id' => $user->id,
            'title' => 'Early Item',
            'date_available' => '2026-05-10',
        ]);
        Product::factory()->create([
            'user_id' => $user->id,
            'title' => 'Late Item',
            'date_available' => '2026-06-01',
        ]);

        Sanctum::actingAs($user);

        $this->get(route('admin.products.index', [
            'available_from' => '2026-05-01',
            'available_to' => '2026-05-20',
        ]))
            ->assertOk()
            ->assertSee('Early Item')
            ->assertDontSee('Late Item');
    }

    public function test_search_filters_products(): void
    {
        $user = User::factory()->create(['role' => UserRole::Admin]);
        Product::factory()->create(['user_id' => $user->id, 'title' => 'Unique Gadget X']);
        Product::factory()->create(['user_id' => $user->id, 'title' => 'Other Item']);

        Sanctum::actingAs($user);

        $this->get(route('admin.products.index', ['search' => 'Unique Gadget']))
            ->assertOk()
            ->assertSee('Unique Gadget X')
            ->assertDontSee('Other Item');
    }
}
