<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderInquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:160'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'fulfillment_type' => ['required', 'in:pickup,delivery'],
            'preferred_time' => ['required', 'string', 'max:80'],
            'order_details' => ['required', 'string', 'max:5000'],
            'special_instructions' => ['nullable', 'string', 'max:2000'],
            'delivery_address' => [
                'nullable',
                'required_if:fulfillment_type,delivery',
                'string',
                'max:500',
            ],
            'website' => ['nullable', 'prohibited_unless:website,null'],
        ];
    }
}
