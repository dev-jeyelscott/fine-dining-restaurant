<?php

namespace App\Jobs;

use App\Mail\ContactInquirySubmitted;
use App\Models\ContactInquiry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendContactInquiryNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 20;

    public bool $failOnTimeout = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $contactInquiryId,
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
        $contactInquiry = ContactInquiry::query()->find($this->contactInquiryId);

        if (! $contactInquiry) {
            Log::warning('Contact inquiry notification was skipped because the record no longer exists.', [
                'contact_inquiry_id' => $this->contactInquiryId,
            ]);

            return;
        }

        if ($contactInquiry->notification_sent_at !== null) {
            return;
        }

        Mail::to($this->recipient)->send(new ContactInquirySubmitted($contactInquiry));

        $contactInquiry->forceFill([
            'notification_sent_at' => now(),
        ])->save();
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Contact inquiry notification permanently failed.', [
            'contact_inquiry_id' => $this->contactInquiryId,
            'exception' => $exception::class,
            'message' => $exception->getMessage(),
        ]);
    }
}
