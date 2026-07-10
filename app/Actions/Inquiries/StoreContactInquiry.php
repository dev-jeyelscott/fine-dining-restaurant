<?php

namespace App\Actions\Inquiries;

use App\Jobs\SendContactInquiryNotification;
use App\Models\ContactInquiry;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreContactInquiry
{
    /**
     * Store a contact inquiry and queue the restaurant notification.
     *
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): ContactInquiry
    {
        $contactInquiry = ContactInquiry::create($data);

        $recipient = config('mail.inquiries_to');

        if (! is_string($recipient) || blank($recipient)) {
            Log::warning(
                'Contact inquiry notification recipient is not configured.',
                [
                    'contact_inquiry_id' => $contactInquiry->id,
                ],
            );

            return $contactInquiry;
        }

        try {
            SendContactInquiryNotification::dispatch(
                contactInquiryId: $contactInquiry->id,
                recipient: $recipient,
            );
        } catch (Throwable $exception) {
            Log::error('Contact inquiry notification could not be queued.', [
                'contact_inquiry_id' => $contactInquiry->id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);
        }

        return $contactInquiry;
    }
}
