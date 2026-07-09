<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'restaurant_name', 'value' => 'Le Jardin Fine Dining', 'group' => 'general'],
            ['key' => 'tagline', 'value' => 'Elegant dining for families, guests, and special occasions.', 'group' => 'general'],

            ['key' => 'phone', 'value' => '+1 (555) 123-4567', 'group' => 'contact'],
            ['key' => 'email', 'value' => 'hello@example.com', 'group' => 'contact'],
            ['key' => 'address', 'value' => '123 Grand Avenue, New York, NY 10001', 'group' => 'contact'],
            ['key' => 'map_url', 'value' => 'https://maps.google.com', 'group' => 'contact'],

            ['key' => 'facebook_url', 'value' => 'https://facebook.com', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com', 'group' => 'social'],

            ['key' => 'meta_title', 'value' => 'Le Jardin Fine Dining', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'A fine-dining restaurant experience with curated menus, elegant ambiance, banquet hall options, and request-based reservations.', 'group' => 'seo'],
            ['key' => 'notification_recipient_email', 'value' => 'restaurant@example.com', 'group' => 'mail'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting,
            );
        }
    }
}
