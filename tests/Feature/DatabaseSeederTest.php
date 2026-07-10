<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use App\Models\GalleryImage;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\OrderInquiry;
use App\Models\Page;
use App\Models\ReservationRequest;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeders_create_demo_records_with_configured_admin_credentials(): void
    {
        $credentials = [
            'name' => 'Configured Admin',
            'email' => 'configured-admin@example.test',
            'password' => 'a-strong-test-password',
        ];

        config()->set('admin.seed_user', $credentials);

        $this->seed();

        $admin = User::query()
            ->where('email', $credentials['email'])
            ->firstOrFail();

        $this->assertSame($credentials['name'], $admin->name);
        $this->assertTrue(Hash::check($credentials['password'], $admin->password));

        $this->assertDatabaseHas(SiteSetting::class, [
            'key' => 'restaurant_name',
        ]);

        $this->assertDatabaseHas(Page::class, [
            'slug' => 'home',
        ]);

        $this->assertGreaterThanOrEqual(4, MenuCategory::query()->count('*'));
        $this->assertGreaterThanOrEqual(12, MenuItem::query()->count('*'));
        $this->assertGreaterThanOrEqual(4, GalleryImage::query()->count('*'));

        $this->assertGreaterThanOrEqual(2, ReservationRequest::query()->count('*'));
        $this->assertGreaterThanOrEqual(2, OrderInquiry::query()->count('*'));
        $this->assertGreaterThanOrEqual(2, ContactInquiry::query()->count('*'));
    }

    public function test_admin_user_seeder_rejects_a_missing_password(): void
    {
        config()->set('admin.seed_user', [
            'name' => 'Configured Admin',
            'email' => 'configured-admin@example.test',
            'password' => null,
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ADMIN_USER_PASSWORD must be configured before seeding the admin user.');

        $this->seed(AdminUserSeeder::class);
    }
}
