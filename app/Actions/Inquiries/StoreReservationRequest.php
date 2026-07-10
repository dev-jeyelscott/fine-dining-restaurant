<?php

namespace App\Actions\Inquiries;

use App\Jobs\SendReservationRequestNotification;
use App\Models\ReservationRequest;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreReservationRequest
{
    /**
     * Store a reservation request and queue the restaurant notification.
     *
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): ReservationRequest
    {
        $reservationRequest = ReservationRequest::create($data);

        $recipient = config('mail.inquiries_to');

        if (! is_string($recipient) || blank($recipient)) {
            Log::warning(
                'Reservation request notification recipient is not configured.',
                [
                    'reservation_request_id' => $reservationRequest->id,
                ],
            );

            return $reservationRequest;
        }

        try {
            SendReservationRequestNotification::dispatch(
                reservationRequestId: $reservationRequest->id,
                recipient: $recipient,
            );
        } catch (Throwable $exception) {
            Log::error('Reservation request notification could not be queued.', [
                'reservation_request_id' => $reservationRequest->id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);
        }

        return $reservationRequest;
    }
}
