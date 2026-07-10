<?php

use App\Mail\ReservationRequestSubmitted;
use App\Models\ReservationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

if (! function_exists('reservationRequestSubmissionTestPayload')) {
    function reservationRequestSubmissionTestPayload(array $overrides = []): array
    {
        return array_merge([
            'customer_name' => 'Maria Santos',
            'phone' => '09171234567',
            'email' => 'maria@example.test',
            'preferred_date' => now()->addDays(7)->toDateString(),
            'preferred_time' => '7:00 PM',
            'guest_count' => 4,
            'special_requests' => 'Window seat if available.',
            'is_banquet_or_event' => false,
            'website' => '',
        ], $overrides);
    }
}

beforeEach(function (): void {
    config([
        'mail.inquiries_to' => 'restaurant@example.test',
    ]);
});

test('reservation request page renders form', function (): void {
    $this->get(route('reservation-request.create'))
        ->assertOk()
        ->assertSeeText('Reservation Request')
        ->assertSee('method="POST"', false)
        ->assertSee('name="customer_name"', false)
        ->assertSee('name="phone"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="preferred_date"', false)
        ->assertSee('name="preferred_time"', false)
        ->assertSee('name="guest_count"', false)
        ->assertSee('name="special_requests"', false)
        ->assertSee('name="website"', false);
});

test('invalid reservation request payload fails validation', function (): void {
    Mail::fake();

    $this->from(route('reservation-request.create'))
        ->post(route('reservation-requests.store'), [])
        ->assertRedirect(route('reservation-request.create'))
        ->assertSessionHasErrors([
            'customer_name',
            'phone',
            'email',
            'preferred_date',
            'preferred_time',
            'guest_count',
        ]);

    $this->assertDatabaseCount('reservation_requests', 0);

    Mail::assertNothingSent();
});

test('valid reservation request payload stores database record', function (): void {
    Mail::fake();

    $payload = reservationRequestSubmissionTestPayload([
        'preferred_date' => now()->addDays(7)->toDateString(),
    ]);

    $this->from(route('reservation-request.create'))
        ->post(route('reservation-requests.store'), $payload)
        ->assertRedirect(route('reservation-request.create'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('reservation_requests', [
        'customer_name' => 'Maria Santos',
        'phone' => '09171234567',
        'email' => 'maria@example.test',
        'preferred_time' => '7:00 PM',
        'guest_count' => 4,
        'special_requests' => 'Window seat if available.',
        'is_banquet_or_event' => false,
    ]);

    $reservationRequest = ReservationRequest::query()
        ->where('email', 'maria@example.test')
        ->firstOrFail();

    expect(Carbon::parse($reservationRequest->preferred_date)->toDateString())
        ->toBe($payload['preferred_date']);
});

test('valid reservation request payload sends email', function (): void {
    Mail::fake();

    $this->from(route('reservation-request.create'))
        ->post(route('reservation-requests.store'), reservationRequestSubmissionTestPayload())
        ->assertRedirect(route('reservation-request.create'))
        ->assertSessionHasNoErrors();

    Mail::assertSent(ReservationRequestSubmitted::class, 1);
});

test('reservation request success message does not imply confirmed booking', function (): void {
    Mail::fake();

    $this->from(route('reservation-request.create'))
        ->post(route('reservation-requests.store'), reservationRequestSubmissionTestPayload())
        ->assertRedirect(route('reservation-request.create'))
        ->assertSessionHas('status');

    $message = mb_strtolower((string) session('status'));

    expect($message)
        ->toContain('received')
        ->toContain('review')
        ->toContain('confirm availability');

    foreach ([
        'booking confirmed',
        'confirmed booking',
        'reservation is confirmed',
        'your reservation is confirmed',
        'table is booked',
        'your table is booked',
        'automatically confirmed',
    ] as $forbiddenPhrase) {
        expect($message)->not->toContain($forbiddenPhrase);
    }
});

test('honeypot reservation request submission is rejected', function (): void {
    Mail::fake();

    $this->from(route('reservation-request.create'))
        ->post(route('reservation-requests.store'), reservationRequestSubmissionTestPayload([
            'website' => 'https://spam.example',
        ]))
        ->assertRedirect(route('reservation-request.create'))
        ->assertSessionHasErrors(['website']);

    $this->assertDatabaseCount('reservation_requests', 0);

    Mail::assertNothingSent();
});
