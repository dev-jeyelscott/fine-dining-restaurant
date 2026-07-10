<?php

namespace App\Jobs;

use App\Mail\OrderInquirySubmitted;
use App\Models\OrderInquiry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendOrderInquiryNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 20;

    public bool $failOnTimeout = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $orderInquiryId,
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
        $orderInquiry = OrderInquiry::query()->find($this->orderInquiryId);

        if (! $orderInquiry) {
            Log::warning('Order inquiry notification was skipped because the record no longer exists.', [
                'order_inquiry_id' => $this->orderInquiryId,
            ]);

            return;
        }

        if ($orderInquiry->notification_sent_at !== null) {
            return;
        }

        Mail::to($this->recipient)->send(new OrderInquirySubmitted($orderInquiry));

        $orderInquiry->forceFill([
            'notification_sent_at' => now(),
        ])->save();
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Order inquiry notification permanently failed.', [
            'order_inquiry_id' => $this->orderInquiryId,
            'exception' => $exception::class,
            'message' => $exception->getMessage(),
        ]);
    }
}
