<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->words(3, true),
            'description' => '<p>'.fake()->paragraph().'</p>',
            'price' => fake()->randomFloat(2, 5, 9999),
            'date_available' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
        ];
    }
}
