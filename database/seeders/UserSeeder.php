<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin.ekahal@gmail.com'],
            [
                'name' => 'Ekahal Admin',
                'password' => 'Admin@123',
                'role' => UserRole::Admin,
            ],
        );

        User::updateOrCreate(
            ['email' => 'manav.ekahal@gmail.com'],
            [
                'name' => 'Manav User',
                'password' => 'Manav@123',
                'role' => UserRole::User,
            ],
        );
    }
}
