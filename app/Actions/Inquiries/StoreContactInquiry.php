<?php

namespace App\Actions\Inquiries;

use App\Mail\ContactInquirySubmitted;
use App\Models\ContactInquiry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class StoreContactInquiry
{
    /**
     * Store a contact inquiry and attempt to notify the restaurant.
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
            Mail::to($recipient)
                ->send(new ContactInquirySubmitted($contactInquiry));

            $contactInquiry->forceFill([
                'notification_sent_at' => now(),
            ])->save();
        } catch (Throwable $exception) {
            Log::error('Contact inquiry notification failed.', [
                'contact_inquiry_id' => $contactInquiry->id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);
        }

        return $contactInquiry;
    }
}
