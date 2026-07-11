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
                'title' => 'A Dining Experience, Beautifully Composed',
                'excerpt' => 'Season-led cuisine, gracious hospitality, and an intimate setting for dinners, celebrations, and private occasions.',
                'content' => 'At Le Jardin, precise technique and generous hospitality come together in a dining experience that feels polished, personal, and warmly familiar.',
                'meta_title' => 'Le Jardin Fine Dining | Seasonal Cuisine & Private Occasions',
                'meta_description' => 'Discover seasonal cuisine, gracious hospitality, private dining, and beautifully considered occasions at Le Jardin Fine Dining.',
                'is_published' => true,
            ],
            [
                'slug' => 'menu',
                'title' => 'A Menu Guided by Season and Craft',
                'excerpt' => 'From delicate first courses to expressive mains and elegant finales, every plate is composed with balance, precision, and character.',
                'content' => 'Explore a considered progression of dishes prepared with premium ingredients, precise technique, and a respect for flavour.',
                'meta_title' => 'Seasonal Menu | Le Jardin Fine Dining',
                'meta_description' => 'Explore Le Jardin’s considered menu of delicate starters, signature mains, elegant desserts, and composed beverages.',
                'is_published' => true,
            ],
            [
                'slug' => 'reservation-request',
                'title' => 'Plan Your Evening at Le Jardin',
                'excerpt' => 'Share your preferred date, time, and party size, and our team will personally confirm availability with you.',
                'content' => 'Tell us when you would like to join us and who will be dining. Every request is reviewed personally so the details of your visit can be handled with care.',
                'meta_title' => 'Reservation Request | Le Jardin Fine Dining',
                'meta_description' => 'Request a table at Le Jardin. Our team personally reviews each preferred date, time, and party size before confirming availability.',
                'is_published' => true,
            ],
            [
                'slug' => 'order-inquiry',
                'title' => 'Bring Le Jardin to Your Table',
                'excerpt' => 'Share your preferred dishes and pickup or delivery details, and our team will personally review availability and pricing with you.',
                'content' => 'Share the dishes you are considering, your preferred timing, and any special notes. Our team will then confirm availability, pricing, and fulfillment arrangements with you.',
                'meta_title' => 'Order Inquiry | Le Jardin Fine Dining',
                'meta_description' => 'Submit an Order Inquiry for pickup or delivery. Our team will confirm availability, final pricing, and arrangements directly with you.',
                'is_published' => true,
            ],
            [
                'slug' => 'gallery',
                'title' => 'A Portrait of Le Jardin',
                'excerpt' => 'Discover the atmosphere, culinary craft, and celebrations that define Le Jardin.',
                'content' => 'From softly lit dining rooms to carefully finished plates and beautifully prepared celebrations, every image reflects our warmth, refinement, and attention to detail.',
                'meta_title' => 'Gallery | Le Jardin Fine Dining',
                'meta_description' => 'Explore Le Jardin’s dining rooms, signature dishes, private celebrations, and beautifully considered details.',
                'is_published' => true,
            ],
            [
                'slug' => 'banquet-hall',
                'title' => 'A Setting for Meaningful Occasions',
                'excerpt' => 'Gather for private dinners, family milestones, celebrations, and corporate occasions in a setting shaped around attentive hospitality.',
                'content' => 'Our banquet hall offers a refined setting for family celebrations, private dining, corporate meals, and life’s most meaningful gatherings. Contact our team to discuss availability and event details.',
                'meta_title' => 'Banquet Hall & Private Dining | Le Jardin Fine Dining',
                'meta_description' => 'Discover Le Jardin’s banquet hall and private-dining setting for family milestones, celebrations, and corporate occasions.',
                'is_published' => true,
            ],
            [
                'slug' => 'contact',
                'title' => 'Begin a Conversation with Le Jardin',
                'excerpt' => 'Whether you are planning a visit, a private occasion, or have a dining question, our team will be pleased to assist.',
                'content' => 'Reach our team by phone, email, location map, or inquiry form. Every message is personally reviewed and answered as soon as practical.',
                'meta_title' => 'Contact Le Jardin Fine Dining',
                'meta_description' => 'Contact Le Jardin for dining questions, directions, Reservation Requests, Order Inquiries, and private occasions.',
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
