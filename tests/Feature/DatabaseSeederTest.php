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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeders_create_demo_records(): void
    {
        $this->seed();

        $this->assertDatabaseHas(User::class, [
            'email' => 'admin@example.com',
        ]);

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
}
