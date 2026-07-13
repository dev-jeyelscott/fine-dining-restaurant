<?php

use App\Filament\Resources\ContactInquiries\ContactInquiryResource;
use App\Filament\Resources\OrderInquiries\OrderInquiryResource;
use App\Filament\Resources\ReservationRequests\ReservationRequestResource;
use App\Models\ContactInquiry;
use App\Models\OrderInquiry;
use App\Models\ReservationRequest;
use App\Models\User;

function inquiryDetailAdminUser(): User
{
    config(['admin.seed_user.email' => 'admin@example.com']);

    return User::factory()->create([
        'email' => 'admin@example.com',
    ]);
}

function inquiryDetailRecords(): array
{
    $reservationRequest = ReservationRequest::query()->create([
        'customer_name' => 'Ada Reservation',
        'phone' => '+63 900 000 0001',
        'email' => 'ada.reservation@example.com',
        'preferred_date' => '2026-08-15',
        'preferred_time' => '7:00 PM',
        'guest_count' => 4,
        'special_requests' => 'A quiet table, if available.',
        'is_banquet_or_event' => true,
        'is_read' => false,
        'notification_sent_at' => '2026-07-13 08:30:00',
    ]);

    $orderInquiry = OrderInquiry::query()->create([
        'customer_name' => 'Owen Inquiry',
        'phone' => '+63 900 000 0002',
        'email' => 'owen.inquiry@example.com',
        'fulfillment_type' => 'delivery',
        'preferred_time' => 'Tomorrow evening',
        'order_details' => 'Two trays for a family gathering.',
        'special_instructions' => 'Please call before preparing.',
        'delivery_address' => '12 Orchard Lane, Manila',
        'is_read' => false,
        'notification_sent_at' => '2026-07-13 08:30:00',
    ]);

    $orderInquiry->items()->create([
        'item_name_snapshot' => 'Truffle Pasta',
        'quantity' => 2,
        'notes' => 'No mushrooms.',
    ]);

    $contactInquiry = ContactInquiry::query()->create([
        'customer_name' => 'Cora Contact',
        'email' => 'cora.contact@example.com',
        'phone' => null,
        'subject' => null,
        'message' => 'Could you share your private dining options?',
        'is_read' => true,
        'notification_sent_at' => '2026-07-13 08:30:00',
    ]);

    return [$reservationRequest, $orderInquiry, $contactInquiry];
}

test('authorized admins can review branded inquiry detail pages', function () {
    $this->actingAs(inquiryDetailAdminUser());

    [$reservationRequest, $orderInquiry, $contactInquiry] = inquiryDetailRecords();

    $this->get(ReservationRequestResource::getUrl('view', ['record' => $reservationRequest]))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Guest contact',
            'Ada Reservation',
            'ada.reservation@example.com',
            'Reservation request',
            'This is a request for staff review and is not a confirmed reservation.',
            'Banquet or event',
            'A quiet table, if available.',
            'Review details',
            'Notification sent',
            'Jul 13, 2026 08:30:00',
        ]);

    $this->get(OrderInquiryResource::getUrl('view', ['record' => $orderInquiry]))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Customer contact',
            'Owen Inquiry',
            'owen.inquiry@example.com',
            'Order inquiry',
            'This is an inquiry for staff review and is not a paid or fulfilled order.',
            'Delivery address',
            '12 Orchard Lane, Manila',
            'Two trays for a family gathering.',
            'Special instructions',
            'Please call before preparing.',
            'Requested items',
            'Truffle Pasta',
            'No mushrooms.',
            'Review details',
            'Notification sent',
            'Jul 13, 2026 08:30:00',
        ]);

    $this->get(ContactInquiryResource::getUrl('view', ['record' => $contactInquiry]))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Customer contact',
            'Cora Contact',
            'cora.contact@example.com',
            'No phone number was provided.',
            'Message',
            'This inquiry is awaiting manual restaurant review.',
            'No subject was provided.',
            'Could you share your private dining options?',
            'Review details',
            'Notification sent',
            'Jul 13, 2026 08:30:00',
        ]);
});

test('public and unauthorized users cannot view inquiry detail records', function () {
    [$reservationRequest, $orderInquiry, $contactInquiry] = inquiryDetailRecords();

    foreach ([
        ReservationRequestResource::getUrl('view', ['record' => $reservationRequest]),
        OrderInquiryResource::getUrl('view', ['record' => $orderInquiry]),
        ContactInquiryResource::getUrl('view', ['record' => $contactInquiry]),
    ] as $url) {
        $this->get($url)->assertRedirect();
    }

    config(['admin.seed_user.email' => 'admin@example.com']);

    $this->actingAs(User::factory()->create(['email' => 'staff@example.com']));

    foreach ([
        ReservationRequestResource::getUrl('view', ['record' => $reservationRequest]),
        OrderInquiryResource::getUrl('view', ['record' => $orderInquiry]),
        ContactInquiryResource::getUrl('view', ['record' => $contactInquiry]),
    ] as $url) {
        $this->get($url)->assertForbidden();
    }
});
