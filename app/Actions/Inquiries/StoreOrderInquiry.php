<?php

namespace App\Actions\Inquiries;

use App\Mail\OrderInquirySubmitted;
use App\Models\OrderInquiry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class StoreOrderInquiry
{
    /**
     * Store an order inquiry and attempt to notify the restaurant.
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
            Mail::to($recipient)
                ->send(new OrderInquirySubmitted($orderInquiry));

            $orderInquiry->forceFill([
                'notification_sent_at' => now(),
            ])->save();
        } catch (Throwable $exception) {
            Log::error('Order inquiry notification failed.', [
                'order_inquiry_id' => $orderInquiry->id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);
        }

        return $orderInquiry;
    }
}
