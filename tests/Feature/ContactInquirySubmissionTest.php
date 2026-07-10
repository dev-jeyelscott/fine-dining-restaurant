<?php

use App\Mail\ContactInquirySubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

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

test('invalid payload fails validation', function (): void {
    Mail::fake();

    $response = $this
        ->from(route('contact.create'))
        ->post(route('contact-inquiries.store'), []);

    $response
        ->assertRedirect(route('contact.create'))
        ->assertSessionHasErrors([
            'customer_name',
            'email',
            'message',
        ]);

    $this->assertDatabaseCount('contact_inquiries', 0);

    Mail::assertNothingSent();
});

test('valid payload stores database record', function () use ($validPayload): void {
    Mail::fake();

    $response = $this
        ->from(route('contact.create'))
        ->post(route('contact-inquiries.store'), $validPayload());

    $response
        ->assertRedirect(route('contact.create'))
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

test('valid payload sends email', function () use ($validPayload): void {
    Mail::fake();

    config([
        'mail.inquiries_to' => 'restaurant@example.test',
    ]);

    $response = $this
        ->from(route('contact.create'))
        ->post(route('contact-inquiries.store'), $validPayload());

    $response
        ->assertRedirect(route('contact.create'))
        ->assertSessionHasNoErrors();

    Mail::assertSent(
        ContactInquirySubmitted::class,
        fn (ContactInquirySubmitted $mail): bool => $mail->hasTo(
            'restaurant@example.test',
        ),
    );
});

test('success message appears', function () use ($validPayload): void {
    Mail::fake();

    $response = $this
        ->from(route('contact.create'))
        ->post(route('contact-inquiries.store'), $validPayload());

    $response
        ->assertRedirect(route('contact.create'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');
});
