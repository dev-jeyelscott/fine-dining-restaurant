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

        if (! is_string($name) || trim($name) === '') {
            throw new RuntimeException('ADMIN_USER_NAME must be configured before seeding the admin user.');
        }

        if (! is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException('ADMIN_USER_EMAIL must contain a valid email address before seeding the admin user.');
        }

        if (! is_string($password) || trim($password) === '') {
            throw new RuntimeException('ADMIN_USER_PASSWORD must be configured before seeding the admin user.');
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
