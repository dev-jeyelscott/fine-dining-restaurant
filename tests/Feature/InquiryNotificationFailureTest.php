<?php

use App\Actions\Inquiries\StoreContactInquiry;
use App\Actions\Inquiries\StoreOrderInquiry;
use App\Actions\Inquiries\StoreReservationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

function simulateInquiryQueueFailure(): void
{
    config()->set('mail.inquiries_to', 'restaurant@example.test');

    Queue::shouldReceive('connection')
        ->once()
        ->andThrow(new RuntimeException('Simulated queue failure.'));
}

test('reservation request remains stored when notification queue dispatch fails', function (): void {
    config()->set('mail.inquiries_to', 'restaurant@example.test');
    Log::spy();
    Queue::shouldReceive('connection')
        ->once()
        ->andThrow(new RuntimeException('Simulated queue failure.'));

    $reservationRequest = app(StoreReservationRequest::class)->handle([
        'customer_name' => 'Maria Santos',
        'phone' => '09171234567',
        'email' => 'maria@example.test',
        'preferred_date' => now()->addDays(7)->toDateString(),
        'preferred_time' => '7:00 PM',
        'guest_count' => 4,
        'special_requests' => 'Window seat if available.',
        'is_banquet_or_event' => false,
    ]);

    expect($reservationRequest->exists)->toBeTrue();

    $this->assertDatabaseHas('reservation_requests', [
        'id' => $reservationRequest->getKey(),
    ]);

    expect($reservationRequest->fresh()->notification_sent_at)->toBeNull();

    Log::shouldHaveReceived('error')
        ->once()
        ->withArgs(function (string $message, array $context) use ($reservationRequest): bool {
            return $message === 'Reservation request notification could not be queued.'
                && $context['reservation_request_id'] === $reservationRequest->id
                && $context['exception'] === RuntimeException::class
                && $context['message'] === 'Simulated queue failure.';
        });
});

test('order inquiry remains stored when notification queue dispatch fails', function (): void {
    simulateInquiryQueueFailure();

    $orderInquiry = app(StoreOrderInquiry::class)->handle([
        'customer_name' => 'Maria Santos',
        'phone' => '09171234567',
        'email' => 'maria@example.test',
        'fulfillment_type' => 'pickup',
        'preferred_time' => '6:30 PM',
        'order_details' => 'Two servings of grilled salmon.',
        'quantity' => 2,
        'special_instructions' => 'Please pack the sauce separately.',
        'delivery_address' => null,
    ]);

    expect($orderInquiry->exists)->toBeTrue();

    $this->assertDatabaseHas('order_inquiries', [
        'id' => $orderInquiry->getKey(),
    ]);

    expect($orderInquiry->fresh()->notification_sent_at)->toBeNull();
});

test('contact inquiry remains stored when notification queue dispatch fails', function (): void {
    simulateInquiryQueueFailure();

    $contactInquiry = app(StoreContactInquiry::class)->handle([
        'customer_name' => 'Maria Santos',
        'email' => 'maria@example.test',
        'phone' => '09171234567',
        'subject' => 'Private dining inquiry',
        'message' => 'I would like to ask about your available dining packages.',
    ]);

    expect($contactInquiry->exists)->toBeTrue();

    $this->assertDatabaseHas('contact_inquiries', [
        'id' => $contactInquiry->getKey(),
    ]);

    expect($contactInquiry->fresh()->notification_sent_at)->toBeNull();
});
