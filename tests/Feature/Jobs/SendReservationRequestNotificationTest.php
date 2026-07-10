<?php

use App\Jobs\SendReservationRequestNotification;
use App\Mail\ReservationRequestSubmitted;
use App\Models\ReservationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

if (! function_exists('createReservationRequestForNotificationJob')) {
    function createReservationRequestForNotificationJob(array $overrides = []): ReservationRequest
    {
        return ReservationRequest::query()->create(array_merge([
            'customer_name' => 'Maria Santos',
            'phone' => '09171234567',
            'email' => 'maria@example.test',
            'preferred_date' => now()->addDays(7)->toDateString(),
            'preferred_time' => '7:00 PM',
            'guest_count' => 4,
            'special_requests' => 'Window seat if available.',
            'is_banquet_or_event' => false,
        ], $overrides));
    }
}

test('reservation request notification job sends email and records delivery time', function (): void {
    Mail::fake();

    $reservationRequest = createReservationRequestForNotificationJob();

    $job = new SendReservationRequestNotification(
        reservationRequestId: $reservationRequest->id,
        recipient: 'restaurant@example.test',
    );

    $job->handle();

    Mail::assertSent(
        ReservationRequestSubmitted::class,
        function (ReservationRequestSubmitted $mail) use ($reservationRequest): bool {
            return $mail->reservationRequest->is($reservationRequest)
                && $mail->hasTo('restaurant@example.test');
        },
    );

    expect($reservationRequest->refresh()->notification_sent_at)
        ->not->toBeNull();
});

test('reservation request notification job skips an already notified request', function (): void {
    Mail::fake();

    $reservationRequest = createReservationRequestForNotificationJob([
        'notification_sent_at' => now(),
    ]);

    (new SendReservationRequestNotification(
        reservationRequestId: $reservationRequest->id,
        recipient: 'restaurant@example.test',
    ))->handle();

    Mail::assertNothingSent();
});

test('notification failure leaves the reservation request stored', function (): void {
    $reservationRequest = createReservationRequestForNotificationJob();

    Mail::shouldReceive('to')
        ->once()
        ->with('restaurant@example.test')
        ->andThrow(new RuntimeException('SMTP unavailable.'));

    $job = new SendReservationRequestNotification(
        reservationRequestId: $reservationRequest->id,
        recipient: 'restaurant@example.test',
    );

    expect(fn () => $job->handle())
        ->toThrow(RuntimeException::class, 'SMTP unavailable.');

    $this->assertModelExists($reservationRequest);

    expect($reservationRequest->refresh()->notification_sent_at)
        ->toBeNull();
});

test('reservation request notification job handles a missing record safely', function (): void {
    Mail::fake();
    Log::spy();

    (new SendReservationRequestNotification(
        reservationRequestId: 999_999,
        recipient: 'restaurant@example.test',
    ))->handle();

    Mail::assertNothingSent();

    Log::shouldHaveReceived('warning')
        ->once()
        ->with(
            'Reservation request notification was skipped because the record no longer exists.',
            [
                'reservation_request_id' => 999_999,
            ],
        );
});

test('reservation request notification job logs permanent failure details', function (): void {
    Log::spy();

    $job = new SendReservationRequestNotification(
        reservationRequestId: 123,
        recipient: 'restaurant@example.test',
    );

    $job->failed(new RuntimeException('SMTP unavailable.'));

    Log::shouldHaveReceived('error')
        ->once()
        ->with(
            'Reservation request notification permanently failed.',
            [
                'reservation_request_id' => 123,
                'exception' => RuntimeException::class,
                'message' => 'SMTP unavailable.',
            ],
        );
});
