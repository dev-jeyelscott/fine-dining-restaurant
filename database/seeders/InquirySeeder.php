<?php

namespace Database\Seeders;

use App\Models\ContactInquiry;
use App\Models\OrderInquiry;
use App\Models\ReservationRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        ReservationRequest::updateOrCreate(
            [
                'email' => 'maria@example.com',
                'preferred_date' => Carbon::now()->addDays(3)->toDateString(),
            ],
            [
                'customer_name' => 'Maria Santos',
                'phone' => '+1 (555) 201-1001',
                'preferred_time' => '7:00 PM',
                'guest_count' => 4,
                'special_requests' => 'Window-side seating if available. This is only a request.',
                'is_banquet_or_event' => false,
                'is_read' => false,
                'notification_sent_at' => null,
            ],
        );

        ReservationRequest::updateOrCreate(
            [
                'email' => 'daniel@example.com',
                'preferred_date' => Carbon::now()->addWeeks(2)->toDateString(),
            ],
            [
                'customer_name' => 'Daniel Reyes',
                'phone' => '+1 (555) 201-1002',
                'preferred_time' => '6:30 PM',
                'guest_count' => 18,
                'special_requests' => 'Family celebration inquiry. Please contact us to discuss banquet options.',
                'is_banquet_or_event' => true,
                'is_read' => false,
                'notification_sent_at' => null,
            ],
        );

        OrderInquiry::updateOrCreate(
            [
                'email' => 'alex@example.com',
                'preferred_time' => 'Tomorrow, 5:30 PM',
            ],
            [
                'customer_name' => 'Alex Morgan',
                'phone' => '+1 (555) 301-2001',
                'fulfillment_type' => 'pickup',
                'order_details' => "2x Herb-Crusted Salmon\n1x Classic Crème Brûlée",
                'special_instructions' => 'Please confirm availability and final total before pickup.',
                'delivery_address' => null,
                'is_read' => false,
                'notification_sent_at' => null,
            ],
        );

        OrderInquiry::updateOrCreate(
            [
                'email' => 'jamie@example.com',
                'preferred_time' => 'Friday, 7:00 PM',
            ],
            [
                'customer_name' => 'Jamie Lee',
                'phone' => '+1 (555) 301-2002',
                'fulfillment_type' => 'delivery',
                'order_details' => "1x Filet Mignon\n2x Burrata Caprese\n2x Sparkling Citrus Refresher",
                'special_instructions' => 'Please call to confirm delivery availability and final amount.',
                'delivery_address' => '456 Park Lane, New York, NY 10001',
                'is_read' => false,
                'notification_sent_at' => null,
            ],
        );

        ContactInquiry::updateOrCreate(
            [
                'email' => 'sophia@example.com',
                'subject' => 'Private dinner inquiry',
            ],
            [
                'customer_name' => 'Sophia Williams',
                'phone' => '+1 (555) 401-3001',
                'message' => 'Hello, I would like to ask about hosting a private dinner for 12 guests next month.',
                'is_read' => false,
            ],
        );

        ContactInquiry::updateOrCreate(
            [
                'email' => 'liam@example.com',
                'subject' => 'Menu question',
            ],
            [
                'customer_name' => 'Liam Carter',
                'phone' => '+1 (555) 401-3002',
                'message' => 'Do you have vegetarian options available for dinner service?',
                'is_read' => true,
            ],
        );
    }
}
