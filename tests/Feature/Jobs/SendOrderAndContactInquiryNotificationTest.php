<?php

use App\Jobs\SendContactInquiryNotification;
use App\Jobs\SendOrderInquiryNotification;
use App\Mail\ContactInquirySubmitted;
use App\Mail\OrderInquirySubmitted;
use App\Models\ContactInquiry;
use App\Models\OrderInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function createOrderInquiryForNotificationJob(array $overrides = []): OrderInquiry
{
    return OrderInquiry::query()->create(array_merge([
        'customer_name' => 'Maria Santos',
        'phone' => '09171234567',
        'email' => 'maria@example.test',
        'fulfillment_type' => 'pickup',
        'preferred_time' => '6:30 PM',
        'order_details' => 'Two servings of grilled salmon.',
        'quantity' => 2,
        'special_instructions' => null,
        'delivery_address' => null,
    ], $overrides));
}

function createContactInquiryForNotificationJob(array $overrides = []): ContactInquiry
{
    return ContactInquiry::query()->create(array_merge([
        'customer_name' => 'Maria Santos',
        'email' => 'maria@example.test',
        'phone' => '09171234567',
        'subject' => 'Private dining inquiry',
        'message' => 'I would like to ask about available dining packages.',
    ], $overrides));
}

test('order inquiry notification job sends email and records delivery time', function (): void {
    Mail::fake();
    $orderInquiry = createOrderInquiryForNotificationJob();

    (new SendOrderInquiryNotification($orderInquiry->id, 'restaurant@example.test'))->handle();

    Mail::assertSent(OrderInquirySubmitted::class, fn (OrderInquirySubmitted $mail): bool => (
        $mail->orderInquiry->is($orderInquiry)
        && $mail->hasTo('restaurant@example.test')
    ));

    expect($orderInquiry->refresh()->notification_sent_at)->not->toBeNull();
});

test('contact inquiry notification job sends email and records delivery time', function (): void {
    Mail::fake();
    $contactInquiry = createContactInquiryForNotificationJob();

    (new SendContactInquiryNotification($contactInquiry->id, 'restaurant@example.test'))->handle();

    Mail::assertSent(ContactInquirySubmitted::class, fn (ContactInquirySubmitted $mail): bool => (
        $mail->contactInquiry->is($contactInquiry)
        && $mail->hasTo('restaurant@example.test')
    ));

    expect($contactInquiry->refresh()->notification_sent_at)->not->toBeNull();
});

test('order inquiry notification job skips an already notified inquiry', function (): void {
    Mail::fake();
    $orderInquiry = createOrderInquiryForNotificationJob(['notification_sent_at' => now()]);

    (new SendOrderInquiryNotification($orderInquiry->id, 'restaurant@example.test'))->handle();

    Mail::assertNothingSent();
});

test('contact inquiry notification job skips an already notified inquiry', function (): void {
    Mail::fake();
    $contactInquiry = createContactInquiryForNotificationJob(['notification_sent_at' => now()]);

    (new SendContactInquiryNotification($contactInquiry->id, 'restaurant@example.test'))->handle();

    Mail::assertNothingSent();
});

test('order inquiry notification failure leaves the inquiry stored', function (): void {
    $orderInquiry = createOrderInquiryForNotificationJob();

    Mail::shouldReceive('to')
        ->once()
        ->with('restaurant@example.test')
        ->andThrow(new RuntimeException('SMTP unavailable.'));

    expect(fn () => (new SendOrderInquiryNotification($orderInquiry->id, 'restaurant@example.test'))->handle())
        ->toThrow(RuntimeException::class, 'SMTP unavailable.');

    $this->assertModelExists($orderInquiry);
    expect($orderInquiry->refresh()->notification_sent_at)->toBeNull();
});

test('contact inquiry notification failure leaves the inquiry stored', function (): void {
    $contactInquiry = createContactInquiryForNotificationJob();

    Mail::shouldReceive('to')
        ->once()
        ->with('restaurant@example.test')
        ->andThrow(new RuntimeException('SMTP unavailable.'));

    expect(fn () => (new SendContactInquiryNotification($contactInquiry->id, 'restaurant@example.test'))->handle())
        ->toThrow(RuntimeException::class, 'SMTP unavailable.');

    $this->assertModelExists($contactInquiry);
    expect($contactInquiry->refresh()->notification_sent_at)->toBeNull();
});
