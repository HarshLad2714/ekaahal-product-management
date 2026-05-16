<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin.ekahal@gmail.com')->first();
        $user = User::where('email', 'manav.ekahal@gmail.com')->first();

        if (! $admin || ! $user) {
            return;
        }

        $samples = [
            [
                'user_id' => $admin->id,
                'title' => 'Premium Wireless Headphones',
                'description' => '<p>High-fidelity audio with active noise cancellation and 30-hour battery life.</p>',
                'price' => 249.99,
                'date_available' => now()->addDays(3)->toDateString(),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Organic Cotton T-Shirt',
                'description' => '<p>Sustainably sourced cotton, relaxed fit, available in multiple colors.</p>',
                'price' => 29.50,
                'date_available' => now()->addWeek()->toDateString(),
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Smart Home Hub',
                'description' => '<p>Centralize your IoT devices with voice control and automation routines.</p>',
                'price' => 129.00,
                'date_available' => now()->addDays(14)->toDateString(),
            ],
        ];

        foreach ($samples as $data) {
            Product::updateOrCreate(
                ['title' => $data['title'], 'user_id' => $data['user_id']],
                $data,
            );
        }
    }
}
