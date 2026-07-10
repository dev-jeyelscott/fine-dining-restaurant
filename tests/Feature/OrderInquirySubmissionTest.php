<?php

use App\Mail\OrderInquirySubmitted;
use App\Models\OrderInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config([
        'mail.inquiries_to' => 'restaurant@example.test',
    ]);
});

test('invalid payload fails validation', function (): void {
    Mail::fake();

    $response = $this->from(route('order-inquiry.create'))
        ->post(route('order-inquiries.store'), []);

    $response
        ->assertRedirect(route('order-inquiry.create'))
        ->assertSessionHasErrors([
            'customer_name',
            'phone',
            'email',
            'fulfillment_type',
            'preferred_time',
            'order_details',
            'quantity',
        ]);

    $this->assertDatabaseCount('order_inquiries', 0);

    Mail::assertNothingSent();
});

test('delivery requires delivery address', function (): void {
    Mail::fake();

    $payload = validOrderInquirySubmissionPayload([
        'fulfillment_type' => 'delivery',
        'delivery_address' => '',
    ]);

    $response = $this->from(route('order-inquiry.create'))
        ->post(route('order-inquiries.store'), $payload);

    $response
        ->assertRedirect(route('order-inquiry.create'))
        ->assertSessionHasErrors([
            'delivery_address',
        ]);

    $this->assertDatabaseCount('order_inquiries', 0);

    Mail::assertNothingSent();
});

test('pickup does not require delivery address', function (): void {
    Mail::fake();

    $payload = validOrderInquirySubmissionPayload([
        'fulfillment_type' => 'pickup',
        'delivery_address' => '',
    ]);

    $response = $this->from(route('order-inquiry.create'))
        ->post(route('order-inquiries.store'), $payload);

    $response
        ->assertRedirect(route('order-inquiry.create'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('order_inquiries', [
        'customer_name' => 'Maria Santos',
        'phone' => '09171234567',
        'email' => 'maria@example.test',
        'fulfillment_type' => 'pickup',
        'preferred_time' => 'Tomorrow at 6:30 PM',
        'order_details' => '2x Grilled Salmon, 1x Caesar Salad',
        'quantity' => 3,
        'special_instructions' => 'Please separate the salad dressing.',
    ]);

    Mail::assertSent(OrderInquirySubmitted::class);
});

test('valid payload stores database record', function (): void {
    Mail::fake();

    $payload = validOrderInquirySubmissionPayload([
        'fulfillment_type' => 'delivery',
        'delivery_address' => '123 Mabini Street, Manila',
    ]);

    $response = $this->from(route('order-inquiry.create'))
        ->post(route('order-inquiries.store'), $payload);

    $response
        ->assertRedirect(route('order-inquiry.create'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('order_inquiries', [
        'customer_name' => 'Maria Santos',
        'phone' => '09171234567',
        'email' => 'maria@example.test',
        'fulfillment_type' => 'delivery',
        'preferred_time' => 'Tomorrow at 6:30 PM',
        'order_details' => '2x Grilled Salmon, 1x Caesar Salad',
        'quantity' => 3,
        'special_instructions' => 'Please separate the salad dressing.',
        'delivery_address' => '123 Mabini Street, Manila',
    ]);
});

test('valid payload sends email', function (): void {
    Mail::fake();

    $payload = validOrderInquirySubmissionPayload([
        'fulfillment_type' => 'delivery',
        'delivery_address' => '123 Mabini Street, Manila',
    ]);

    $this->from(route('order-inquiry.create'))
        ->post(route('order-inquiries.store'), $payload)
        ->assertRedirect(route('order-inquiry.create'))
        ->assertSessionHasNoErrors();

    expect(OrderInquiry::query()->count())->toBe(1);

    Mail::assertSent(OrderInquirySubmitted::class, 1);
});

test('success message explains manual review', function (): void {
    Mail::fake();

    $payload = validOrderInquirySubmissionPayload();

    $response = $this->from(route('order-inquiry.create'))
        ->post(route('order-inquiries.store'), $payload);

    $response
        ->assertRedirect(route('order-inquiry.create'))
        ->assertSessionHasNoErrors();

    $message = session('success') ?? session('status');

    expect($message)->not->toBeNull();

    $normalizedMessage = strtolower((string) $message);

    expect($normalizedMessage)
        ->toContain('order inquiry')
        ->toContain('received')
        ->toContain('review')
        ->toContain('confirm');
});

test('no cart payment order status wording appears', function (): void {
    $this->get(route('order-inquiry.create'))
        ->assertOk()
        ->assertDontSeeText('Cart')
        ->assertDontSeeText('Shopping Cart')
        ->assertDontSeeText('Checkout')
        ->assertDontSeeText('Pay Online')
        ->assertDontSeeText('Online Payment')
        ->assertDontSeeText('Payment Status')
        ->assertDontSeeText('Track Order')
        ->assertDontSeeText('Order Status')
        ->assertDontSeeText('Live Order Tracking')
        ->assertDontSeeText('Kitchen Dashboard')
        ->assertDontSeeText('POS');
});

function validOrderInquirySubmissionPayload(array $overrides = []): array
{
    return array_merge([
        'customer_name' => 'Maria Santos',
        'phone' => '09171234567',
        'email' => 'maria@example.test',
        'fulfillment_type' => 'pickup',
        'preferred_time' => 'Tomorrow at 6:30 PM',
        'order_details' => '2x Grilled Salmon, 1x Caesar Salad',
        'quantity' => 3,
        'special_instructions' => 'Please separate the salad dressing.',
        'delivery_address' => '',
        'website' => '',
    ], $overrides);
}
