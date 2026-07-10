<?php

namespace App\Actions\Inquiries;

use App\Mail\ReservationRequestSubmitted;
use App\Models\ReservationRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class StoreReservationRequest
{
    /**
     * Store a reservation request and attempt to notify the restaurant.
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
            Mail::to($recipient)
                ->send(new ReservationRequestSubmitted($reservationRequest));

            $reservationRequest->forceFill([
                'notification_sent_at' => now(),
            ])->save();
        } catch (Throwable $exception) {
            Log::error('Reservation request notification failed.', [
                'reservation_request_id' => $reservationRequest->id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);
        }

        return $reservationRequest;
    }
}
