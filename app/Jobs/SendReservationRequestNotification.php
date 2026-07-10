<?php

namespace App\Jobs;

use App\Mail\ReservationRequestSubmitted;
use App\Models\ReservationRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendReservationRequestNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 20;

    public bool $failOnTimeout = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $reservationRequestId,
        public readonly string $recipient,
    ) {
        $this->afterCommit();
    }

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [60, 300];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reservationRequest = ReservationRequest::query()
            ->find($this->reservationRequestId);

        if (! $reservationRequest) {
            Log::warning(
                'Reservation request notification was skipped because the record no longer exists.',
                [
                    'reservation_request_id' => $this->reservationRequestId,
                ],
            );

            return;
        }

        if ($reservationRequest->notification_sent_at !== null) {
            return;
        }

        Mail::to($this->recipient)
            ->send(new ReservationRequestSubmitted($reservationRequest));

        $reservationRequest->forceFill([
            'notification_sent_at' => now(),
        ])->save();
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Reservation request notification permanently failed.', [
            'reservation_request_id' => $this->reservationRequestId,
            'exception' => $exception::class,
            'message' => $exception->getMessage(),
        ]);
    }
}
