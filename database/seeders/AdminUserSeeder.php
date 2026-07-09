<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('SEED_ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('SEED_ADMIN_NAME', 'Admin User'),
                'password' => Hash::make(env('SEED_ADMIN_PASSWORD', 'password')),
            ],
        );
    }
}
