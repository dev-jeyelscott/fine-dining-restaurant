<?php

use App\Jobs\SendContactInquiryNotification;
use App\Models\ContactInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

$validPayload = static function (array $overrides = []): array {
    return array_merge([
        'customer_name' => 'Maria Santos',
        'email' => 'maria@example.com',
        'phone' => '09171234567',
        'subject' => 'Private dining inquiry',
        'message' => 'Hello, I would like to ask about your available dining packages.',
        'website' => '',
    ], $overrides);
};

$contactFormUrl = static fn (): string => route('contact.create').'#contact-inquiry';

test('invalid payload fails validation', function () use ($contactFormUrl): void {
    Queue::fake();

    $response = $this
        ->from(route('contact.create'))
        ->post(route('contact-inquiries.store'), [
            'subject' => 'Banquet inquiry',
        ]);

    $response
        ->assertRedirect($contactFormUrl())
        ->assertSessionHasErrors([
            'customer_name',
            'email',
            'message',
        ])
        ->assertSessionHasInput('subject', 'Banquet inquiry');

    $this->assertDatabaseCount('contact_inquiries', 0);

    Queue::assertNothingPushed();
});

test('valid payload stores database record', function () use ($contactFormUrl, $validPayload): void {
    Queue::fake();

    $response = $this
        ->from(route('contact.create'))
        ->post(route('contact-inquiries.store'), $validPayload());

    $response
        ->assertRedirect($contactFormUrl())
        ->assertSessionHasNoErrors();

    $this->assertDatabaseCount('contact_inquiries', 1);

    $this->assertDatabaseHas('contact_inquiries', [
        'customer_name' => 'Maria Santos',
        'email' => 'maria@example.com',
        'phone' => '09171234567',
        'subject' => 'Private dining inquiry',
        'message' => 'Hello, I would like to ask about your available dining packages.',
    ]);
});

test('valid payload queues notification', function () use ($contactFormUrl, $validPayload): void {
    Queue::fake();

    config([
        'mail.inquiries_to' => 'restaurant@example.test',
    ]);

    $response = $this
        ->from(route('contact.create'))
        ->post(route('contact-inquiries.store'), $validPayload());

    $response
        ->assertRedirect($contactFormUrl())
        ->assertSessionHasNoErrors();

    $contactInquiry = ContactInquiry::query()->firstOrFail();

    Queue::assertPushed(
        SendContactInquiryNotification::class,
        fn (SendContactInquiryNotification $job): bool => (
            $job->contactInquiryId === $contactInquiry->id
            && $job->recipient === 'restaurant@example.test'
        ),
    );

    expect($contactInquiry->notification_sent_at)->toBeNull();
});

test('success message appears', function () use ($contactFormUrl, $validPayload): void {
    Queue::fake();

    $response = $this
        ->from(route('contact.create'))
        ->post(route('contact-inquiries.store'), $validPayload());

    $response
        ->assertRedirect($contactFormUrl())
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');
});
