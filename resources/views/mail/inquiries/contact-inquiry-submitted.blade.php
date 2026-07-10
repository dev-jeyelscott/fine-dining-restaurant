<x-mail::message>
# New Contact Inquiry

A customer submitted a contact inquiry through {{ config('app.name') }}.

<x-mail::panel>
**Customer Name:** {{ $contactInquiry->customer_name }}

**Email:** [{{ $contactInquiry->email }}](mailto:{{ $contactInquiry->email }})

@if($contactInquiry->phone)
**Phone:** {{ $contactInquiry->phone }}
@endif

@if($contactInquiry->subject)
**Subject:** {{ $contactInquiry->subject }}
@endif

**Submitted:** {{ $contactInquiry->created_at?->timezone(config('app.timezone'))->format('M j, Y \a\t g:i A') ?? now()->format('M j, Y \a\t g:i A') }}
</x-mail::panel>

## Message

{{ $contactInquiry->message }}

<x-mail::button :url="'mailto:' . $contactInquiry->email">
Reply to Customer
</x-mail::button>

Please review this inquiry and follow up with the customer directly.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
