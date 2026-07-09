<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'home',
                'title' => 'Welcome to Le Jardin',
                'excerpt' => 'A refined dining experience for families, guests, and special occasions.',
                'content' => 'Enjoy carefully prepared dishes, warm hospitality, and an elegant restaurant atmosphere designed for memorable dining experiences.',
                'meta_title' => 'Home | Le Jardin Fine Dining',
                'meta_description' => 'Discover Le Jardin Fine Dining, featuring curated menus, elegant ambiance, banquet options, and request-based reservations.',
                'is_published' => true,
            ],
            [
                'slug' => 'menu',
                'title' => 'Our Menu',
                'excerpt' => 'Explore chef-prepared dishes, seasonal favorites, and refined dining selections.',
                'content' => 'Our menu features appetizers, entrées, desserts, and beverages prepared with quality ingredients and thoughtful presentation.',
                'meta_title' => 'Menu | Le Jardin Fine Dining',
                'meta_description' => 'Browse menu categories, dish descriptions, and prices from Le Jardin Fine Dining.',
                'is_published' => true,
            ],
            [
                'slug' => 'reservation-request',
                'title' => 'Reservation Request',
                'excerpt' => 'Submit your preferred dining date and time for manual restaurant review.',
                'content' => 'Reservation requests are reviewed by our team. A submitted request is not yet a confirmed reservation.',
                'meta_title' => 'Reservation Request | Le Jardin Fine Dining',
                'meta_description' => 'Submit a reservation request for manual confirmation by the restaurant team.',
                'is_published' => true,
            ],
            [
                'slug' => 'order-inquiry',
                'title' => 'Order Inquiry',
                'excerpt' => 'Send your pickup or delivery inquiry for manual restaurant review.',
                'content' => 'Order inquiries are reviewed by our team. Availability, final total, payment, and pickup or delivery details are confirmed directly with you.',
                'meta_title' => 'Order Inquiry | Le Jardin Fine Dining',
                'meta_description' => 'Submit an order inquiry for pickup or delivery review by the restaurant team.',
                'is_published' => true,
            ],
            [
                'slug' => 'gallery',
                'title' => 'Gallery',
                'excerpt' => 'View our restaurant interiors, dishes, events, and ambiance.',
                'content' => 'Browse a preview of our dining experience, food presentation, banquet spaces, and restaurant atmosphere.',
                'meta_title' => 'Gallery | Le Jardin Fine Dining',
                'meta_description' => 'View restaurant interiors, dishes, events, and ambiance photos.',
                'is_published' => true,
            ],
            [
                'slug' => 'banquet-hall',
                'title' => 'Banquet Hall',
                'excerpt' => 'Host family gatherings, private dining, and special events in an elegant setting.',
                'content' => 'Our banquet hall supports private dining, celebrations, corporate gatherings, and special occasions. Please contact us to discuss availability and event details.',
                'meta_title' => 'Banquet Hall | Le Jardin Fine Dining',
                'meta_description' => 'Explore banquet hall information, event types, capacity, and inquiry options.',
                'is_published' => true,
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'excerpt' => 'Reach our team for general questions, directions, and dining inquiries.',
                'content' => 'Contact us by phone, email, location map, or our inquiry form. Our team will respond as soon as possible.',
                'meta_title' => 'Contact | Le Jardin Fine Dining',
                'meta_description' => 'Contact Le Jardin Fine Dining by phone, email, map, or inquiry form.',
                'is_published' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page,
            );
        }
    }
}
