<x-mail::message>
# New Reservation Request

A customer submitted a reservation request through {{ config('app.name') }}.

<x-mail::panel>
**Customer Name:** {{ $reservationRequest->customer_name }}

**Email:** [{{ $reservationRequest->email }}](mailto:{{ $reservationRequest->email }})

**Phone:** {{ $reservationRequest->phone }}

**Preferred Date:** {{ $reservationRequest->preferred_date?->format('M j, Y') ?? $reservationRequest->preferred_date }}

**Preferred Time:** {{ $reservationRequest->preferred_time }}

**Guest Count:** {{ $reservationRequest->guest_count }}

@if($reservationRequest->is_banquet_or_event)
**Banquet/Event Inquiry:** Yes
@endif

**Submitted:** {{ $reservationRequest->created_at?->timezone(config('app.timezone'))->format('M j, Y \a\t g:i A') ?? now()->format('M j, Y \a\t g:i A') }}
</x-mail::panel>

@if($reservationRequest->special_requests)
## Special Requests / Notes

{{ $reservationRequest->special_requests }}
@endif

<x-mail::button :url="'mailto:' . $reservationRequest->email">
Reply to Customer
</x-mail::button>

Please review this request and contact the customer directly to confirm availability.

This request is not an automatically confirmed reservation.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
