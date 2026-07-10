<x-mail::message>
# New Order Inquiry

A customer submitted an order inquiry through {{ config('app.name') }}.

<x-mail::panel>
**Customer Name:** {{ $orderInquiry->customer_name }}

**Email:** [{{ $orderInquiry->email }}](mailto:{{ $orderInquiry->email }})

**Phone:** {{ $orderInquiry->phone }}

**Fulfillment Preference:** {{ str($orderInquiry->fulfillment_type)->headline() }}

**Quantity:** {{ $orderInquiry->quantity }}

**Preferred Pickup/Delivery Time:** {{ $orderInquiry->preferred_time }}

@if($orderInquiry->delivery_address)
**Delivery Address:** {{ $orderInquiry->delivery_address }}
@endif

**Submitted:** {{ $orderInquiry->created_at?->timezone(config('app.timezone'))->format('M j, Y \a\t g:i A') ?? now()->format('M j, Y \a\t g:i A') }}
</x-mail::panel>

## Order Details

{{ $orderInquiry->order_details }}

@if($orderInquiry->special_instructions)
## Special Instructions

{{ $orderInquiry->special_instructions }}
@endif

<x-mail::button :url="'mailto:' . $orderInquiry->email">
Reply to Customer
</x-mail::button>

Please review this inquiry, confirm item availability, final total, payment details, and pickup or delivery arrangements directly with the customer.

This inquiry is not an automatically confirmed or paid order.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
