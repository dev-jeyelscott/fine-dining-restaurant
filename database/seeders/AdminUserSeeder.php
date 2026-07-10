<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = config('admin.seed_user.name');
        $email = config('admin.seed_user.email');
        $password = config('admin.seed_user.password');

        if (! is_string($name) || $name === '') {
            $name = 'Restaurant Admin';
        }

        if (! is_string($email) || $email === '') {
            if (app()->isProduction()) {
                throw new RuntimeException('SEED_ADMIN_EMAIL must be configured before seeding the production admin user.');
            }

            $email = 'admin@example.com';
        }

        if (! is_string($password) || $password === '') {
            if (app()->isProduction()) {
                throw new RuntimeException('SEED_ADMIN_PASSWORD must be configured before seeding the production admin user.');
            }

            $password = 'password';
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ],
        );
    }
}
