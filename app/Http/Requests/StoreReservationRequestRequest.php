<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequestRequest extends FormRequest
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
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'string', 'max:40'],
            'guest_count' => ['required', 'integer', 'min:1', 'max:200'],
            'special_requests' => ['nullable', 'string', 'max:2000'],
            'is_banquet_or_event' => ['nullable', 'boolean'],
            'website' => ['nullable', 'prohibited_unless:website,null'],
        ];
    }
}
