<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'              => ['required'],
            'email'             => ['required', 'email'],
            'service'           => ['required', Rule::in(array_keys(config('constant.services')))],
            'date'              => ['required', 'date', 'after_or_equal:today'],
            'start_at'          => ['required', 'date_format:H:i'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'service.required' => 'Service is required.',
            'service.in' => 'The selected service does not exist.',
            'date.required' => 'Booking date is required.',
            'date.after_or_equal' => 'Booking date must be today or a future date.',
            'start_at.required' => 'Booking slot must be selected.',
            'start_at.date_format' => 'Booking slot must be in H:i format.'
        ];
    }
}
