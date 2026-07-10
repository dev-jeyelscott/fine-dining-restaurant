<?php

namespace App\Actions\Inquiries;

use App\Jobs\SendOrderInquiryNotification;
use App\Models\OrderInquiry;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreOrderInquiry
{
    /**
     * Store an order inquiry and queue the restaurant notification.
     *
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): OrderInquiry
    {
        $orderInquiry = OrderInquiry::create($data);

        $recipient = config('mail.inquiries_to');

        if (! is_string($recipient) || blank($recipient)) {
            Log::warning(
                'Order inquiry notification recipient is not configured.',
                [
                    'order_inquiry_id' => $orderInquiry->id,
                ],
            );

            return $orderInquiry;
        }

        try {
            SendOrderInquiryNotification::dispatch(
                orderInquiryId: $orderInquiry->id,
                recipient: $recipient,
            );
        } catch (Throwable $exception) {
            Log::error('Order inquiry notification could not be queued.', [
                'order_inquiry_id' => $orderInquiry->id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);
        }

        return $orderInquiry;
    }
}
