<?php

use App\Jobs\SendReservationRequestNotification;
use App\Models\ReservationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

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
        ->assertSee('max="200"', false)
        ->assertSee('name="special_requests"', false)
        ->assertSee('name="website"', false);
});

test('reservation request page uses the luxury homepage design language', function (): void {
    $this->get(route('reservation-request.create'))
        ->assertOk()
        ->assertSeeText('An evening worth anticipating')
        ->assertSeeText('Your table, thoughtfully prepared')
        ->assertSeeText('How your request works')
        ->assertSee('data-reservation-hero', false)
        ->assertSee('data-reservation-form', false)
        ->assertSee('bg-brand-ivory', false)
        ->assertSee('font-display', false);
});

test('reservation request page exposes progressive motion hooks', function (): void {
    $this->get(route('reservation-request.create'))
        ->assertOk()
        ->assertSee('data-home-motion', false)
        ->assertSee('data-reservation-motion', false)
        ->assertSee('data-gsap="hero-image"', false)
        ->assertSee('data-gsap="hero-content"', false)
        ->assertSee('data-reservation-sidebar', false)
        ->assertSee('data-reservation-process-step', false)
        ->assertSee('data-reservation-notice', false)
        ->assertSee('data-reservation-field-group="identity"', false)
        ->assertSee('data-reservation-field-group="contact"', false)
        ->assertSee('data-reservation-field-group="schedule"', false)
        ->assertSee('data-reservation-field-group="event"', false)
        ->assertSee('data-reservation-field-group="notes"', false)
        ->assertSee('data-reservation-field-group="submit"', false)
        ->assertSee('data-reservation-form', false);
});

test('reservation success feedback remains an accessible status region', function (): void {
    $this->withSession([
        'status' => 'Your reservation request has been received for review.',
    ])->get(route('reservation-request.create'))
        ->assertOk()
        ->assertSee('data-reservation-feedback', false)
        ->assertSee('role="status"', false);
});

test('reservation validation feedback remains an accessible alert region', function (): void {
    Queue::fake();

    $this->followingRedirects()
        ->from(route('reservation-request.create'))
        ->post(route('reservation-requests.store'), [])
        ->assertOk()
        ->assertSee('data-reservation-feedback', false)
        ->assertSee('role="alert"', false);
});

test('invalid reservation request payload fails validation', function (): void {
    Queue::fake();

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

    Queue::assertNothingPushed();
});

test('reservation request accepts no more than 200 guests', function (): void {
    Queue::fake();

    $this->from(route('reservation-request.create'))
        ->post(route('reservation-requests.store'), reservationRequestSubmissionTestPayload([
            'guest_count' => 201,
        ]))
        ->assertRedirect(route('reservation-request.create'))
        ->assertSessionHasErrors(['guest_count']);

    $this->assertDatabaseCount('reservation_requests', 0);

    Queue::assertNothingPushed();
});

test('valid reservation request payload stores database record', function (): void {
    Queue::fake();

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

test('valid reservation request payload queues notification', function (): void {
    Queue::fake();

    $this->from(route('reservation-request.create'))
        ->post(route('reservation-requests.store'), reservationRequestSubmissionTestPayload())
        ->assertRedirect(route('reservation-request.create'))
        ->assertSessionHasNoErrors();

    $reservationRequest = ReservationRequest::query()->firstOrFail();

    Queue::assertPushed(
        SendReservationRequestNotification::class,
        fn (SendReservationRequestNotification $job): bool => (
            $job->reservationRequestId === $reservationRequest->id
            && $job->recipient === 'restaurant@example.test'
        ),
    );

    expect($reservationRequest->notification_sent_at)->toBeNull();
});

test('reservation request success message does not imply confirmed booking', function (): void {
    Queue::fake();

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
    Queue::fake();

    $this->from(route('reservation-request.create'))
        ->post(route('reservation-requests.store'), reservationRequestSubmissionTestPayload([
            'website' => 'https://spam.example',
        ]))
        ->assertRedirect(route('reservation-request.create'))
        ->assertSessionHasErrors(['website']);

    $this->assertDatabaseCount('reservation_requests', 0);

    Queue::assertNothingPushed();
});

test('reservation request is stored when the notification recipient is missing', function (): void {
    Queue::fake();
    Log::spy();

    config([
        'mail.inquiries_to' => null,
    ]);

    $this->from(route('reservation-request.create'))
        ->post(route('reservation-requests.store'), reservationRequestSubmissionTestPayload())
        ->assertRedirect(route('reservation-request.create'))
        ->assertSessionHasNoErrors();

    $reservationRequest = ReservationRequest::query()->firstOrFail();

    expect($reservationRequest->exists)->toBeTrue();

    Queue::assertNothingPushed();

    Log::shouldHaveReceived('warning')
        ->once()
        ->with(
            'Reservation request notification recipient is not configured.',
            [
                'reservation_request_id' => $reservationRequest->id,
            ],
        );
});
